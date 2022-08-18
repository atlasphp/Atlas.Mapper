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
use Atlas\Mapper\Define\RefinementAttribute;
use Atlas\Mapper\Define\RelationshipAttribute;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Related;
use Atlas\Mapper\Relationship\Relationship;
use IteratorAggregate;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

class RelationshipLocator implements IteratorAggregate
{
    protected string $nativeTableClass;

    protected array $instances = [];

    protected $persist = [];

    public function __construct(
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass
    ) {
        $this->mapperLocator = $mapperLocator;
        $this->nativeMapperClass = $nativeMapperClass;
        $this->nativeTableClass = $this->nativeMapperClass . 'Table';

        $rc = new ReflectionClass($this->nativeMapperClass . 'Related');
        $properties = $rc->getProperties();

        foreach ($properties as $property) {
            $this->defineFromProperty($property);
        }
    }

    protected function defineFromProperty(ReflectionProperty $property)
    {
        $attributes = $property->getAttributes();

        while ($attribute = array_shift($attributes)) {
            if (is_subclass_of(
                $attribute->getName(),
                RelationshipAttribute::CLASS
            )) {
                $this->defineFromPropertyAttribute(
                    $property,
                    $attribute->newInstance(),
                    $attributes
                );
            }
        }
    }

    protected function defineFromPropertyAttribute(
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
        array $otherAttrs
    ) : void
    {
        $relationshipClass = $attribute->class;

        $name = $property->getName();

        if (in_array($name, $this->nativeTableClass::COLUMN_NAMES)) {
            throw Exception::nameConflict($name, 'column');
        }

        $type = $property->getType();
        $foreignMapperClass = $type instanceof ReflectionNamedType
            ? Mapper::classFrom($type->getName())
            : 'UNKNOWN';

        $relationship = new $relationshipClass(
            $name,
            $attribute,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
            $this
        );

        $this->instances[$name] = $relationship;
        $this->persist[$relationship::PERSISTENCE_PRIORITY][] = $relationship;

        foreach ($otherAttrs as $otherAttr) {
            if (is_subclass_of(
                $otherAttr->getName(),
                RefinementAttribute::CLASS
            )) {
                $otherAttr = $otherAttr->newInstance();
                $otherAttr($relationship);
            }
        }
    }

    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->instances);
    }

    public function has(string $name) : bool
    {
        return isset($this->instances[$name]);
    }

    public function get(string $name) : Relationship
    {
        return $this->instances[$name];
    }

    public function getPersistBeforeNative() : array
    {
        return $this->persist[Relationship::BEFORE_NATIVE] ?? [];
    }

    public function getPersistAfterNative() : array
    {
        return $this->persist[Relationship::AFTER_NATIVE] ?? [];
    }

    public function getNames()
    {
        return array_keys($this->instances);
    }

    public function listRelatedSpec(string $relatedSpec) : array
    {
        $relatedSpec = trim($relatedSpec);

        // extract the foreign alias
        $foreignAlias = '';
        $pos = stripos($relatedSpec, ' AS ');
        if ($pos !== false) {
            $foreignAlias = trim(substr($relatedSpec, $pos + 4));
            $relatedSpec = trim(substr($relatedSpec, 0, $pos));
        }

        // extract the join type
        $join = 'JOIN';
        $pos = strpos($relatedSpec, ' ');
        if ($pos !== false) {
            $join = trim(substr($relatedSpec, 0, $pos));
            $relatedSpec = trim(substr($relatedSpec, $pos));
        }

        // fix the foreign alias
        if ($foreignAlias === '') {
            $foreignAlias = $relatedSpec;
        }

        return [
            $relatedSpec,
            $join,
            $foreignAlias,
        ];
    }
}
