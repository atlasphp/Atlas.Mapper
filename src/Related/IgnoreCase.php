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
use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
class IgnoreCase extends RelationshipAttribute
{
    public function __invoke(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        ReflectionProperty $prop,
        array $relationships
    ) : mixed
    {
        $relationships[$name]->ignoreCase();
        return null;
    }
}
