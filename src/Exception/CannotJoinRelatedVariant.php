<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Exception;

use Atlas\Mapper\Exception;

class CannotJoinRelatedVariant extends Exception
{
    public function __construct(string $nativeMapperClass, string $relatedName)
    {
        $ms = "Cannot JOIN on ManyToOneVariant relationships "
            . "({$nativeMapperClass}Related::\${$relatedName}).";
        parent::__construct($ms);
    }
}
