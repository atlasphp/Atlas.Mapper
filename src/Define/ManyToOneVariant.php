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
class ManyToOneVariant extends RelationshipAttribute
{
    public function __construct(
        public string $column
    ) {
    }
}
