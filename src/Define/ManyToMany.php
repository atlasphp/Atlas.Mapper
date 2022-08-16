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
class ManyToMany extends RelationshipAttribute
{
    public function __construct(
        public string $through,
        public array $on = [],
        // public ?string $throughNative = null, // the native field on the through related
        // public ?string $throughForeign = null, // the foreign field on the through related
    ) {
    }
}
