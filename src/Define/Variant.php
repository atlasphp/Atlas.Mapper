<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Define;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\Relationship\ManyToOneVariant;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Variant extends RefinementAttribute
{
    public function __construct(
        public int|string $value,
        public string $class,
        public array $on
    ) {
    }

    public function __invoke(ManyToOneVariant $relationship) : void
    {
        $relationship->type($this);
    }
}
