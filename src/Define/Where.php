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

use Atlas\Mapper\Relationship\Relationship;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Where extends RefinementAttribute
{
    public array $bindInline = [];

    public function __construct(
        public string $condition,
        mixed ...$bindInline
    ) {
        $this->bindInline = $bindInline;
    }

    public function __invoke(Relationship $relationship) : void
    {
        $relationship->where(
            $this->condition,
            ...$this->bindInline
        );
    }
}
