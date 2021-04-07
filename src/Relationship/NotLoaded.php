<?php
declare(strict_types=1);

namespace Atlas\Mapper\Relationship;

use EmptyIterator;
use IteratorAggregate;

final class NotLoaded implements IteratorAggregate
{
    static protected ?NotLoaded $flyweight = null;

    static public function getFlyweight() : NotLoaded
    {
        if (static::$flyweight === null) {
            static::$flyweight = new NotLoaded();
        }

        return static::$flyweight;
    }

    public function getIterator() : EmptyIterator
    {
        return new EmptyIterator();
    }
}
