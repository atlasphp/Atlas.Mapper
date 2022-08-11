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
use Atlas\Mapper\Relationship\Relationship;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Variant extends RefinementAttribute
{
    public function __construct(
        public mixed $value,
        public string $class,
        public array $on
    ) {
    }

    public function __invoke(Relationship $relationship) : void
    {
        $relationship->type($this);
    }
}
