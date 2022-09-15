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

/**
 * @property string $class
 */
abstract class RelationshipAttribute
{
    public array $on = [];
}
