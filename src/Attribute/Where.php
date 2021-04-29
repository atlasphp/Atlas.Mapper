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
use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Where extends RelationshipAttribute
{
    public array $bindInline = [];

    public function __construct(
        protected string $condition,
        mixed ...$bindInline
    ) {
        $this->bindInline = $bindInline;
    }

    public function __invoke(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        ReflectionProperty $prop,
        array $relationships
    ) : mixed
    {
        $relationships[$name]->where(
            $this->condition,
            ...$this->bindInline
        );

        return null;
    }
}
