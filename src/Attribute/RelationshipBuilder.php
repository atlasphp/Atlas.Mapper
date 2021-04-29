<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Attribute;

use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use Atlas\Mapper\Relationship\Relationship;
use ReflectionProperty;
use Atlas\Mapper\Exception;

abstract class RelationshipBuilder extends RelationshipAttribute
{
    public function getForeignMapperClass(ReflectionProperty $prop) : string
    {
        $type = $this->getType($prop);
        $parts = explode('\\', $type);
        array_pop($parts);
        return implode('\\', $parts) . '\\' . end($parts);
    }

    protected function getType(ReflectionProperty $prop) : string
    {
        $types = explode('|', (string) $prop->getType());

        foreach ($types as $type) {
            if (
                is_subclass_of($type, Record::CLASS)
                || is_subclass_of($type, RecordSet::CLASS)
            ) {
                return $type;
            }
        }

        return 'UNKNOWN';
    }

    protected function getOn(string $mapperClass) : array
    {
        if (! empty($this->on)) {
            return $this->on;
        }

        $on = [];
        $tableClass = $mapperClass . 'Table';

        foreach ($tableClass::PRIMARY_KEY as $col) {
            $on[$col] = $col;
        }

        return $on;
    }
}
