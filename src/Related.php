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
use Atlas\Mapper\Related\NotLoaded;
use SplObjectStorage;

abstract class Related
{
    protected const LOADED = '\0LOADED';

    public function __construct(array $fields = [])
    {
        $this->{static::LOADED} = (object) [];
        $this->set($fields);
    }

    public function __get(string $field) : mixed
    {
        $this->assertHas($field);

        if (isset($this->{static::LOADED}->$field)) {
            return $this->$field;
        }

        return NotLoaded::getInstance();
    }

    public function __set(string $field, mixed $value) : void
    {
        if ($field === static::LOADED) {
            $this->{static::LOADED} = $value;
            return;
        }

        $this->assertHas($field);

        $this->$field = $value;
        $this->{static::LOADED}->$field = true;
    }

    public function __isset(string $field) : bool
    {
        $this->assertHas($field);
        return isset($this->{static::LOADED}->$field)
            && isset($this->$field);
    }

    public function __unset(string $field) : void
    {
        $this->assertHas($field);
        unset($this->{static::LOADED}->$field);
    }

    public function set(array $fields) : void
    {
        foreach ($fields as $field => $value) {
            if ($this->has($field)) {
                $this->$field = $value;
                $this->{static::LOADED}->$field = true;
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

            foreach (get_object_vars($this) as $field => $value) {
                if ($field === static::LOADED) {
                    continue;
                }

                $array[$field] = $value;

                if ($value !== null) {
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
