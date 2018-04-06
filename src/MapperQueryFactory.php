<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper;

use Atlas\Pdo\Connection;
use Atlas\Query\Select;
use Atlas\Table\TableQueryFactory;

class MapperQueryFactory extends TableQueryFactory
{
    public function newSelect(Connection $connection) : Select
    {
        return new MapperSelect($connection, $this->newBind());
    }
}
