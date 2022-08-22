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

use Atlas\Mapper\Relationship;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToOneVariant extends RelationshipAttribute
{
    public string $class = Relationship\ManyToOneVariant::CLASS;

    public function __construct(
        public string $column
    ) {
    }
}
