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
use Atlas\Table\Row;
use JsonSerializable;
use SplObjectStorage;

abstract class Record implements JsonSerializable
{
    private $row;

    private $related;

    public function __construct(Row $row, Related $related)
    {
        $this->row = $row;
        $this->related = $related;
    }

    public function __get(string $field)
    {
        $prop = $this->assertHas($field);
        return $this->$prop->$field;
    }

    public function __set(string $field, $value) : void
    {
        $prop = $this->assertHas($field);
        $this->$prop->$field = $value;
    }

    public function __isset(string $field) : bool
    {
        $prop = $this->assertHas($field);
        return isset($this->$prop->$field);
    }

    public function __unset($field) : void
    {
        $prop = $this->assertHas($field);
        unset($this->$prop->$field);
    }

    public function getMapperClass() : string
    {
        return substr(static::CLASS, 0, -6);
    }

    public function getRow() : Row
    {
        return $this->row;
    }

    public function getRelated() : Related
    {
        return $this->related;
    }

    public function set(array $fieldsValues) : void
    {
        foreach ($fieldsValues as $field => $value) {
            if ($this->row->has($field)) {
                $this->row->$field = $value;
            } elseif ($this->related->has($field)) {
                $this->related->$field = $value;
            }
        }
    }

    public function has($field) : bool
    {
        return $this->row->has($field)
            || $this->related->has($field);
    }

    public function getArrayCopy(SplObjectStorage $tracker = null) : array
    {
        if ($tracker === null) {
            $tracker = new SplObjectStorage();
        }

        if (! $tracker->contains($this)) {
            $tracker[$this]
                = $this->row->getArrayCopy($tracker)
                + $this->related->getArrayCopy($tracker);
        }

        return $tracker[$this];
    }

    public function jsonSerialize() : array
    {
        return $this->getArrayCopy();
    }

    public function setDelete($delete = true) : void
    {
        $this->row->setDelete($delete);
    }

    public function getAction() : string
    {
        return $this->row->getAction();
    }

    private function assertHas($field) : string
    {
        if ($this->row->has($field)) {
            return 'row';
        }

        if ($this->related->has($field)) {
            return 'related';
        }

        throw Exception::propertyDoesNotExist(static::CLASS, $field);
    }
}
