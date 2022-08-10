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

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class OneToOne extends RelationshipAttribute
{
    public function __construct(
        public array $on = []
    ) {
    }

    public function args(string $foreignMapperClass) : array
    {
        return [
            $foreignMapperClass,
            $this->on,
        ];
    }
}
