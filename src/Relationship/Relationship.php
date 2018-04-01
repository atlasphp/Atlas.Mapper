<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use SplObjectStorage;

abstract class Relationship
{
    protected $mapperLocator;

    protected $name;

    protected $nativeMapperClass;

    protected $nativeMapper;

    protected $foreignMapperClass;

    protected $foreignMapper;

    protected $foreignTableName;

    protected $on = [];

    protected $ignoreCase = false;

    protected $where = [];

    protected $initialized = false;

    public function __construct(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        string $foreignMapperClass,
        array $on = []
    ) {
        if (! class_exists($foreignMapperClass)) {
            throw Exception::classDoesNotExist($foreignMapperClass);
        }

        $this->name = $name;
        $this->mapperLocator = $mapperLocator;
        $this->nativeMapperClass = $nativeMapperClass;
        $this->foreignMapperClass = $foreignMapperClass;
        $this->on = $on;
    }

    public function getSettings() : array
    {
        $this->initialize();
        $settings = get_object_vars($this);
        unset($settings['initialized']);
        unset($settings['mapperLocator']);
        unset($settings['nativeMapper']);
        unset($settings['foreignMapper']);
        return $settings;
    }

    public function where(string $cond, ...$bind) : Relationship
    {
        $this->where[] = func_get_args();
        return $this;
    }

    public function ignoreCase(bool $ignoreCase = true) : self
    {
        $this->ignoreCase = (bool) $ignoreCase;
        return $this;
    }

    public function getOn() : array
    {
        $this->initialize();
        return $this->on;
    }

    public function getForeignMapper() : Mapper
    {
        $this->initialize();
        return $this->foreignMapper;
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void {
        if (! $nativeRecords) {
            return;
        }

        $this->initialize();

        $foreignRecords = $this->fetchForeignRecords($nativeRecords, $custom);
        foreach ($nativeRecords as $nativeRecord) {
            $this->stitchIntoRecord($nativeRecord, $foreignRecords);
        }
    }

    public function joinSelect($join, MapperSelect $select) : void
    {
        $this->initialize();

        $nativeTable = $this->nativeMapper->getTable()::NAME;
        $foreignTable = $this->foreignMapper->getTable()::NAME;
        $spec = "{$foreignTable} AS {$this->name}";

        $cond = [];
        foreach ($this->on as $nativeCol => $foreignCol) {
            $cond[] = "{$nativeTable}.{$nativeCol} = {$this->name}.{$foreignCol}";
        }
        $cond = implode(' AND ', $cond);
        $select->join($join, $spec, $cond);

        $this->foreignSelectWhere($select, $this->name);
    }

    protected function initialize() : void
    {
        if ($this->initialized) {
            return;
        }

        $this->nativeMapper = $this->mapperLocator->get($this->nativeMapperClass);
        $this->foreignMapper = $this->mapperLocator->get($this->foreignMapperClass);
        $this->foreignTableName = $this->foreignMapper->getTable()::NAME;

        if (! $this->on) {
            $this->initializeOn();
        }

        $this->initialized = true;
    }

    protected function initializeOn() : void
    {
        foreach ($this->nativeMapper->getTable()::PRIMARY_KEY as $col) {
            $this->on[$col] = $col;
        }
    }

    protected function fetchForeignRecords(array $records, $custom) : array
    {
        if (! $records) {
            return [];
        }

        $select = $this->foreignSelect($records);
        if ($custom) {
            $custom($select);
        }
        return $select->fetchRecords();
    }

    protected function foreignSelect(array $records) : MapperSelect
    {
        $select = $this->foreignMapper->select();

        if (count($this->on) > 1) {
            $this->foreignSelectComposite($select, $records);
        } else {
            $this->foreignSelectSimple($select, $records);
        }

        // add relationship-specific WHERE conditions
        $this->foreignSelectWhere($select, $this->foreignTableName);
        return $select;
    }

    protected function foreignSelectSimple(MapperSelect $select, array $records) : void
    {
        $vals = [];
        reset($this->on);
        $nativeCol = key($this->on);
        foreach ($records as $record) {
            $row = $record->getRow();
            $vals[] = $row->$nativeCol;
        }

        $foreignCol = current($this->on);
        $where = "{$this->foreignTableName}.{$foreignCol} IN ";
        $select->where($where, array_unique($vals));
    }

    protected function foreignSelectComposite(MapperSelect $select, array $records) : void
    {
        $uniques = $this->getUniqueCompositeKeys($records);
        $all = [];
        foreach ($uniques as $unique) {
            $one = [];
            foreach ($unique as $col => $val) {
                $one[] = "{$col} = " . $select->bindInline($val);
            }
            $all[] = '(' . implode(' AND ', $one) . ')';
        }

        $cond = '( -- composite keys' . PHP_EOL . '    '
            . implode(PHP_EOL . '    OR ', $all)
            . PHP_EOL . ')';

        $select->where($cond);
    }

    protected function getUniqueCompositeKeys(array $records) : array
    {
        $uniques = [];
        foreach ($records as $record) {
            $row = $record->getRow();
            $vals = [];
            foreach ($this->on as $nativeCol => $foreignCol) {
                $vals[$nativeCol] = $row->$nativeCol;
            }
            // a pipe, and ASCII 31 ("unit separator").
            // identical composite values should have identical array keys.
            $key = implode("|\x1F", $vals);
            $uniques[$key] = $vals;
        }
        return $uniques;
    }

    protected function foreignSelectWhere(MapperSelect $select, $alias) : void
    {
        foreach ($this->where as $spec) {
            $cond = "{$alias}." . array_shift($spec);
            $select->where($cond, ...$spec);
        }
    }

    protected function recordsMatch(
        Record $nativeRecord,
        Record $foreignRecord
    ) : bool {
        $nativeRow = $nativeRecord->getRow();
        $foreignRow = $foreignRecord->getRow();
        foreach ($this->on as $nativeCol => $foreignCol) {
            if (! $this->valuesMatch(
                $nativeRow->$nativeCol,
                $foreignRow->$foreignCol
            )) {
                return false;
            }
        }
        return true;
    }

    protected function valuesMatch($nativeVal, $foreignVal) : bool
    {
        // cannot match if one is numeric and other is not
        if (is_numeric($nativeVal) && ! is_numeric($foreignVal)) {
            return false;
        }

        // ignore string case?
        if ($this->ignoreCase) {
            $nativeVal = strtolower($nativeVal);
            $foreignVal = strtolower($foreignVal);
        }

        // are they equal?
        return $nativeVal == $foreignVal;
    }

    abstract protected function stitchIntoRecord(
        Record $nativeRecord,
        array $foreignRecords
    ) : void;

    public function fixNativeRecordKeys(Record $nativeRecord) : void
    {
        // by default do nothing
    }

    public function fixForeignRecordKeys(Record $nativeRecord) : void
    {
        // by default do nothing
    }

    abstract public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void;

    protected function persistForeignRecord(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        $this->initialize();

        $this->foreignMapper->persist($foreignRecord, $tracker);
    }

    protected function persistForeignRecordSet(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        $foreignRecordSet = $nativeRecord->{$this->name};
        if (! $foreignRecordSet instanceof RecordSet) {
            return;
        }

        $this->initialize();

        foreach ($foreignRecordSet as $foreignRecord) {
            $this->foreignMapper->persist($foreignRecord, $tracker);
        }
    }
}
