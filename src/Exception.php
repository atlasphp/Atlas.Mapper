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

    public static function unresolvableMapperClass(
        string $spec,
        string $mapperClass
    ) : self
    {
        $ms = "{$spec} resolves to {$mapperClass}, which does not exist.";
        return new Exception($ms);
    }

    public static function propertyDoesNotExist(
        string $class,
        string $property
    ) : self
    {
        return new Exception("{$class}::\${$property} does not exist.");
    }

    public static function cannotLoadRelated(
        string $relatedName,
        string $targetClass,
        string $nativeMapperClass,
    ) : self
    {
        $ms = "Cannot load '{$relatedName}' for {$targetClass} "
            . "because there is no {$nativeMapperClass}Related property "
            . "defined for it.";

        return new Exception($ms);
    }

    public static function invalidRecordSetValue(
        string $recordSetClass,
        mixed $actual
    ) : self
    {
        if (is_object($actual)) {
            $actual = get_class($actual);
        } else {
            $actual = gettype($actual);
        }

        $ms = "{$recordSetClass} expected a Record object, "
            . "got {$actual} instead.";

        return new Exception($ms);
    }

    public static function relatedNameConflict(
        string $nativeMapperClass,
        string $name
    ) : self
    {
        $ms = "{$nativeMapperClass}Related::\${$name} property conflicts with "
            . "existing {$nativeMapperClass}Table column also named '{$name}'.";
        return new Exception($ms);
    }

    public static function cannotJoinOnVariantRelationships() : self
    {
        return new Exception("Cannot JOIN on variant relationships.");
    }

    public static function variantDoesNotExist(
        string $nativeMapperClass,
        string $relatedName,
        int|string|null $typeVal
    ) : self
    {
        $typeVal = $typeVal === null
            ? 'NULL'
            : "'" . (string) $typeVal . "'";

        $ms = "Variant relationship for value {$typeVal} "
            . "does not exist on {$nativeMapperClass}Related::\${$relatedName}.";

        return new Exception($ms);
    }

    public static function throughDoesNotExist(
        string $nativeMapperClass,
        string $nativeName,
        string $throughName,
        string $throughRelatedClass,
        string $targetMapperClass
    ) : self
    {
        // DataSource\Thread\ThreadRelated::$tags goes through
        // DataSource\Tagging\TaggingRecordSet $taggings,
        // but DataSource\Tagging\TaggingRelated does not define
        // a ManyToOne property relating to a TagRecord.

        $ms = "ManyToMany {$nativeMapperClass}Related::\${$nativeName} "
            . "goes through ManyToOne \${$throughName}, "
            . "but {$throughRelatedClass} does not define "
            . "a ManyToOne property typehinted to {$targetMapperClass}Record.";

        return new Exception($ms);
    }
}
