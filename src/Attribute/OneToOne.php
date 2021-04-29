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
use Atlas\Mapper\Relationship;
use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
class OneToOne extends RelationshipBuilder
{
    public function __construct(
        protected array $on = []
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
        return new Relationship\OneToOne(
            $name,
            $mapperLocator,
            $nativeMapperClass,
            $this->getForeignMapperClass($prop),
            $this->getOn($nativeMapperClass),
        );
    }
}
