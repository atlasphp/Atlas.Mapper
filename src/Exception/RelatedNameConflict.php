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

class RelatedNameConflict extends Exception
{
    public function __construct(string $nativeMapperClass, string $name)
    {
        $ms = "{$nativeMapperClass}Related::\${$name} property conflicts with "
            . "existing {$nativeMapperClass}Table column also named '{$name}'.";

        parent::__construct($ms);
    }
}
