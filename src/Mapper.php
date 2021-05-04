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

use Atlas\Mapper\Identity\IdentityComposite;
use Atlas\Mapper\Identity\IdentityMap;
use Atlas\Mapper\Identity\IdentitySimple;
use Atlas\Mapper\Relationships;
use Atlas\Table\Row;
use Atlas\Table\Table;
use SplObjectStorage;

abstract class Mapper
{
    protected string $recordSetClass;

    protected IdentityMap $identityMap;

    protected ?Related $related = null;

    public function __construct(
        protected Table $table,
        protected MapperRelationships $relationships,
        protected MapperEvents $mapperEvents
    ) {
        $this->recordSetClass = static::class . 'RecordSet';

        $primaryKey = $this->table::PRIMARY_KEY;
        if (count($primaryKey) == 1) {
            $this->identityMap = new Identity\IdentitySimple($primaryKey);
        } else {
            $this->identityMap = new Identity\IdentityComposite($primaryKey);
        }
    }

    public function getTable() : Table
    {
        return $this->table;
    }

    public function getRelationships() : MapperRelationships
    {
        return $this->relationships;
    }

    public function fetchRecord(mixed $primaryVal, array $eager = []) : ?Record
    {
        $serial = $this->identityMap->getSerial($primaryVal);
        $row = $this->identityMap->getRow($serial)
            ?? $this->table->selectRow($this->select(), $primaryVal);

        if ($row === null) {
            return null;
        }

        return $this->turnRowIntoRecord($row, $eager);
    }

    public function fetchRecordBy(
        array $whereEquals,
        array $eager = []
    ) : ?Record
    {
        $row = $this->select($whereEquals)->fetchRow();
        if ($row === null) {
            return null;
        }

        return $this->turnRowIntoRecord($row, $eager);
    }

    public function fetchRecords(array $primaryVals, array $eager = []) : array
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
            return $this->turnRowsIntoRecords($rows, $eager);
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

        return $this->turnRowsIntoRecords($rows, $eager);
    }

    public function fetchRecordsBy(array $whereEquals, array $eager = []) : array
    {
        $rows = $this->select($whereEquals)->fetchRows();
        return $this->turnRowsIntoRecords($rows, $eager);
    }

    public function fetchRecordSet(
        array $primaryVals,
        array $eager = []
    ) : RecordSet
    {
        $records = $this->fetchRecords($primaryVals, $eager);
        return $this->newRecordSet($records);
    }

    public function fetchRecordSetBy(
        array $whereEquals,
        array $eager = []
    ) : RecordSet
    {
        $records = $this->fetchRecordsBy($whereEquals, $eager);
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
        $this->relationships->fixNativeRecord($record);
        $insert = $this->table->insertRowPrepare($row);
        $this->mapperEvents->modifyInsert($this, $record, $insert);
        $pdoStatement = $this->table->insertRowPerform($row, $insert);
        $this->identityMap->setRow($row);
        $this->relationships->fixForeignRecord($record);
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
        $this->relationships->fixNativeRecord($record);
        $update = $this->table->updateRowPrepare($row);
        $this->mapperEvents->modifyUpdate($this, $record, $update);
        $pdoStatement = $this->table->updateRowPerform($row, $update);
        $this->relationships->fixForeignRecord($record);
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
        $this->relationships->fixNativeRecord($record);
        $delete = $this->table->deleteRowPrepare($row);
        $this->mapperEvents->modifyDelete($this, $record, $delete);
        $pdoStatement = $this->table->deleteRowPerform($row, $delete);
        $this->relationships->fixForeignRecord($record);
        if ($pdoStatement === null) {
            return;
        }
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

        $this->relationships->persistBeforeNative($record, $tracker);
        $this->relationships->fixNativeRecord($record);

        $method = $record->getNextAction();
        if ($method !== null) {
            $this->$method($record);
        }

        $this->relationships->fixForeignRecord($record);
        $this->relationships->persistAfterNative($record, $tracker);
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

    public function turnRowIntoRecord(Row $row, array $eager = []) : Record
    {
        $record = $this->newRecordFromSelectedRow($row);
        $this->relationships->stitchIntoRecords([$record], $eager);
        return $record;
    }

    public function turnRowsIntoRecords(array $rows, array $eager = []) : array
    {
        $records = [];
        foreach ($rows as $row) {
            $records[] = $this->newRecordFromSelectedRow($row);
        }
        $this->relationships->stitchIntoRecords($records, $eager);
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

    protected function newRelated(array $fields = []) : Related
    {
        $related = static::CLASS . 'Related';
        return new $related($fields);
    }

    protected function getRecordClass(Row $row) : string
    {
        return static::CLASS . 'Record';
    }
}
