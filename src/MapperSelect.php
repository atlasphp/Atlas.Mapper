<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Mapper;

use Atlas\Table\TableSelect;

class MapperSelect extends TableSelect
{
    protected $mapper;

    protected $with = [];

    /*
    I am not happy about using setter injection, but it's the only way to make
    the underlying TableSelect and Select methods recognizable to IDEs
    without adding @method docblocks for all of their methods. I'd rather
    override the constructor, but then it cannot honor the
    TableEvents::modifySelect() method, or any other overrides at the
    Table::select() level.
    */
    public function setMapper(Mapper $mapper)
    {
        if (isset($this->mapper)) {
            throw Exception::mapperAlreadySet();
        }

        $this->mapper = $mapper;
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
