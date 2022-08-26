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

class UnresolvableMapperClass extends Exception
{
    public function __construct(string $spec, string $mapperClass)
    {
        $ms = "{$spec} resolves to {$mapperClass}, which does not exist.";
        parent::__construct($ms);
    }
}
