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

use Atlas\Mapper\Exception;
use SplObjectStorage;

abstract class Related
{
    public function __construct(array $fields = [])
    {
        $this->set($fields);
    }

    public function __get(string $field) : mixed
    {
        $this->assertHas($field);
        return $this->$field;
    }

    public function __set(string $field, mixed $value) : void
    {
        $this->assertHas($field);
        $this->$field = $value;
    }

    public function __isset(string $field) : bool
    {
        $this->assertHas($field);
        return isset($this->$field); // ! instanceof NotLoaded?
    }

    public function __unset(string $field) : void
    {
        $this->assertHas($field);
        $this->$field = NotLoaded::getFlyweight();
    }

    public function isLoaded(string $field) : bool
    {
        $this->assertHas($field);
        return ! $this->$field instanceof NotLoaded;
    }

    public function getFields() : array
    {
        return get_object_vars($this);
    }

    public function set(array $fields) : void
    {
        foreach ($fields as $field => $value) {
            if ($this->has($field)) {
                $this->$field = $value;
            }
        }
    }

    public function has(string $field) : bool
    {
        return property_exists($this, $field);
    }

    public function getArrayCopy(SplObjectStorage $tracker = null) : array
    {
        if ($tracker === null) {
            $tracker = new SplObjectStorage();
        }

        if (! $tracker->contains($this)) {
            $tracker[$this] = [];
            $array = [];

            foreach ($this->getFields() as $field => $value) {
                if ($value instanceof NotLoaded) {
                    continue;
                }

                $array[$field] = $value;

                if ($value) {
                    $array[$field] = $value->getArrayCopy($tracker);
                }
            }

            $tracker[$this] = $array;
        }

        return $tracker[$this];
    }

    protected function assertHas(string $field) : void
    {
        if (! $this->has($field)) {
            throw Exception::propertyDoesNotExist(static::CLASS, $field);
        }
    }
}
