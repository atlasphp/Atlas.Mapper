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

use Atlas\Mapper\Identity;
use Atlas\Mapper\Relationship\RelationshipLocator;
use Atlas\Table\Row;
use Atlas\Table\Table;
use SplObjectStorage;

abstract class Mapper
{
    static public function classFrom(string $spec)
    {
        // DataSource\Foo\BarRecord => DataSource\Foo\Foo
        $parts = explode('\\', $spec);
        array_pop($parts);
        return implode('\\', $parts) . '\\' . end($parts);
    }

    protected $table;

    protected $relationshipLocator;

    protected $mapperEvents;

    protected $recordSetClass;

    protected $identityMap;

    public function __construct(
        Table $table,
        RelationshipLocator $relationshipLocator,
        MapperEvents $mapperEvents
    ) {
        $this->table = $table;
        $this->relationshipLocator = $relationshipLocator;
        $this->mapperEvents = $mapperEvents;

        $this->recordSetClass = static::CLASS . 'RecordSet';

        $primaryKey = $this->table::PRIMARY_KEY;
        if (count($primaryKey) == 1) {
            $this->identityMap = new Identity\IdentitySimple($primaryKey[0]);
        } else {
            $this->identityMap = new Identity\IdentityComposite($primaryKey);
        }
    }

    public function getTable() : Table
    {
        return $this->table;
    }

    public function getRelationshipLocator() : RelationshipLocator
    {
        return $this->relationshipLocator;
    }

    public function fetchRecord($primaryVal, array $loadRelated = []) : ?Record
    {
        $serial = $this->identityMap->getSerial($primaryVal);
        $row = $this->identityMap->getRow($serial)
            ?? $this->table->selectRow($this->select(), $primaryVal);

        if ($row === null) {
            return null;
        }

        return $this->turnRowIntoRecord($row, $loadRelated);
    }

    public function fetchRecordBy(
        array $whereEquals,
        array $loadRelated = []
    ) : ?Record
    {
        $row = $this->select($whereEquals)->fetchRow();
        if ($row === null) {
            return null;
        }

        return $this->turnRowIntoRecord($row, $loadRelated);
    }

    public function fetchRecords(array $primaryVals, array $loadRelated = []) : array
    {
        $rows = [];
        $missing = [];

        // find identity-mapped rows
        foreach ($primaryVals as $primaryVal) {
            $serial = $this->identityMap->getSerial($primaryVal);
            $memory = $this->identityMap->getRow($serial);
            if ($memory === null) {
                $rows[$serial] = null;
                $missing[$serial] = $primaryVal;
            } else {
                $rows[$serial] = $memory;
            }
        }

        // early return if all records were identity-mapped
        if (count($missing) == 0) {
            return $this->turnRowsIntoRecords($rows, $loadRelated);
        }

        // fetch rows missing from identity map
        foreach ($this->table->selectRows($this->select(), $missing) as $row) {
            $serial = $this->identityMap->getSerial($row);
            $rows[$serial] = $row;
            unset($missing[$serial]);
        }

        // remove placeholders for unfetched rows
        foreach ($missing as $serial => $primaryVal) {
            unset($rows[$serial]);
        }

        return $this->turnRowsIntoRecords($rows, $loadRelated);
    }

    public function fetchRecordsBy(array $whereEquals, array $loadRelated = []) : array
    {
        $rows = $this->select($whereEquals)->fetchRows();
        return $this->turnRowsIntoRecords($rows, $loadRelated);
    }

    public function fetchRecordSet(
        array $primaryVals,
        array $loadRelated = []
    ) : RecordSet
    {
        $records = $this->fetchRecords($primaryVals, $loadRelated);
        return $this->newRecordSet($records);
    }

    public function fetchRecordSetBy(
        array $whereEquals,
        array $loadRelated = []
    ) : RecordSet
    {
        $records = $this->fetchRecordsBy($whereEquals, $loadRelated);
        return $this->newRecordSet($records);
    }

    public function select(array $whereEquals = []) : MapperSelect
    {
        $class = static::CLASS . 'Select';
        $select = $class::new(
            $this->table->getReadConnection(),
            $this->table,
            $whereEquals,
            $this
        );
        $this->mapperEvents->modifySelect($this, $select);
        return $select;
    }

    public function insert(Record $record) : void
    {
        $row = $record->getRow();
        $this->mapperEvents->beforeInsert($this, $record);
        $this->fixNativeRecord($record);
        $insert = $this->table->insertRowPrepare($row);
        $this->mapperEvents->modifyInsert($this, $record, $insert);
        $pdoStatement = $this->table->insertRowPerform($row, $insert);
        $this->identityMap->setRow($row);
        $this->fixForeignRecord($record);
        $this->mapperEvents->afterInsert(
            $this,
            $record,
            $insert,
            $pdoStatement
        );
    }

    public function update(Record $record) : void
    {
        $row = $record->getRow();
        $this->mapperEvents->beforeUpdate($this, $record);
        $this->fixNativeRecord($record);
        $update = $this->table->updateRowPrepare($row);
        $this->mapperEvents->modifyUpdate($this, $record, $update);
        $pdoStatement = $this->table->updateRowPerform($row, $update);
        $this->fixForeignRecord($record);
        if ($pdoStatement === null) {
            return;
        }
        $this->mapperEvents->afterUpdate(
            $this,
            $record,
            $update,
            $pdoStatement
        );
    }

    public function delete(Record $record) : void
    {
        $row = $record->getRow();
        $this->mapperEvents->beforeDelete($this, $record);
        $this->fixNativeRecord($record);
        $delete = $this->table->deleteRowPrepare($row);
        $this->mapperEvents->modifyDelete($this, $record, $delete);
        $pdoStatement = $this->table->deleteRowPerform($row, $delete);
        $this->fixForeignRecord($record);
        $this->mapperEvents->afterDelete(
            $this,
            $record,
            $delete,
            $pdoStatement
        );
    }

    public function persist(
        Record $record,
        SplObjectStorage $tracker = null
    ) : void
    {
        if ($tracker === null) {
            $tracker = new SplObjectStorage();
        }

        if ($tracker->contains($record)) {
            return;
        }

        $tracker->attach($record);

        foreach ($this->relationshipLocator->getPersistBeforeNative() as $relationship) {
            $relationship->persistForeign($record, $tracker);
        }

        $this->fixNativeRecord($record);
        $method = $record->getAction();

        if ($method !== null) {
            $this->$method($record);
        }

        $this->fixForeignRecord($record);

        foreach ($this->relationshipLocator->getPersistAfterNative() as $relationship) {
            $relationship->persistForeign($record, $tracker);
        }
    }

    public function newRecord(array $fields = []) : Record
    {
        $row = $this->table->newRow($fields);
        $record = $this->newRecordFromRow($row, $fields);
        return $record;
    }

    public function newRecords(array $fieldSets) : array
    {
        $records = [];
        foreach ($fieldSets as $fields) {
            $records[] = $this->newRecord($fields);
        }
        return $records;
    }

    public function newRecordSet(array $records = []) : RecordSet
    {
        $recordSetClass = $this->recordSetClass;
        return new $recordSetClass(
            $records,
            [$this, 'newRecord']
        );
    }

    public function turnRowIntoRecord(Row $row, array $loadRelated = []) : Record
    {
        $record = $this->newRecordFromSelectedRow($row);
        $this->stitchIntoRecords([$record], $loadRelated);
        return $record;
    }

    public function turnRowsIntoRecords(array $rows, array $loadRelated = []) : array
    {
        $records = [];
        foreach ($rows as $row) {
            $records[] = $this->newRecordFromSelectedRow($row);
        }
        $this->stitchIntoRecords($records, $loadRelated);
        return $records;
    }

    protected function newRecordFromSelectedRow(Row $row) : Record
    {
        $row = $this->identityMap->setOrGetRow($row);
        return $this->newRecordFromRow($row);
    }

    protected function newRecordFromRow(Row $row, array $fields = []) : Record
    {
        $recordClass = $this->getRecordClass($row);
        return new $recordClass(
            $row,
            $this->newRelated($fields)
        );
    }

    public function newRelated(array $fields = []) : Related
    {
        $relatedClass = static::CLASS . 'Related';
        $related = new $relatedClass();
        $related->set($fields);
        return $related;
    }

    protected function getRecordClass(Row $row) : string
    {
        return static::CLASS . 'Record';
    }

    protected function fixNativeRecord(Record $nativeRecord) : void
    {
        foreach ($this->relationshipLocator as $relationship) {
            $relationship->fixNativeRecord($nativeRecord);
        }
    }

    protected function fixForeignRecord(Record $nativeRecord) : void
    {
        foreach ($this->relationshipLocator as $relationship) {
            $relationship->fixForeignRecord($nativeRecord);
        }
    }

    protected function stitchIntoRecords(
        array $nativeRecords,
        array $loadRelated = []
    ) : void
    {
        foreach ($this->fixLoadRelated($loadRelated) as $relatedName => $custom) {
            if (! $this->relationshipLocator->has($relatedName)) {
                throw Exception::relationshipDoesNotExist($relatedName);
            }
            $this->relationshipLocator->get($relatedName)->stitchIntoRecords(
                $nativeRecords,
                $custom
            );
        }
    }

    protected function fixLoadRelated(array $spec) : array
    {
        $loadRelated = [];
        foreach ($spec as $key => $val) {
            if (is_int($key)) {
                $loadRelated[$val] = null;
            } elseif (is_array($val) && ! is_callable($val)) {
                $loadRelated[$key] = function ($select) use ($val) {
                    $select->loadRelated($val);
                };
            } else {
                $loadRelated[$key] = $val;
            }
        }
        return $loadRelated;
    }
}
