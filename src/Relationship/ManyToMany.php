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

use Atlas\Mapper\Define\RelationshipAttribute;
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
    public const PERSISTENCE_PRIORITY = self::BEFORE_NATIVE;

    protected $throughName;

    protected $throughRelationship;

    protected $throughNativeRelatedName;

    protected $throughForeignRelatedName;

    protected $throughRecordSet;

    public function __construct(
        string $name,
        RelationshipAttribute $attribute,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        string $foreignMapperClass,
        RelationshipLocator $relationshipLocator
    ) {
        $this->throughName = $attribute->through;

        if (! $relationshipLocator->has($this->throughName)) {
            throw Exception::relationshipDoesNotExist($this->throughName);
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

            if (
                $this->throughNativeRelatedName === null
                && $relationship->foreignMapperClass === $nativeMapperClass
            ) {
                $this->throughNativeRelatedName = $relatedName;
            }

            if (
                $this->throughForeignRelatedName === null
                && $relationship->foreignMapperClass === $foreignMapperClass
            ) {
                $this->throughForeignRelatedName = $relatedName;
                if (empty($attribute->on)) {
                    $attribute->on = $relationship->on;
                }
            }
        }

        if (! $this->throughNativeRelatedName) {
            throw Exception::couldNotFindThroughRelationship(
                'native',
                $this->throughName,
                $name,
                $nativeMapperClass
            );
        }

        if (! $this->throughForeignRelatedName) {
            throw Exception::couldNotFindThroughRelationship(
                'foreign',
                $this->throughName,
                $name,
                $nativeMapperClass
            );
        }

        parent::__construct(
            $name,
            $attribute,
            $mapperLocator,
            $nativeMapperClass,
            $foreignMapperClass,
            $relationshipLocator
        );
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
            $this->stitchIntoRecord($nativeRecord, $foreignRecords);
        }
    }

    protected function stitchIntoRecord(
        Record $nativeRecord,
        array &$foreignRecords
    ) : void
    {
        $matches = $this->getMatches($nativeRecord, $foreignRecords);
        $nativeRecord->{$this->name} = $this->getForeignMapper()->newRecordSet($matches);
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
        foreach ($foreignRecordSet as $foreignRecord) {

            // does the foreign match any through?
            $matched = false;
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
