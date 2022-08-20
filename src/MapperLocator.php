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

use Atlas\Mapper\Relationship\RelationshipLocator;
use Atlas\Pdo\ConnectionLocator;
use Atlas\Table\TableLocator;

class MapperLocator
{
    protected $instances = [];

    protected $factory;

    public static function new(mixed ...$args) : MapperLocator
    {
        return new MapperLocator(
            new TableLocator(
                ConnectionLocator::new(...$args)
            )
        );
    }

    public function __construct(
        protected TableLocator $tableLocator,
        callable $factory = null
    ) {
        if ($factory === null) {
            $factory = function ($class) {
                return new $class();
            };
        }

        $this->factory = $factory;
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

    protected function newMapper($mapperClass) : Mapper
    {
        $table = "{$mapperClass}Table";
        $events = "{$mapperClass}Events";

        return new $mapperClass(
            $this->tableLocator->get($table),
            new RelationshipLocator(
                $this,
                $mapperClass,
            ),
            ($this->factory)($events)
        );
    }
}
