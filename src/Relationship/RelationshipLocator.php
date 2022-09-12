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
use ReflectionType;
use ReflectionUnionType;

class RelationshipLocator implements IteratorAggregate
{
    /** @var Relationship[] */
    protected array $instances = [];

    protected array $persist = [
        Relationship::BEFORE_NATIVE => [],
        Relationship::AFTER_NATIVE => [],
    ];

    public function __construct(
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass,
        protected string $nativeTableClass,
        protected string $nativeRelatedClass,
    ) {
        $rclass = new ReflectionClass($this->nativeRelatedClass);

        foreach ($rclass->getProperties() as $property) {
            $this->defineFromRelatedProperty($property);
        }
    }

    protected function defineFromRelatedProperty(
        ReflectionProperty $property
    ) : void
    {
        $attributes = $property->getAttributes();

        while ($attribute = array_shift($attributes)) {
            if (is_subclass_of(
                $attribute->getName(),
                RelationshipAttribute::CLASS
            )) {
                /** @var RelationshipAttribute */
                $attribute = $attribute->newInstance();
                $this->defineFromRelatedPropertyAttribute(
                    $property,
                    $attribute,
                    $attributes
                );
                return;
            }
        }
    }

    protected function defineFromRelatedPropertyAttribute(
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
        array $otherAttrs
    ) : void
    {
        $relationshipClass = $attribute->class;

        $name = $property->getName();

        if (in_array($name, $this->nativeTableClass::COLUMN_NAMES)) {
            throw new Exception\RelatedNameConflict(
                $this->nativeMapperClass,
                $name,
            );
        }

        $type = $property->getType();
        $foreignMapperClass = $this->resolveMapperClass($type);

        /** @var Relationship */
        $relationship = new $relationshipClass(
            $name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
            $attribute,
            $this
        );

        $this->instances[$name] = $relationship;
        $priority = $relationship->getPersistencePriority();
        $this->persist[$priority][] = $relationship;

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

    public function getNativeRelatedClass()
    {
        return $this->nativeRelatedClass;
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

    public function getNames() : array
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

    /**
     * Project\DataSource\Foo\BarRecord => Project\DataSource\Foo\Foo
     */
    public function resolveMapperClass(ReflectionType|string|null $spec) : string
    {
        if ($spec instanceof ReflectionUnionType) {
            return 'UNKNOWN';
        }

        if ($spec instanceof ReflectionNamedType) {
            $spec = $spec->isBuiltin() ? '' : $spec->getName();
        }

        $spec = trim((string) $spec);

        if ($spec === '') {
            return 'UNKNOWN';
        }

        $parts = explode('\\', $spec);
        array_pop($parts);
        $mapperClass = implode('\\', $parts) . '\\' . end($parts);

        if (! class_exists($mapperClass)) {
            throw new Exception\UnresolvableMapperClass($spec, $mapperClass);
        }

        return $mapperClass;
    }
}
