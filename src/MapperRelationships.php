<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper;

use Atlas\Mapper\Exception;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Record;
use Atlas\Mapper\Relationship\ManyToMany;
use Atlas\Mapper\Relationship\ManyToOne;
use Atlas\Mapper\Relationship\ManyToOneVariant;
use Atlas\Mapper\Relationship\OneToMany;
use Atlas\Mapper\Relationship\OneToOne;
use Atlas\Mapper\Relationship\OneToOneBidi;
use Atlas\Mapper\Relationship\Relationship;
use SplObjectStorage;

abstract class MapperRelationships
{
    protected $mapperLocator;

    protected $nativeMapperClass;

    protected $nativeTableColumns;

    protected $relationships = [];

    protected $fields = [];

    protected $persistBeforeNative = [];

    protected $persistAfterNative = [];

    protected $prototypeRelated = null;

    public function __construct(
        MapperLocator $mapperLocator,
        string $nativeMapperClass
    ) {
        $this->mapperLocator = $mapperLocator;
        $this->nativeMapperClass = $nativeMapperClass;

        $nativeTableClass = $this->nativeMapperClass . 'Table';
        $this->nativeTableColumns = $nativeTableClass::COLUMN_NAMES;

        $this->define();
    }

    abstract protected function define();

    protected function oneToOne(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : OneToOne
    {
        return $this->set(
            $relatedName,
            OneToOne::CLASS,
            $foreignMapperClass,
            'persistAfterNative',
            $on
        );
    }

    protected function oneToOneBidi(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : OneToOneBidi
    {
        return $this->set(
            $relatedName,
            OneToOneBidi::CLASS,
            $foreignMapperClass,
            'persistAfterNative',
            $on
        );
    }

    protected function oneToMany(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : OneToMany
    {
        return $this->set(
            $relatedName,
            OneToMany::CLASS,
            $foreignMapperClass,
            'persistAfterNative',
            $on
        );
    }

    protected function manyToOne(
        string $relatedName,
        string $foreignMapperClass,
        array $on = []
    ) : ManyToOne
    {
        return $this->set(
            $relatedName,
            ManyToOne::CLASS,
            $foreignMapperClass,
            'persistBeforeNative',
            $on
        );
    }

    protected function manyToOneVariant(
        string $relatedName,
        string $referenceCol
    ) : ManyToOneVariant
    {
        return $this->set(
            $relatedName,
            ManyToOneVariant::CLASS,
            $referenceCol,
            'persistBeforeNative'
        );
    }

    protected function manyToMany(
        string $relatedName,
        string $foreignMapperClass,
        string $throughRelatedName,
        array $on = []
    ) {
        return $this->set(
            $relatedName,
            ManyToMany::CLASS,
            $foreignMapperClass,
            'persistBeforeNative',
            $on,
            $throughRelatedName
        );
    }

    public function get(string $relatedName) : Relationship
    {
        return $this->relationships[$relatedName];
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        array $with = []
    ) : void
    {
        foreach ($this->fixWith($with) as $relatedName => $custom) {
            if (! isset($this->relationships[$relatedName])) {
                throw Exception::relationshipDoesNotExist($relatedName);
            }
            $this->relationships[$relatedName]->stitchIntoRecords(
                $nativeRecords,
                $custom
            );
        }
    }

    protected function set(
        string $relatedName,
        string $relationshipClass,
        string $foreignSpec,
        string $persistencePriority,
        array $on = [],
        string $throughRelatedName = null
    ) : Relationship
    {
        $this->assertRelatedName($relatedName);

        $this->fields[$relatedName] = null;

        $args = [
            $relatedName,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignSpec
        ];

        if ($throughRelatedName !== null) {
            if (! isset($this->relationships[$throughRelatedName])) {
                throw Exception::relationshipDoesNotExist($throughRelatedName);
            }
            $args[] = $this->relationships[$throughRelatedName];
        }

        if (! empty($on)) {
            $args[] = $on;
        }

        $relationship = new $relationshipClass(...$args);
        $this->{$persistencePriority}[] = $relationship;
        $this->relationships[$relatedName] = $relationship;
        return $relationship;
    }

    protected function fixWith(array $spec) : array
    {
        $with = [];
        foreach ($spec as $key => $val) {
            if (is_int($key)) {
                $with[$val] = null;
            } elseif (is_array($val) && ! is_callable($val)) {
                $with[$key] = function ($select) use ($val) {
                    $select->with($val);
                };
            } else {
                $with[$key] = $val;
            }
        }
        return $with;
    }

    public function fixNativeRecord(Record $nativeRecord) : void
    {
        foreach ($this->relationships as $relationship) {
            $relationship->fixNativeRecord($nativeRecord);
        }
    }

    public function fixForeignRecord(Record $nativeRecord) : void
    {
        foreach ($this->relationships as $relationship) {
            $relationship->fixForeignRecord($nativeRecord);
        }
    }

    public function persistBeforeNative(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        foreach ($this->persistBeforeNative as $relationship) {
            $relationship->persistForeign($nativeRecord, $tracker);
        }
    }

    public function persistAfterNative(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        foreach ($this->persistAfterNative as $relationship) {
            $relationship->persistForeign($nativeRecord, $tracker);
        }
    }

    public function newRelated(array $fields = []) : Related
    {
        if ($this->prototypeRelated === null) {
            $this->prototypeRelated = new Related($this->fields);
        }

        $newRelated = clone $this->prototypeRelated;
        $newRelated->set($fields);
        return $newRelated;
    }

    public function joinSelect(
        MapperSelect $select,
        string $nativeAlias,
        string $relatedName,
        callable $sub = null
    ) : void
    {
        // clean up the specification
        $relatedName = trim($relatedName);

        // extract the foreign alias
        $foreignAlias = '';
        $pos = stripos($relatedName, ' AS ');
        if ($pos !== false) {
            $foreignAlias = trim(substr($relatedName, $pos + 4));
            $relatedName = trim(substr($relatedName, 0, $pos));
        }

        // extract the join type
        $join = 'JOIN';
        $pos = strpos($relatedName, ' ');
        if ($pos !== false) {
            $join = trim(substr($relatedName, 0, $pos));
            $relatedName = trim(substr($relatedName, $pos));
        }

        // fix the foreign alias
        if ($foreignAlias == '') {
            $foreignAlias = $relatedName;
        }

        // make the join
        $this->get($relatedName)->joinSelect(
            $select,
            $join,
            $nativeAlias,
            $foreignAlias,
            $sub
        );
    }

    protected function assertRelatedName(string $relatedName) : void
    {
        if (isset($this->relationships[$relatedName])) {
            throw Exception::relatedNameConflict($relatedName, 'relationship');
        }

        if (in_array($relatedName, $this->nativeTableColumns)) {
            throw Exception::relatedNameConflict($relatedName, 'column');
        }
    }
}
