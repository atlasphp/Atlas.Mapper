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

class UnexpectedRelatedTypehint extends Exception
{
    public function __construct(
        string $nativeMapperClass,
        string $relatedName,
        string $expect,
        string $actual)
    {
        $ms = "{$nativeMapperClass}Related::\${$relatedName} expected a "
            . "typehint of {$expect}; got {$actual} instead.";
        parent::__construct($ms);
    }
}
