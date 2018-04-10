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

abstract class Record implements JsonSerializable
{
    const DELETE = 'delete';

    const INSERT = 'insert';

    const UPDATE = 'update';

    private $status = null;

    private $row;

    private $related;

    private $delete = false;

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
        if (
            $this->status !== self::INSERT
            && $this->status !== self::DELETE
        ) {
            $this->status = self::UPDATE;
        }
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
        return substr(get_class($this), 0, -6) . 'Mapper';
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

    public function getArrayCopy() : array
    {
        return $this->row->getArrayCopy()
             + $this->related->getArrayCopy();
    }

    public function jsonSerialize() : array
    {
        return $this->getArrayCopy();
    }

    public function setDelete($delete = true) : void
    {
        $this->delete = (bool) $delete;
    }

    public function getStatus() : ?string
    {
        return $this->delete ? Record::DELETE : $this->status;
    }

    public function setStatus(string $status) : void
    {
        $this->status = $status;
    }

    private function assertHas($field) : string
    {
        if ($this->row->has($field)) {
            return 'row';
        }

        if ($this->related->has($field)) {
            return 'related';
        }

        throw Exception::propertyDoesNotExist($this, $field);
    }
}
