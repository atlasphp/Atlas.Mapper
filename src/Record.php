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
    private Row $__ROW;

    private Related $__RELATED;

    public function __construct(Row $row, Related $related)
    {
        $this->__ROW = $row;
        $this->__RELATED = $related;
    }

    public function __get(string $field) : mixed
    {
        $prop = $this->assertHas($field);
        return $this->$prop->$field;
    }

    public function __set(string $field, mixed $value) : void
    {
        $prop = $this->assertHas($field);
        $this->$prop->$field = $value;
    }

    public function __isset(string $field) : bool
    {
        $prop = $this->assertHas($field);
        return isset($this->$prop->$field);
    }

    public function __unset(string $field) : void
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
        return $this->__ROW;
    }

    public function getRelated() : Related
    {
        return $this->__RELATED;
    }

    public function set(array $fieldsValues) : void
    {
        foreach ($fieldsValues as $field => $value) {
            if ($this->__ROW->has($field)) {
                $this->__ROW->$field = $value;
            } elseif ($this->__RELATED->has($field)) {
                $this->__RELATED->$field = $value;
            }
        }
    }

    public function has(string $field) : bool
    {
        return $this->__ROW->has($field)
            || $this->__RELATED->has($field);
    }

    public function getArrayCopy(SplObjectStorage $tracker = null) : array
    {
        if ($tracker === null) {
            $tracker = new SplObjectStorage();
        }

        if (! $tracker->contains($this)) {
            $tracker[$this]
                = $this->__ROW->getArrayCopy()
                + $this->__RELATED->getArrayCopy($tracker);
        }

        /** @var array */
        return $tracker[$this];
    }

    public function jsonSerialize() : array
    {
        return $this->getArrayCopy();
    }

    public function setDelete(bool $delete = true) : void
    {
        $this->__ROW->setDelete($delete);
    }

    public function getAction() : ?string
    {
        return $this->__ROW->getNextAction();
    }

    private function assertHas(string $field) : string
    {
        if ($this->__ROW->has($field)) {
            return '__ROW';
        }

        if ($this->__RELATED->has($field)) {
            return '__RELATED';
        }

        throw Exception::propertyDoesNotExist(static::CLASS, $field);
    }
}
