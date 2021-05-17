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

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Variant extends RelationshipAttribute
{
    public string $method = 'variant';

    public function __construct(
        protected mixed $value,
        protected string $class,
        protected array $on
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
        $relationships[$name]->type(
            $this->value,
            $this->getForeignMapperClass($this->class),
            $this->on
        );

        return null;
    }
}
