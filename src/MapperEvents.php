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

use Atlas\Query\Delete;
use Atlas\Query\Insert;
use Atlas\Query\Update;
use PDOStatement;

abstract class MapperEvents
{
    public function modifySelect(Mapper $mapper, MapperSelect $select)
    {
    }

    public function beforeInsert(Mapper $mapper, Record $record)
    {
    }

    public function modifyInsert(Mapper $mapper, Record $record, Insert $insert)
    {
    }

    public function afterInsert(Mapper $mapper, Record $record, Insert $insert, PDOStatement $pdoStatement)
    {
    }

    public function beforeUpdate(Mapper $mapper, Record $record)
    {
    }

    public function modifyUpdate(Mapper $mapper, Record $record, Update $update)
    {
    }

    public function afterUpdate(Mapper $mapper, Record $record, Update $update, PDOStatement $pdoStatement)
    {
    }

    public function beforeDelete(Mapper $mapper, Record $record)
    {
    }

    public function modifyDelete(Mapper $mapper, Record $record, Delete $delete)
    {
    }

    public function afterDelete(Mapper $mapper, Record $record, Delete $delete, PDOStatement $pdoStatement)
    {
    }
}
