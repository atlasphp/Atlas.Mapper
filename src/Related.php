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

class Related
{
    private $fields = [];

    public function __construct(array $fields = [])
    {
        foreach ($fields as $field => $value) {
            $this->modify($field, $value);
        }
    }

    public function __get(string $field)
    {
        $this->assertHas($field);
        return $this->fields[$field];
    }

    public function __set(string $field, $value) : void
    {
        $this->assertHas($field);
        $this->modify($field, $value);
    }

    public function __isset(string $field) : bool
    {
        $this->assertHas($field);
        return isset($this->fields[$field]);
    }

    public function __unset(string $field) : void
    {
        $this->assertHas($field);
        $this->fields[$field] = null;
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function set(array $fieldsValues = []) : void
    {
        foreach ($fieldsValues as $field => $value) {
            if ($this->has($field)) {
                $this->modify($field, $value);
            }
        }
    }

    public function has(string $field) : bool
    {
        return array_key_exists($field, $this->fields);
    }

    public function getArrayCopy(SplObjectStorage $tracker = null) : array
    {
        if ($tracker === null) {
            $tracker = new SplObjectStorage();
        }

        if (! $tracker->contains($this)) {
            $tracker[$this] = [];
            $array = [];
            foreach ($this->fields as $field => $foreign) {
                $array[$field] = $foreign;
                if ($foreign) {
                    $array[$field] = $foreign->getArrayCopy($tracker);
                }
            }
            $tracker[$this] = $array;
        }

        return $tracker[$this];
    }

    protected function modify(string $field, $value) : void
    {
        $valid = $value === null
            || $value === false
            || $value instanceof Record
            || $value instanceof RecordSet;

        if (! $valid) {
            $expect = 'null, false, Record, or RecordSet';
            throw Exception::invalidType($expect, $value);
        }

        $this->fields[$field] = $value;
    }

    protected function assertHas(string $field) : void
    {
        if (! $this->has($field)) {
            throw Exception::propertyDoesNotExist(static::CLASS, $field);
        }
    }
}
