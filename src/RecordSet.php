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

use ArrayAccess;
use ArrayIterator;
use Atlas\Mapper\Exception;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use SplObjectStorage;

abstract class RecordSet implements
    ArrayAccess,
    Countable,
    IteratorAggregate,
    JsonSerializable
{
    private $records = [];

    private $newRecordFactory;

    public function __construct(
        array $records,
        callable $newRecordFactory
    ) {
        $this->newRecordFactory = $newRecordFactory;
        foreach ($records as $key => $record) {
            $this->offsetSet($key, $record);
        }
    }

    public function offsetExists($offset) : bool
    {
        return isset($this->records[$offset]);
    }

    public function offsetGet($offset) : Record
    {
        return $this->records[$offset];
    }

    public function offsetSet($offset, $value) : void
    {
        if (! is_object($value)) {
            throw Exception::invalidType(Record::CLASS, gettype($value));
        }

        if (! $value instanceof Record) {
            throw Exception::invalidType(Record::CLASS, $value);
        }

        if ($offset === null) {
            $this->records[] = $value;
            return;
        }

        $this->records[$offset] = $value;
    }

    public function offsetUnset($offset) : void
    {
        unset($this->records[$offset]);
    }

    public function count() : int
    {
        return count($this->records);
    }

    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->records);
    }

    public function isEmpty() : bool
    {
        return empty($this->records);
    }

    public function getRecords() : array
    {
        return $this->records;
    }

    public function getArrayCopy(SplObjectStorage $tracker = null) : array
    {
        if ($tracker === null) {
            $tracker = new SplObjectStorage();
        }

        if (! $tracker->contains($this)) {
            $tracker[$this] = [];
            $array = [];
            foreach ($this as $key => $record) {
                $array[] = $record->getArrayCopy($tracker);
            }
            $tracker[$this] = $array;
        }

        return $tracker[$this];
    }

    public function appendNew(array $fields = []) : Record
    {
        $record = call_user_func($this->newRecordFactory, $fields);
        $this->records[] = $record;
        return $record;
    }

    public function getOneBy(array $whereEquals) : ?Record
    {
        foreach ($this->records as $i => $record) {
            if ($this->compareBy($record, $whereEquals)) {
                return $record;
            }
        }
        return null;
    }

    public function getAllBy(array $whereEquals) : RecordSet
    {
        $records = [];
        foreach ($this->records as $i => $record) {
            if ($this->compareBy($record, $whereEquals)) {
                $records[$i] = $record;
            }
        }
        return $this->cloneSet($records);
    }

    public function detachOneBy(array $whereEquals) : ?Record
    {
        foreach ($this->records as $i => $record) {
            if ($this->compareBy($record, $whereEquals)) {
                unset($this->records[$i]);
                return $record;
            }
        }
        return null;
    }

    public function detachAllBy(array $whereEquals) : RecordSet
    {
        $records = [];
        foreach ($this->records as $i => $record) {
            if ($this->compareBy($record, $whereEquals)) {
                unset($this->records[$i]);
                $records[$i] = $record;
            }
        }
        return $this->cloneSet($records);
    }

    public function detachAll() : RecordSet
    {
        $records = $this->records;
        $this->records = [];
        return $this->cloneSet($records);
    }

    public function detachDeleted() : RecordSet
    {
        $records = [];
        foreach ($this->records as $i => $record) {
            $row = $record->getRow();
            if ($row->getStatus() === $row::DELETED) {
                unset($this->records[$i]);
                $records[$i] = $record;
            }
        }
        return $this->cloneSet($records);
    }

    protected function compareBy(Record $record, array $whereEquals) : bool
    {
        foreach ($whereEquals as $field => $value) {
            if ($record->$field != $value) {
                return false;
            }
        }
        return true;
    }

    public function setDelete(bool $delete = true) : void
    {
        foreach ($this->records as $record) {
            $record->setDelete($delete);
        }
    }

    public function jsonSerialize() : array
    {
        return $this->getArrayCopy();
    }

    private function cloneSet(array $records) : RecordSet
    {
        $clone = clone($this);
        $clone->records = $records;
        return $clone;
    }
}
