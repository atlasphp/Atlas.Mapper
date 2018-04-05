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

class QueryFactory extends \Atlas\Table\QueryFactory
{
    public function newSelect(Connection $connection, ...$args) : Select
    {
        return $this->newQuery(MapperSelect::CLASS, $connection, $args);
    }
}
