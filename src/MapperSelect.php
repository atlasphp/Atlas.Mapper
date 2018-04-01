<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Mapper;

use Atlas\Pdo\Connection;
use Atlas\Query\Bind;
use Atlas\Table\Table;
use Atlas\Table\TableSelect;

class MapperSelect
{
    protected $mapper;

    protected $with = [];

    public function __construct(
        TableSelect $tableSelect,
        Mapper $mapper
    ) {
        $this->tableSelect = $tableSelect;
        $this->mapper = $mapper;
    }

    public function __call($method, $params)
    {
        $result = $this->tableSelect->$method(...$params);
        if ($result === $this->tableSelect) {
            return $this;
        }

        return $result;
    }

    public function joinWith(string $join, string $relatedName) : self
    {
        $this->mapper
            ->getRelationships()
            ->get($relatedName)
            ->joinSelect($join, $this);

        return $this;
    }

    public function with(array $with) : self
    {
        // make sure that all with() are on relateds that actually exist
        $fields = array_keys($this->mapper->getRelationships()->getFields());
        foreach ($with as $key => $val) {
            $related = $key;
            if (is_int($key)) {
                $related = $val;
            }
            if (! in_array($related, $fields)) {
                throw Exception::relationshipDoesNotExist($related);
            }
        }
        $this->with = $with;
        return $this;
    }

    public function fetchRecord() : ?Record
    {
        $row = $this->fetchRow();
        if (! $row) {
            return null;
        }

        return $this->mapper->turnRowIntoRecord($row, $this->with);
    }

    public function fetchRecords() : array
    {
        $rows = $this->fetchRows();
        return $this->mapper->turnRowsIntoRecords($rows, $this->with);
    }

    public function fetchRecordSet() : RecordSet
    {
        $records = $this->fetchRecords();
        return $this->mapper->newRecordSet($records);
    }
}
