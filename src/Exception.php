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
    public static function classDoesNotExist(string $class) : self
    {
        return new Exception("Class '{$class}' does not exist.");
    }

    public static function propertyDoesNotExist(
        string $class,
        string $property
    ) : self
    {
        return new Exception("{$class}::\${$property} does not exist.");
    }

    public static function mapperNotFound(string $class) : self
    {
        return new Exception("{$class} not found in mapper locator.");
    }

    public static function invalidType(string $expect, mixed $actual) : self
    {
        if (is_object($actual)) {
            $actual = get_class($actual);
        } else {
            $actual = gettype($actual);
        }

        return new Exception("Expected type $expect; got $actual instead.");
    }

    public static function rowAlreadyMapped(Row $row) : self
    {
        return new Exception("Row already exists in IdentityMap.");
    }

    public static function relationshipDoesNotExist(
        string $foreignName
    ) : self
    {
        return new Exception("Relationship '$foreignName' does not exist.");
    }

    public static function primaryValueNotScalar(
        string $col,
        mixed $val
    ) : self
    {
        $message = "Expected scalar value for primary key '{$col}', "
            . "got " . gettype($val) . " instead.";
        return new Exception($message);
    }

    public static function primaryValueMissing(string $col) : self
    {
        $message = "Expected scalar value for primary key '$col', "
            . "value is missing instead.";
        return new Exception($message);
    }

    public static function relatedNameConflict(
        string $name,
        string $type
    ) : self
    {
        $message = "Relationship '$name' conflicts with existing {$type} name.";
        return new Exception($message);
    }

    public static function cannotJoinOnVariantRelationships() : self
    {
        $message = "Cannot JOIN on variant relationships.";
        return new Exception($message);
    }

    public static function noSuchType(
        string $nativeMapperClass,
        string $typeVal
    ) : self
    {
        $message = "Variant relationship type '{$typeVal}' "
            . "not defined in {$nativeMapperClass}Relationships.";
        return new Exception($message);
    }

    public static function mapperAlreadySet() : self
    {
        $message = "Mapper already set.";
        return new Exception($message);
    }

    public static function couldNotFindThroughRelationship(
        string $type,
        string $throughName,
        string $foreignName,
        string $mapperClass
    ) : self
    {
        $message = "Could not find ManyToOne $type relationship through '$throughName' "
            . "for ManyToMany '{$foreignName}' on {$mapperClass}";
        return new Exception($message);
    }
}
