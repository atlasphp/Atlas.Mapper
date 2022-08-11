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

use Atlas\Mapper\Define\RefinementAttribute;
use Atlas\Mapper\Define\RelationshipAttribute;
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
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use SplObjectStorage;

class MapperRelationships
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

        $relatedClass = $nativeMapperClass . 'Related';
        $this->prototypeRelated = new $relatedClass();

        $rc = new ReflectionClass($this->prototypeRelated);
        $props = $rc->getProperties();

        foreach ($props as $prop) {
            $this->defineFromProperty($prop);
        }
    }

    /**
     * you have to be REALLY ATTENTIVE to the attributes. if you typo the attr,
     * it WILL NOT define the relationship, silently, and then fail when you
     * try to use the related field.
     *
     * @todo better (or any!) error messaging on this
     */
    protected function defineFromProperty(ReflectionProperty $prop)
    {
        $attrs = $prop->getAttributes();

        while ($attr = array_shift($attrs)) {
            if (is_subclass_of($attr->getName(), RelationshipAttribute::CLASS)) {
                $this->defineFromPropertyAttribute($prop, $attr->newInstance(), $attrs);
            }
        }
    }

    /**
     * @todo this will not handle union types AT ALL
     *
     * @todo UNKNOWN is awful
     */
    protected function defineFromPropertyAttribute(
        ReflectionProperty $prop,
        RelationshipAttribute $attr,
        array $otherAttrs
    ) : void
    {
        $relatedName = $prop->getName();

        $type = $prop->getType();
        $foreignMapperClass = $type instanceof ReflectionNamedType
            ? Mapper::classFrom($type->getName())
            : 'UNKNOWN';

        $parts = explode('\\', get_class($attr));
        $method = lcfirst(end($parts));
        $args = $attr->args($foreignMapperClass);
        $relationship = $this->$method($relatedName, ...$args);

        foreach ($otherAttrs as $otherAttr) {
            if (is_subclass_of($otherAttr->getName(), RefinementAttribute::CLASS)) {
                $otherAttr = $otherAttr->newInstance();
                $otherAttr($relationship);
            }
        }
    }

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
        array $loadRelated = []
    ) : void
    {
        foreach ($this->fixLoadRelated($loadRelated) as $relatedName => $custom) {
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
        $persistencePriority = $relationship::PERSISTENCE_PRIORITY;
        $this->{$persistencePriority}[] = $relationship;
        $this->relationships[$relatedName] = $relationship;
        return $relationship;
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
