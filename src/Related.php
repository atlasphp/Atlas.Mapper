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
use Atlas\Mapper\Relationship\NotLoaded;
use SplObjectStorage;

class Related
{
    private array $__LOADED = [];

    public function __construct(array $fields = [])
    {
        $loaded = get_class_vars(get_class($this));
        unset($loaded['__LOADED']);

        foreach ($loaded as $field => $value) {
            $this->__LOADED[$field] = false;
        }

        foreach ($fields as $field => $value) {
            $this->__set($field, $value);
        }
    }

    public function __get(string $field) : mixed
    {
        $this->assertHas($field);

        return $this->__LOADED[$field]
            ? $this->$field
            : NotLoaded::getInstance();
    }

    public function __set(string $field, mixed $value) : void
    {
        $this->assertHas($field);
        $this->$field = $value;
        $this->__LOADED[$field] = true;
    }

    public function __isset(string $field) : bool
    {
        $this->assertHas($field);

        return $this->__LOADED[$field]
            ? isset($this->$field)
            : false;
    }

    public function __unset(string $field) : void
    {
        $this->assertHas($field);
        unset($this->$field);
        $this->__LOADED[$field] = false;
    }

    public function getFields() : array
    {
        $fields = [];

        foreach ($this->__LOADED as $field => $flag) {
            $fields[$field] = $this->__get($field);
        }

        return $fields;
    }

    public function set(array $fieldsValues = []) : void
    {
        foreach ($fieldsValues as $field => $value) {
            if ($this->has($field)) {
                $this->__set($field, $value);
            }
        }
    }

    public function has(string $field) : bool
    {
        return isset($this->__LOADED[$field]);
    }

    public function getArrayCopy(SplObjectStorage $tracker = null) : array
    {
        if ($tracker === null) {
            $tracker = new SplObjectStorage();
        }

        if (! $tracker->contains($this)) {
            $tracker[$this] = [];
            $array = [];
            foreach ($this->__LOADED as $field => $flag) {
                $value = $this->__get($field);
                if ($value instanceof Record || $value instanceof RecordSet) {
                    $value = $value->getArrayCopy($tracker);
                }

                $array[$field] = $value;
            }
            $tracker[$this] = $array;
        }

        /** @var array */
        return $tracker[$this];
    }

    protected function assertHas(string $field) : void
    {
        if (! $this->has($field)) {
            throw Exception::propertyDoesNotExist(static::CLASS, $field);
        }
    }
}
