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

class ClassDoesNotExist extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("Class '{$class}' does not exist.");
    }
}
