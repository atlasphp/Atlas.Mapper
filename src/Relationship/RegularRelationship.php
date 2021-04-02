<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use SplObjectStorage;

abstract class RegularRelationship extends Relationship
{
    protected string $nativeTableName;

    protected string $foreignTableName;

    protected ForeignSimple|ForeignComposite $foreignKey;

    public function __construct(
        protected string $name,
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass,
        protected string $foreignMapperClass,
        protected array $on = []
    ) {
        if (! class_exists($foreignMapperClass)) {
            throw Exception::classDoesNotExist($foreignMapperClass);
        }

        $foreignTable = $this->foreignMapperClass . 'Table';
        $this->foreignTableName = $foreignTable::NAME;

        $nativeTable = $this->nativeMapperClass . 'Table';
        $this->nativeTableName = $nativeTable::NAME;

        if (empty($this->on)) {
            $this->on = $this->getDefaultOn();
        }

        if (count($this->on) == 1) {
            $this->foreignKey = new ForeignSimple($this->foreignTableName, $this->on);
        } else {
            $this->foreignKey = new ForeignComposite($this->foreignTableName, $this->on);
        }
    }

    public function getForeignMapperClass() : string
    {
        return $this->foreignMapperClass;
    }

    protected function getDefaultOn() : array
    {
        $on = [];
        $nativeTableClass = $this->nativeMapperClass . 'Table';
        foreach ($nativeTableClass::PRIMARY_KEY as $col) {
            $on[$col] = $col;
        }
        return $on;
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void
    {
        if (empty($nativeRecords)) {
            return;
        }

        $foreignRecords = $this->fetchForeignRecords($nativeRecords, $custom);
        foreach ($nativeRecords as $nativeRecord) {
            $this->stitchIntoRecord($nativeRecord, $foreignRecords);
        }
    }

    protected function getForeignMapper() : Mapper
    {
        return $this->mapperLocator->get($this->foreignMapperClass);
    }

    abstract protected function stitchIntoRecord(
        Record $nativeRecord,
        array &$foreignRecords
    ) : void;

    public function joinSelect(
        MapperSelect $select,
        string $join,
        string $nativeAlias,
        string $foreignAlias,
        callable $sub = null
    ) : void
    {
        $spec = $select->quoteIdentifier($this->foreignTableName);
        if ($this->foreignTableName !== $foreignAlias) {
            $spec .= " AS " . $select->quoteIdentifier($foreignAlias);
        }

        $cond = [];
        foreach ($this->on as $nativeCol => $foreignCol) {
            $qna = $select->quoteIdentifier($nativeAlias);
            $qnc = $select->quoteIdentifier($nativeCol);
            $qfa = $select->quoteIdentifier($foreignAlias);
            $qfc = $select->quoteIdentifier($foreignCol);
            $cond[] = "{$qna}.{$qnc} = {$qfa}.{$qfc}";
        }
        $cond = implode(' AND ', $cond);
        $select->join($join, $spec, $cond);

        $this->foreignSelectWhere($select, $foreignAlias);

        if ($sub === null) {
            return;
        }

        // invoke the callable for sub-relateds
        $sub(new SubJoinEager(
            $this->getForeignMapper()->getRelationships(),
            $select,
            $foreignAlias // current "foreign" alias becomes "native" one
        ));
    }

    protected function fetchForeignRecords(array $records, ?callable $custom) : array
    {
        if (! $records) {
            return [];
        }

        $select = $this->getForeignMapper()->select();
        $this->foreignKey->modifySelect($select, $records);
        $this->foreignSelectWhere($select, $this->foreignTableName);

        if ($custom) {
            $custom($select);
        }
        return $select->fetchRecords();
    }

    protected function foreignSelectWhere(MapperSelect $select, string $alias) : void
    {
        $qa = $select->quoteIdentifier($alias);
        foreach ($this->where as $spec) {
            $cond = "{$qa}." . array_shift($spec);
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

    protected function valuesMatch(mixed $nativeVal, mixed $foreignVal) : bool
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
}
