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
class ManyToOneBidi extends RelationshipAttribute
{
    public $class = Relationship\ManyToOneBidi::CLASS;

    public function __construct(
        public array $on = []
    ) {
    }
}
