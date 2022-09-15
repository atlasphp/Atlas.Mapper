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
        ReflectionType|string|null $relatedSpec
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

    public static function listJoinSpec(string $relatedSpec) : array
    {
        $relatedSpec = trim($relatedSpec);

        // extract the foreign alias
        $foreignAlias = '';
        $pos = stripos($relatedSpec, ' AS ');
        if ($pos !== false) {
            $foreignAlias = trim(substr($relatedSpec, $pos + 4));
            $relatedSpec = trim(substr($relatedSpec, 0, $pos));
        }

        // extract the join type
        $join = 'JOIN';
        $pos = strpos($relatedSpec, ' ');
        if ($pos !== false) {
            $join = trim(substr($relatedSpec, 0, $pos));
            $relatedSpec = trim(substr($relatedSpec, $pos));
        }

        // fix the foreign alias
        if ($foreignAlias === '') {
            $foreignAlias = $relatedSpec;
        }

        return [
            $relatedSpec,
            $join,
            $foreignAlias,
        ];
    }

}
