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

class PropertyDoesNotExist extends Exception
{
    public function __construct(string $class, string $property)
    {
        parent::__construct("{$class}::\${$property} does not exist.");
    }
}
