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
use Atlas\Mapper\Relationship;
use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToOneVariant extends RelationshipBuilder
{
    public function __construct(
        protected string $column
    ) {
    }

    public function __invoke(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        ReflectionProperty $prop,
        array $relationships
    ) : mixed
    {
        return new Relationship\ManyToOneVariant(
            $name,
            $mapperLocator,
            $nativeMapperClass,
            $this->column
        );
    }
}
