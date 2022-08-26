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

class InvalidRecordSetValue extends Exception
{
    public function __construct(string $recordSetClass, mixed $actual)
    {
        if (is_object($actual)) {
            $actual = get_class($actual);
        } else {
            $actual = gettype($actual);
        }

        $ms = "{$recordSetClass} expected a Record object, "
            . "got {$actual} instead.";

        parent::__construct($ms);
    }
}
