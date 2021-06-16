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

use Atlas\Pdo\ConnectionLocator;
use Atlas\Table\TableLocator;
use Atlas\Mapper\Relationship\MapperRelationships;

class MapperLocator
{
    static public function new(mixed ...$args) : MapperLocator
    {
        return new MapperLocator(
            new TableLocator(
                ConnectionLocator::new(...$args)
            )
        );
    }

    protected array $instances = [];

    public function __construct(
        protected TableLocator $tableLocator,
        protected mixed /* callable */ $factory = null
    ) {
        $this->tableLocator = $tableLocator;
        $this->factory = $factory;
        if ($this->factory === null) {
            $this->factory = function ($class) {
                return new $class();
            };
        }
    }

    public function has(string $mapperClass) : bool
    {
        return class_exists($mapperClass) && is_subclass_of($mapperClass, Mapper::CLASS);
    }

    public function get(string $mapperClass) : Mapper
    {
        if (! $this->has($mapperClass)) {
            throw Exception::mapperNotFound($mapperClass);
        }

        if (! isset($this->instances[$mapperClass])) {
            $this->instances[$mapperClass] = $this->newMapper($mapperClass);
        }

        return $this->instances[$mapperClass];
    }

    public function getTableLocator() : TableLocator
    {
        return $this->tableLocator;
    }

    protected function newMapper(string $mapperClass) : Mapper
    {
        $table = "{$mapperClass}Table";
        $events = "{$mapperClass}Events";
        return new $mapperClass(
            $this->tableLocator->get($table),
            new MapperRelationships($this, $mapperClass, $mapperClass . 'Related'),
            ($this->factory)($events)
        );
    }
}
