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

#[Attribute(Attribute::TARGET_PROPERTY)]
class IgnoreCase extends RefinementAttribute
{
    public function __construct(protected bool $mode = true)
    {
    }

    public function __invoke(Relationship $relationship) : void
    {
        $relationship->ignoreCase($this->mode);
    }
}
