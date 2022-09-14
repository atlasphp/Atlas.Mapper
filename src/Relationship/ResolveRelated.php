<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use SplObjectStorage;
use ReflectionType;
use ReflectionNamedType;
use ReflectionUnionType;
use Atlas\Mapper\Exception;

class ResolveRelated
{
    public static function mapperClass(
        string $nativeMapperClass,
        string $relatedName,
        ReflectionType|string $relatedSpec
    ) : string
    {
        if ($relatedSpec instanceof ReflectionUnionType) {
            return 'mixed';
        }

        if ($relatedSpec instanceof ReflectionNamedType) {
            $relatedSpec = $relatedSpec->getName();
        }

        if ($relatedSpec === 'mixed') {
            return $relatedSpec;
        }

        $parts = explode('\\', $relatedSpec);
        array_pop($parts);

        if (empty($parts)) {
            throw new Exception\CannotResolveRelatedMapperClass(
                $nativeMapperClass,
                $relatedName,
                $relatedSpec,
                $relatedSpec
            );
        }

        $relatedMapperClass = implode('\\', $parts) . '\\' . end($parts);

        if (! class_exists($relatedMapperClass)) {
            throw new Exception\CannotResolveRelatedMapperClass(
                $nativeMapperClass,
                $relatedName,
                $relatedSpec,
                $relatedMapperClass
            );
        }

        return $relatedMapperClass;
    }
}
