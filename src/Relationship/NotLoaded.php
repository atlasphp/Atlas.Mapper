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
    protected static ?NotLoaded $instance = null;

    public static function getInstance() : NotLoaded
    {
        if (static::$instance === null) {
            static::$instance = new NotLoaded();
        }

        return static::$instance;
    }
}
