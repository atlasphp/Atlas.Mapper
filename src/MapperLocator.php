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

class MapperLocator
{
    protected $tableLocator;

    protected $mappers = [];

    protected $factory;

    public static function new(...$args) : MapperLocator
    {
        return new MapperLocator(
            new TableLocator(
                ConnectionLocator::new(...$args),
                new MapperQueryFactory()
            )
        );
    }

    public function __construct(
        TableLocator $tableLocator,
        callable $factory = null
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

        if (! isset($this->mappers[$mapperClass])) {
            $this->mappers[$mapperClass] = $this->newMapper($mapperClass);
        }

        return $this->mappers[$mapperClass];
    }

    public function getTableLocator() : TableLocator
    {
        return $this->tableLocator;
    }

    protected function newMapper($mapperClass) : Mapper
    {
        $table = "{$mapperClass}Table";
        $relationships = "{$mapperClass}Relationships";
        $events = "{$mapperClass}Events";
        return new $mapperClass(
            $this->tableLocator->get($table),
            new $relationships($this, $mapperClass),
            ($this->factory)($events)
        );
    }
}
