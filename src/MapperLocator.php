<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Mapper;

use Atlas\Mapper\Exception;

class MapperLocator
{
    protected $factories = [];

    protected $instances = [];

    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    public function has(string $mapperClass) : bool
    {
        return isset($this->factories[$mapperClass]);
    }

    public function get(string $mapperClass) : Mapper
    {
        if (! $this->has($mapperClass)) {
            throw Exception::mapperNotFound($mapperClass);
        }

        if (! isset($this->instances[$mapperClass])) {
            $this->instances[$mapperClass] = call_user_func($this->factories[$mapperClass]);
        }

        return $this->instances[$mapperClass];
    }
}
