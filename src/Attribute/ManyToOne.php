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
class ManyToOne extends RelationshipBuilder
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
        $foreignMapperClass = $this->getForeignMapperClass($prop);

        return new Relationship\ManyToOne(
            $name,
            $mapperLocator,
            $nativeMapperClass,
            $foreignMapperClass,
            $this->getOn($foreignMapperClass),
        );
    }

}
