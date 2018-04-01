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

class Related
{
    private $fields = [];

    public function __construct(array $fields = [])
    {
        foreach ($fields as $name => $value) {
            $this->modify($name, $value);
        }
    }

    public function __get(string $name)
    {
        $this->assertHas($name);
        return $this->fields[$name];
    }

    public function __set(string $name, $value) : void
    {
        $this->assertHas($name);
        $this->modify($name, $value);
    }

    public function __isset(string $name) : bool
    {
        $this->assertHas($name);
        return isset($this->fields[$name]);
    }

    public function __unset(string $name) : void
    {
        $this->assertHas($name);
        $this->fields[$name] = null;
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function set(array $namesValues = []) : void
    {
        foreach ($namesValues as $name => $value) {
            if ($this->has($name)) {
                $this->modify($name, $value);
            }
        }
    }

    public function has($name) : bool
    {
        return array_key_exists($name, $this->fields);
    }

    public function getArrayCopy() : array
    {
        $array = [];
        foreach ($this->fields as $name => $foreign) {
            $array[$name] = $foreign;
            if ($foreign) {
                $array[$name] = $foreign->getArrayCopy();
            }
        }
        return $array;
    }

    protected function modify(string $name, $value) : void
    {
        $valid = $value === null
              || $value === false
              || $value instanceof Record
              || $value instanceof RecordSet;

        if (! $valid) {
            $expect = 'null, false, Record, or RecordSet';
            throw Exception::invalidType($expect, $value);
        }

        $this->fields[$name] = $value;
    }

    protected function assertHas($name) : void
    {
        if (! $this->has($name)) {
            throw Exception::propertyDoesNotExist($this, $name);
        }
    }
}
