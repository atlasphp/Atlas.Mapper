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

use Atlas\Table\Row;

class Exception extends \Exception
{
    public static function classDoesNotExist(string $class) : Exception
    {
        return new Exception("Class '{$class}' does not exist.");
    }

    public static function propertyDoesNotExist(
        string $class,
        string $property
    ) : Exception
    {
        return new Exception("{$class}::\${$property} does not exist.");
    }

    public static function mapperNotFound(string $class) : Exception
    {
        return new Exception("{$class} not found in mapper locator.");
    }

    public static function invalidType(string $expect, $actual) : Exception
    {
        if (is_object($actual)) {
            $actual = get_class($actual);
        } else {
            $actual = gettype($actual);
        }

        return new Exception("Expected type $expect; got $actual instead.");
    }

    public static function rowAlreadyMapped(Row $row) : Exception
    {
        return new Exception("Row already exists in IdentityMap.");
    }

    public static function relationshipDoesNotExist(
        string $foreignName
    ) : Exception
    {
        return new Exception("Relationship '$foreignName' does not exist.");
    }

    public static function primaryValueNotScalar(string $col, $val)
    {
        $message = "Expected scalar value for primary key '{$col}', "
            . "got " . gettype($val) . " instead.";
        return new Exception($message);
    }

    public static function primaryValueMissing(string $col)
    {
        $message = "Expected scalar value for primary key '$col', "
            . "value is missing instead.";
        return new Exception($message);
    }

    public static function relatedNameConflict(string $name, string $type)
    {
        $message = "Relationship '$name' conflicts with existing {$type} name.";
        return new Exception($message);
    }

    public static function cannotJoinOnVariantRelationships()
    {
        $message = "Cannot JOIN on variant relationships.";
        return new Exception($message);
    }

    public static function noSuchType(string $nativeMapperClass, $typeVal)
    {
        $message = "Variant relationship type '{$typeVal}' "
            . "not defined in {$nativeMapperClass}Relationships.";
        return new Exception($message);
    }

    public static function mapperAlreadySet()
    {
        $message = "Mapper already set.";
        return new Exception($message);
    }

    public static function couldNotFindThroughRelationship($type, $throughName, $foreignName, $mapperClass)
    {
        $message = "Could not find ManyToOne $type relationship through '$throughName' "
            . "for ManyToMany '{$foreignName}' on {$mapperClass}";
        return new Exception($message);
    }
}
