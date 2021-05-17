<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Related;

use Atlas\Mapper\MapperLocator;
use ReflectionProperty;

abstract class RelationshipAttribute
{
    abstract public function __invoke(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        ReflectionProperty $prop,
        array $relationships
    ) : mixed;

    public function getForeignMapperClass(string|ReflectionProperty $spec) : string
    {
        if ($spec instanceof ReflectionProperty) {
            $spec = $this->getType($spec);
        }

        $parts = explode('\\', $spec);
        array_pop($parts);
        return implode('\\', $parts) . '\\' . end($parts);
    }
}
