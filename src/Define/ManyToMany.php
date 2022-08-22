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
class ManyToMany extends RelationshipAttribute
{
    public string $class = Relationship\ManyToMany::CLASS;

    public function __construct(
        public string $through,
        public array $on = [],
    ) {
    }
}
