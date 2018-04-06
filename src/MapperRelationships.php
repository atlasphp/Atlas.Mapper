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
use Atlas\Mapper\Relationship\ManyToOne;
use Atlas\Mapper\Relationship\ManyToOneByReference;
use Atlas\Mapper\Relationship\OneToMany;
use Atlas\Mapper\Relationship\OneToOne;
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

    public function __construct(
        MapperLocator $mapperLocator,
        string $nativeMapperClass
    ) {
        $this->mapperLocator = $mapperLocator;
        $this->nativeMapperClass = $nativeMapperClass;

        $nativeTableClass = substr($this->nativeMapperClass, 0, -6) . 'Table';
        $this->nativeTableColumns = $nativeTableClass::COLUMN_NAMES;

        $this->define();
    }

    abstract protected function define();

    protected function oneToOne(
        string $name,
        string $foreignMapperClass,
        array $on = []
    ) : OneToOne
    {
        return $this->set(
            $name,
            OneToOne::CLASS,
            $foreignMapperClass,
            'persistAfterNative',
            $on
        );
    }

    protected function oneToMany(
        string $name,
        string $foreignMapperClass,
        array $on = []
    ) : OneToMany
    {
        return $this->set(
            $name,
            OneToMany::CLASS,
            $foreignMapperClass,
            'persistAfterNative',
            $on
        );
    }

    protected function manyToOne(
        string $name,
        string $foreignMapperClass,
        array $on = []
    ) : ManyToOne
    {
        return $this->set(
            $name,
            ManyToOne::CLASS,
            $foreignMapperClass,
            'persistBeforeNative',
            $on
        );
    }

    protected function manyToOneByReference(
        string $name,
        string $referenceCol
    ) : ManyToOneByReference
    {
        return $this->set(
            $name,
            ManyToOneByReference::CLASS,
            $referenceCol,
            'persistBeforeNative'
        );

        return $relationship;
    }

    public function get(string $name) : Relationship
    {
        return $this->relationships[$name];
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
        foreach ($this->fixWith($with) as $name => $custom) {
            if (! isset($this->relationships[$name])) {
                throw Exception::relationshipDoesNotExist($name);
            }
            $this->relationships[$name]->stitchIntoRecords(
                $nativeRecords,
                $custom
            );
        }
    }

    protected function set(
        string $name,
        string $relationshipClass,
        string $foreignSpec,
        string $persistencePriority,
        array $on = []
    ) : Relationship
    {
        $this->assertRelatedName($name);

        $this->fields[$name] = null;

        $args = [
            $name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignSpec,
        ];

        if (! empty($on)) {
            $args[] = $on;
        }

        $relationship = new $relationshipClass(...$args);
        $this->{$persistencePriority}[] = $relationship;
        $this->relationships[$name] = $relationship;
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

    public function fixNativeRecordKeys(Record $nativeRecord) : void
    {
        foreach ($this->relationships as $relationship) {
            $relationship->fixNativeRecordKeys($nativeRecord);
        }
    }

    public function fixForeignRecordKeys(Record $nativeRecord) : void
    {
        foreach ($this->relationships as $relationship) {
            $relationship->fixForeignRecordKeys($nativeRecord);
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

    public function newRelated() : Related
    {
        return new Related($this->fields);
    }

    protected function assertRelatedName(string $name) : void
    {
        if (in_array($name, $this->nativeTableColumns)) {
            throw Exception::relatedNameConflict($name);
        }
    }
}
