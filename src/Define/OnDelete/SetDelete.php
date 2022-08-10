<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Define\OnDelete;

use Atlas\Mapper\Define\RefinementAttribute;
use Atlas\Mapper\Relationship\DeletableRelationship;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class SetDelete extends RefinementAttribute
{
    public function __invoke(DeletableRelationship $relationship) : void
    {
        $relationship->onDeleteSetDelete();
    }
}
