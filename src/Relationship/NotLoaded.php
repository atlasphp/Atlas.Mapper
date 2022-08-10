<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Relationship;

final class NotLoaded
{
    static protected ?NotLoaded $instance = null;

    static public function getInstance() : NotLoaded
    {
        if (static::$instance === null) {
            static::$instance = new NotLoaded();
        }

        return static::$instance;
    }
}
