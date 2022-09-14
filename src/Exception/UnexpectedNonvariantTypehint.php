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

class UnexpectedNonvariantTypehint extends Exception
{
    public function __construct(string $nativeMapperClass, string $property)
    {
        $ms = "{$nativeMapperClass}Related::\${$property} expected a typehint "
            . "of Record, ?Record, or RecordSet; got 'mixed' or a union of "
            . "types instead.";
        parent::__construct($ms);
    }
}
