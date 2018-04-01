<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Mapper;

use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperRelationships;

class Container extends \Atlas\Table\Container
{
    protected $mappers;

    protected $tableLocator;

    protected $mapperLocator;

    public function newMapperLocator() : MapperLocator
    {
        $this->tableLocator = $this->newTableLocator();
        $this->mapperLocator = new MapperLocator($this->mappers);
        return $this->mapperLocator;
    }

    public function setMappers(array $mapperClasses) : void
    {
        foreach ($mapperClasses as $mapperClass) {
            $this->setMapper($mapperClass);
        }
    }

    public function setMapper(string $mapperClass) : void
    {
        if (! class_exists($mapperClass)) {
            throw Exception::classDoesNotExist($mapperClass);
        }

        $tableClass = substr($mapperClass, 0, -6) . 'Table';
        $this->setTable($tableClass);

        $this->mappers[$mapperClass] = function () use ($mapperClass, $tableClass) {
            $eventsClass = $mapperClass . 'Events';
            $relationshipsClass = substr($mapperClass, 0, -6) . 'MapperRelationships';
            return new $mapperClass(
                $this->tableLocator->get($tableClass),
                new $relationshipsClass($this->mapperLocator, $mapperClass),
                ($this->factory)($eventsClass)
            );
        };
    }
}
