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

class MapperClassMissing extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("Mapper class '{$class}' does not exist, or is not a Mapper.");
    }
}
