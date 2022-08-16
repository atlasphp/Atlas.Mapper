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

/**
 * @todo Split into a RelationshipLocator and a separate MapperRelationships handler?
 */
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
        $properties = $rc->getProperties();

        foreach ($properties as $property) {
            $this->defineFromProperty($property);
        }
    }

    protected function defineFromProperty(ReflectionProperty $property)
    {
        $attributes = $property->getAttributes();

        while ($attribute = array_shift($attributes)) {
            if (is_subclass_of($attribute->getName(), RelationshipAttribute::CLASS)) {
                $this->defineFromPropertyAttribute($property, $attribute->newInstance(), $attributes);
            }
        }
    }

    protected function defineFromPropertyAttribute(
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
        array $otherAttrs
    ) : void
    {
        $relatedName = $property->getName();

        $parts = explode('\\', get_class($attribute));
        $method = lcfirst(end($parts));
        $relationship = $this->$method($relatedName, $property, $attribute);

        foreach ($otherAttrs as $otherAttr) {
            if (is_subclass_of($otherAttr->getName(), RefinementAttribute::CLASS)) {
                $otherAttr = $otherAttr->newInstance();
                $otherAttr($relationship);
            }
        }
    }

    protected function oneToOne(
        string $relatedName,
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
    ) : OneToOne
    {
        return $this->set(
            $relatedName,
            OneToOne::CLASS,
            $property,
            $attribute,
        );
    }

    protected function oneToOneBidi(
        string $relatedName,
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
    ) : OneToOneBidi
    {
        return $this->set(
            $relatedName,
            OneToOneBidi::CLASS,
            $property,
            $attribute,
        );
    }

    protected function oneToMany(
        string $relatedName,
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
    ) : OneToMany
    {
        return $this->set(
            $relatedName,
            OneToMany::CLASS,
            $property,
            $attribute,
        );
    }

    protected function manyToOne(
        string $relatedName,
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
    ) : ManyToOne
    {
        return $this->set(
            $relatedName,
            ManyToOne::CLASS,
            $property,
            $attribute,
        );
    }

    protected function manyToOneVariant(
        string $relatedName,
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
    ) : ManyToOneVariant
    {
        return $this->set(
            $relatedName,
            ManyToOneVariant::CLASS,
            $property,
            $attribute,
        );
    }

    protected function manyToMany(
        string $relatedName,
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
    ) {
        return $this->set(
            $relatedName,
            ManyToMany::CLASS,
            $property,
            $attribute,
        );
    }

    public function has(string $relatedName) : bool
    {
        return isset($this->relationships[$relatedName]);
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
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
    ) : Relationship
    {
        $this->assertRelatedName($relatedName);
        $this->fields[$relatedName] = null;

        $type = $property->getType();
        $foreignMapperClass = $type instanceof ReflectionNamedType
            ? Mapper::classFrom($type->getName())
            : 'UNKNOWN';

        $relationship = new $relationshipClass(
            $relatedName,
            $attribute,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
            $this
        );
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
