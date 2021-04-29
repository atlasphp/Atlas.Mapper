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

use Atlas\Table\TableSelect;

abstract class MapperSelect extends TableSelect
{
    /**
     * Returns a new TableSelect object.
     *
     * @param Connection $connection A read connection.
     * @param Table $table The table being selected from.
     * @param array $whereEquals Equality pairs of columns and values.
     * @param Mapper $mapper The mapper being used.
     * @return static
     */
    static public function new(mixed $arg, mixed ...$args) : static
    {
        $mapper = array_pop($args);
        $select = parent::new($arg, ...$args);
        $select->mapper = $mapper;
        return $select;
    }

    protected Mapper $mapper;

    protected array $eager = [];

    public function joinEager(string $relatedName, callable $sub = null) : static
    {
        $this->mapper->getRelationships()->joinSelect(
            $this,
            $this->table::NAME,
            $relatedName,
            $sub
        );

        return $this;
    }

    public function eager(array $eager) : static
    {
        $relationships = $this->mapper->getRelationships();

        foreach ($eager as $key => $val) {
            $relatedName = $key;

            if (is_int($key)) {
                $relatedName = $val;
            }

            if (! $relationships->has($relatedName)) {
                throw Exception::relationshipDoesNotExist($relatedName);
            }
        }

        $this->eager = $eager;
        return $this;
    }

    public function fetchRecord() : ?Record
    {
        $row = $this->fetchRow();
        if (! $row) {
            return null;
        }

        return $this->mapper->turnRowIntoRecord($row, $this->eager);
    }

    public function fetchRecords() : array
    {
        $rows = $this->fetchRows();
        return $this->mapper->turnRowsIntoRecords($rows, $this->eager);
    }

    public function fetchRecordSet() : RecordSet
    {
        $records = $this->fetchRecords();
        return $this->mapper->newRecordSet($records);
    }
}
