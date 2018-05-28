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
    protected $mapper;

    protected $with = [];

    public function setMapper(Mapper $mapper)
    {
        if (isset($this->mapper)) {
            throw Exception::mapperAlreadySet();
        }

        $this->mapper = $mapper;
    }

    public function joinWith(string $relatedName, callable $sub = null) : self
    {
        $this->mapper->getRelationships()->joinSelect(
            $this,
            $this->table::NAME,
            $relatedName,
            $sub
        );

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
