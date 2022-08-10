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

abstract class MapperRelationships
{
    protected $__mapperLocator;

    protected $__nativeMapperClass;

    protected $__nativeTableColumns;

    protected $__relationships = [];

    protected $__fields = [];

    protected $__persistBeforeNative = [];

    protected $__persistAfterNative = [];

    protected $__prototypeRelated = null;

    public function __construct(
        MapperLocator $mapperLocator,
        string $nativeMapperClass
    ) {
        $this->__mapperLocator = $mapperLocator;
        $this->__nativeMapperClass = $nativeMapperClass;

        $nativeTableClass = $this->__nativeMapperClass . 'Table';
        $this->__nativeTableColumns = $nativeTableClass::COLUMN_NAMES;

        $this->define();
    }

    protected function define()
    {
        $rc = new ReflectionClass($this);
        $props = $rc->getProperties();

        foreach ($props as $prop) {
            $this->defineFromProperty($prop);
        }
    }

    /**
     * you have to be REALLY ATTENTIVE to the typehints. if you typo the hint,
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
                $this->defineFromRelationshipAttribute($prop, $attr->newInstance(), $attrs);
            }
        }
    }

    /**
     * @todo this will not handle union types AT ALL
     *
     * @todo UNKNOWN is awful
     */
    protected function defineFromRelationshipAttribute(
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
        return $this->__relationships[$relatedName];
    }

    public function getFields() : array
    {
        return $this->__fields;
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        array $loadRelated = []
    ) : void
    {
        foreach ($this->fixLoadRelated($loadRelated) as $relatedName => $custom) {
            if (! isset($this->__relationships[$relatedName])) {
                throw Exception::relationshipDoesNotExist($relatedName);
            }
            $this->__relationships[$relatedName]->stitchIntoRecords(
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

        $this->__fields[$relatedName] = null;

        $args = [
            $relatedName,
            $this->__mapperLocator,
            $this->__nativeMapperClass,
            $foreignSpec
        ];

        if ($throughRelatedName !== null) {
            if (! isset($this->__relationships[$throughRelatedName])) {
                throw Exception::relationshipDoesNotExist($throughRelatedName);
            }
            $args[] = $this->__relationships[$throughRelatedName];
        }

        if (! empty($on)) {
            $args[] = $on;
        }

        $relationship = new $relationshipClass(...$args);
        $persistencePriority = '__' . $persistencePriority;
        $this->{$persistencePriority}[] = $relationship;
        $this->__relationships[$relatedName] = $relationship;
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
        foreach ($this->__relationships as $relationship) {
            $relationship->fixNativeRecord($nativeRecord);
        }
    }

    public function fixForeignRecord(Record $nativeRecord) : void
    {
        foreach ($this->__relationships as $relationship) {
            $relationship->fixForeignRecord($nativeRecord);
        }
    }

    public function persistBeforeNative(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        foreach ($this->__persistBeforeNative as $relationship) {
            $relationship->persistForeign($nativeRecord, $tracker);
        }
    }

    public function persistAfterNative(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        foreach ($this->__persistAfterNative as $relationship) {
            $relationship->persistForeign($nativeRecord, $tracker);
        }
    }

    public function newRelated(array $fields = []) : Related
    {
        if ($this->__prototypeRelated === null) {
            $this->__prototypeRelated = new Related($this->__fields);
        }

        $newRelated = clone $this->__prototypeRelated;
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
        if (isset($this->__relationships[$relatedName])) {
            throw Exception::relatedNameConflict($relatedName, 'relationship');
        }

        if (in_array($relatedName, $this->__nativeTableColumns)) {
            throw Exception::relatedNameConflict($relatedName, 'column');
        }
    }
}
