<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Define;

abstract class RelationshipAttribute
{
    public $class = 'UNKNOWN';

    public array $on = [];
}
