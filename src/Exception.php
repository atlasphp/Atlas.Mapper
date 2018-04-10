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

class Exception extends \Exception
{
    public static function propertyDoesNotExist($class, string $property) : Exception
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
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

    public static function rowAlreadyMapped() : Exception
    {
        return new Exception("Row already exists in IdentityMap.");
    }

    public static function relationshipDoesNotExist(string $foreignName) : Exception
    {
        return new Exception("Relationship '$foreignName' does not exist.");
    }

    public static function unexpectedRowCountAffected($count)
    {
        return new Exception("Expected 1 row affected, actual {$count}.");
    }

    public static function immutableOnceDeleted($class, $property)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        return new Exception("{$class}::\${$property} is immutable once deleted.");
    }

    public static function invalidStatus($status)
    {
        return new Exception("Expected valid row status, got '$status' instead.");
    }

    public static function primaryValueNotScalar($col, $val)
    {
        $message = "Expected scalar value for primary key '{$col}', "
            . "got " . gettype($val) . " instead.";
        return new Exception($message);
    }

    public static function primaryValueMissing($col)
    {
        $message = "Expected scalar value for primary key '$col', "
            . "value is missing instead.";
        return new Exception($message);
    }

    public static function numericCol($col)
    {
        $message = "Expected non-numeric column name, got '$col' instead.";
        return new Exception($message);
    }

    public static function relatedNameConflict($name)
    {
        $message = "Relationship '$name' conflicts with existing column name.";
        return new Exception($message);
    }

    public static function cannotJoinOnVariantRelationships()
    {
        $message = "Cannot JOIN on variant relationships.";
        return new Exception($message);
    }

    public static function noSuchType($nativeMapperClass, $discriminatorVal)
    {
        $message = "Variant relationship type '{$discriminatorVal}' "
            . "not defined in {$nativeMapperClass}.";
        return new Exception($message);
    }

    public static function mapperAlreadySet()
    {
        $message = "Mapper already set.";
        return new Exception($message);
    }
}
