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
    static public function new(mixed $arg, mixed ...$args) : static
    {
        /** @var Mapper */
        $mapper = array_pop($args);
        $select = parent::new($arg, ...$args);
        $select->mapper = $mapper;
        return $select;
    }

    protected $mapper;

    protected $loadRelated = [];

    public function joinRelated(string $relatedName, callable $sub = null) : self
    {
        $this->mapper->getRelationships()->joinSelect(
            $this,
            $this->table::NAME,
            $relatedName,
            $sub
        );

        return $this;
    }

    public function loadRelated(array $loadRelated) : self
    {
        // make sure that all loadRelated() are on relateds that actually exist
        $fields = $this->mapper->getRelationships()->getRelationshipLocator()->getNames();
        foreach ($loadRelated as $key => $val) {
            $related = $key;
            if (is_int($key)) {
                $related = $val;
            }
            if (! in_array($related, $fields)) {
                throw Exception::relationshipDoesNotExist($related);
            }
        }
        $this->loadRelated = $loadRelated;
        return $this;
    }

    public function fetchRecord() : ?Record
    {
        $row = $this->fetchRow();
        if (! $row) {
            return null;
        }

        return $this->mapper->turnRowIntoRecord($row, $this->loadRelated);
    }

    public function fetchRecords() : array
    {
        $rows = $this->fetchRows();
        return $this->mapper->turnRowsIntoRecords($rows, $this->loadRelated);
    }

    public function fetchRecordSet() : RecordSet
    {
        $records = $this->fetchRecords();
        return $this->mapper->newRecordSet($records);
    }
}
