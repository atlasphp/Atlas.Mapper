<?php
declare(strict_types=1);

namespace Atlas\Mapper\Related;

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
