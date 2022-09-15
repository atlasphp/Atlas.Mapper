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
        $relationshipFactory = new RelationshipFactory(
            $mapperLocator,
            $this,
            $nativeMapperClass,
            $nativeTableClass
        );

        $rclass = new ReflectionClass($this->nativeRelatedClass);
        $properties = $rclass->getProperties();
        $yield = $relationshipFactory->yieldFromProperties($properties);

        foreach ($yield as $relationship) {
            $name = $relationship->getName();
            $this->instances[$name] = $relationship;
            $priority = $relationship->getPersistencePriority();
            $this->persist[$priority][] = $relationship;
        }
    }

    public function getNativeRelatedClass() : string
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
}
