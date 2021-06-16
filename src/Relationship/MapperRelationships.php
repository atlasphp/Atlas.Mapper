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

use ArrayIterator;
use Atlas\Mapper\Related\RelationshipAttribute;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Record;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Relationship\Relationship;
use IteratorAggregate;
use ReflectionClass;
use ReflectionProperty;
use SplObjectStorage;

class MapperRelationships implements IteratorAggregate
{
    protected array $nativeTableColumns;

    protected array $relationships = [];

    protected array $persistOrder = [
        'beforeNative' => [],
        'afterNative' => [],
    ];

    public function getIterator()
    {
        return new ArrayIterator($this->relationships);
    }

    public function __construct(
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass,
        protected string $nativeRelatedClass
    ) {
        $nativeTableClass = $this->nativeMapperClass . 'Table';
        $this->nativeTableColumns = $nativeTableClass::COLUMN_NAMES;
        $refl = new ReflectionClass($this->nativeRelatedClass);
        $props = $refl->getProperties();

        foreach ($props as $prop) {
            $this->handleProperty($prop);
        }
    }

    protected function handleProperty(ReflectionProperty $prop) : void
    {
        $name = $prop->getName();

        if (in_array($name, $this->nativeTableColumns)) {
            throw Exception::relatedNameConflict($name, 'column');
        }

        $attrs = $prop->getAttributes();

        foreach ($attrs as $attr) {
            /** @var RelationshipAttribute */
            $attr = $attr->newInstance();
            $this->handleAttribute($prop, $attr);
        }
    }

    protected function handleAttribute(
        ReflectionProperty $prop,
        RelationshipAttribute $attr
    ) : void
    {
        $result = $attr(
            $prop->getName(),
            $this->mapperLocator,
            $this->nativeMapperClass,
            $prop,
            $this->relationships
        );

        if (! $result instanceof Relationship) {
            return;
        }

        $name = $prop->getName();
        $this->persistOrder[$result->getPersistOrder()][] = $name;
        $this->relationships[$name] = $result;
    }

    public function get(string $name) : Relationship
    {
        return $this->relationships[$name];
    }

    public function has(string $name) : bool
    {
        return isset($this->relationships[$name]);
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        array $loadRelated = []
    ) : void
    {
        foreach ($this->fixLoadRelated($loadRelated) as $name => $custom) {
            if (! isset($this->relationships[$name])) {
                throw Exception::relationshipDoesNotExist($name);
            }
            $this->relationships[$name]->stitchIntoRecords(
                $nativeRecords,
                $custom
            );
        }
    }

    protected function fixLoadRelated(array $spec) : array
    {
        $loadRelated = [];
        foreach ($spec as $key => $val) {
            if (is_int($key)) {
                $loadRelated[$val] = null;
            } elseif (is_array($val) && ! is_callable($val)) {
                $loadRelated[$key] = function ($select) use ($val) {
                    $select->loadRelated($val);
                };
            } else {
                $loadRelated[$key] = $val;
            }
        }
        return $loadRelated;
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
        foreach ($this->persistOrder['beforeNative'] as $name) {
            $this->relationships[$name]->persistForeign($nativeRecord, $tracker);
        }
    }

    public function persistAfterNative(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        foreach ($this->persistOrder['afterNative'] as $name) {
            $this->relationships[$name]->persistForeign($nativeRecord, $tracker);
        }
    }

    public function joinSelect(
        MapperSelect $select,
        string $nativeAlias,
        string $name,
        callable $sub = null
    ) : void
    {
        // clean up the specification
        $name = trim($name);

        // extract the foreign alias
        $foreignAlias = '';
        $pos = stripos($name, ' AS ');
        if ($pos !== false) {
            $foreignAlias = trim(substr($name, $pos + 4));
            $name = trim(substr($name, 0, $pos));
        }

        // extract the join type
        $join = 'JOIN';
        $pos = strpos($name, ' ');
        if ($pos !== false) {
            $join = trim(substr($name, 0, $pos));
            $name = trim(substr($name, $pos));
        }

        // fix the foreign alias
        if ($foreignAlias == '') {
            $foreignAlias = $name;
        }

        // make the join
        $this->get($name)->joinSelect(
            $select,
            $join,
            $nativeAlias,
            $foreignAlias,
            $sub
        );
    }
}
