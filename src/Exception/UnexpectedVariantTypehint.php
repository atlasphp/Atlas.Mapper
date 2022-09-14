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

class UnexpectedVariantTypehint extends Exception
{
    public function __construct(string $nativeMapperClass, string $property, string $actual)
    {
        $ms = "{$nativeMapperClass}Related::\${$property} expected a typehint "
            . "of 'mixed' or a union of Record types; got {$actual} instead.";
        parent::__construct($ms);
    }
}
