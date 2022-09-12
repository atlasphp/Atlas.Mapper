<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Define;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use ReflectionNamedType;
use ReflectionProperty;
use SplObjectStorage;

class ManyToMany extends RegularRelationship
{
    protected string $throughName;

    protected Relationship $throughRelationship;

    protected ?string $throughNativeRelatedName = null;

    protected ?string $throughForeignRelatedName = null;

    protected RecordSet $throughRecordSet;

    public function __construct(
        protected string $name,
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass,
        string $foreignMapperClass,
        Define\ManyToMany $attribute,
        RelationshipLocator $relationshipLocator
    ) {
        $this->throughName = $attribute->through;

        // In ThreadRelated, ManyToMany property $tags is defined as going
        // through a OneToMany property $taggings, but $taggings does not exist.
        if (! $relationshipLocator->has($this->throughName)) {
            throw new Exception\ThroughPropertyDoesNotExist(
                $this->nativeMapperClass,
                $this->name,
                $this->throughName
            );
        }

        $this->throughRelationship = $relationshipLocator->get($this->throughName);

        $throughForeignMapper = $this->throughRelationship->getForeignMapper();
        $this->throughRecordSet = $throughForeignMapper->newRecordSet();

        $throughForeignRelationshipLocator = $throughForeignMapper->getRelationshipLocator();
        $relatedNames = $throughForeignRelationshipLocator->getNames();

        foreach ($relatedNames as $relatedName) {
            $relationship = $throughForeignRelationshipLocator->get($relatedName);

            if (! $relationship instanceof ManyToOne) {
                continue;
            }

            if ($relationship->foreignMapperClass === $this->nativeMapperClass) {
                $this->throughNativeRelatedName = $relatedName;
            }

            if ($relationship->foreignMapperClass === $foreignMapperClass) {
                $this->throughForeignRelatedName = $relatedName;
                if (empty($attribute->on)) {
                    $attribute->on = $relationship->on;
                }
            }
        }

        // DataSource\Thread\ThreadRelated::$tags goes through
        // DataSource\Tagging\TaggingRecordSet $taggings,
        // but DataSource\Tagging\TaggingRelated does not define
        // a ManyToOne property relating to a ThreadRecord.
        if (! $this->throughNativeRelatedName) {
            throw new Exception\ThroughRelatedDoesNotExist(
                $this->nativeMapperClass,
                $this->name,
                $this->throughName,
                $throughForeignRelationshipLocator->getNativeRelatedClass(),
                $this->nativeMapperClass,
            );
        }

        // DataSource\Thread\ThreadRelated::$tags goes through
        // DataSource\Tagging\TaggingRecordSet $taggings,
        // but DataSource\Tagging\TaggingRelated does not define
        // a ManyToOne property relating to a TagRecord.
        if (! $this->throughForeignRelatedName) {
            throw new Exception\ThroughRelatedDoesNotExist(
                $this->nativeMapperClass,
                $this->name,
                $this->throughName,
                $throughForeignRelationshipLocator->getNativeRelatedClass(),
                $foreignMapperClass,
            );
        }

        parent::__construct(
            $name,
            $mapperLocator,
            $nativeMapperClass,
            $foreignMapperClass,
            $attribute
        );
    }

    public function getPersistencePriority() : string
    {
        return static::BEFORE_NATIVE;
    }

    public function joinSelect(
        MapperSelect $select,
        string $join,
        string $nativeAlias, // threads
        string $foreignAlias, // tags
        array $more = []
    ) : void
    {
        $this->throughRelationship->joinSelect(
            $select,
            $join,
            $nativeAlias,
            $this->throughName,
            // no $more here
        );

        parent::joinSelect(
            $select,
            $join,
            $this->throughName,
            $foreignAlias,
            $more
        );
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void
    {
        if (empty($nativeRecords)) {
            return;
        }

        $throughRecords = $this->getThroughRecords($nativeRecords);
        $foreignRecords = $this->fetchForeignRecords($throughRecords, $custom);
        foreach ($nativeRecords as $nativeRecord) {
            $matches = $this->getMatches($nativeRecord, $foreignRecords);
            $nativeRecord->{$this->name} = $this->getForeignMapper()->newRecordSet($matches);
        }
    }

    protected function getThroughRecords(array $nativeRecords) : array
    {
        // this hackish. the "through" relation should be loaded for everything,
        // so if even one is loaded, all the others ought to have been too.
        $firstNative = $nativeRecords[0];
        if (! isset($firstNative->{$this->throughName})) {
            $this->throughRelationship->stitchIntoRecords($nativeRecords);
        }

        $throughRecords = [];
        foreach ($nativeRecords as $nativeRecord) {
            foreach ($nativeRecord->{$this->throughName} as $throughRecord) {
                $throughRecords[] = $throughRecord;
            }
        }

        return $throughRecords;
    }

    protected function getMatches(
        Record $nativeRecord,
        array $foreignRecords
    ) : array
    {
        $matches = [];

        // loop through the foreigns and append to matches in the order they are
        // already in; this honors the many-to-many "ORDER" clause, if present.
        foreach ($foreignRecords as $foreignRecord) {
            foreach ($nativeRecord->{$this->throughName} as $throughRecord) {
                if ($this->recordsMatch($throughRecord, $foreignRecord)) {
                    $matches[] = $foreignRecord;
                }
            }
        }
        return $matches;
    }

    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        $foreignRecordSet = $nativeRecord->{$this->name};
        if (! $foreignRecordSet instanceof RecordSet) {
            return;
        }

        $foreignMapper = $this->getForeignMapper();

        /** @var Record $foreignRecord */
        foreach ($foreignRecordSet as $foreignRecord) {
            $foreignMapper->persist($foreignRecord, $tracker);
        }

        // now manage the through records. it's possible this is a new
        // native record with foreign records but no through records, so
        // make sure there is a through related in place.
        if (! $nativeRecord->{$this->throughName} instanceof RecordSet) {
            $nativeRecord->{$this->throughName} = clone $this->throughRecordSet;
        }

        // all the throughs for all foreigns
        $throughRecordSet = $nativeRecord->{$this->throughName};
        $throughRecords = $throughRecordSet->getRecords();

        foreach ($throughRecords as $throughRecord) {
            // set for deletion, unless it matches
            $throughRecord->setDelete(true);
        }

        // find foreigns with a matching through
        /** @var Record $foreignRecord */
        foreach ($foreignRecordSet as $foreignRecord) {

            // does the foreign match any through?
            $matched = false;

            /** @var Record $throughRecord */
            foreach ($throughRecords as $i => $throughRecord) {

                // does this through match the foreign?
                if ($this->recordsMatch($throughRecord, $foreignRecord)) {
                    // matched
                    $matched = true;
                    // unset for deletion
                    $throughRecord->setDelete(false);
                    // no need to match it against another foreign
                    unset($throughRecords[$i]);
                    // no need to keep looping
                    break;
                }
            }

            if (! $matched) {
                // not matched, it's a newly-attached foreign record
                $throughRecordSet->appendNew([
                    $this->throughNativeRelatedName => $nativeRecord,
                    $this->throughForeignRelatedName => $foreignRecord,
                ]);
            }
        }
    }
}
