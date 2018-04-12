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

    public function has(string $class) : bool
    {
        return class_exists($class) && is_subclass_of($class, Mapper::CLASS);
    }

    public function get(string $class) : Mapper
    {
        if (! $this->has($class)) {
            throw Exception::mapperNotFound($class);
        }

        if (! isset($this->mappers[$class])) {
            $this->mappers[$class] = $this->newMapper($class);
        }

        return $this->mappers[$class];
    }

    protected function newMapper($class) : Mapper
    {
        $prefix = substr($class, 0, -6);
        $table = "{$prefix}Table";
        $relationships = "{$prefix}MapperRelationships";
        $events = "{$class}Events";
        return new $class(
            $this->tableLocator->get($table),
            new $relationships($this, $class),
            ($this->factory)($events)
        );
    }
}
