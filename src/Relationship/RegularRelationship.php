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

use Atlas\Mapper\Define\RelationshipAttribute;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use ReflectionNamedType;
use ReflectionProperty;
use SplObjectStorage;

abstract class RegularRelationship extends Relationship
{
    protected array $on = [];

    protected string $nativeTableName;

    protected string $foreignTableName;

    protected ForeignSimple|ForeignComposite $foreignStrategy;

    public function __construct(
        protected string $name,
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass,
        string $foreignMapperClass,
        RelationshipAttribute $attribute,
        /* RelationshipLocator $relationshipLocator, */
    ) {
        if (! class_exists($foreignMapperClass)) {
            throw Exception::classDoesNotExist($foreignMapperClass);
        }

        $this->foreignMapperClass = $foreignMapperClass;

        $foreignTable = $this->foreignMapperClass . 'Table';
        $this->foreignTableName = $foreignTable::NAME;

        $nativeTable = $this->nativeMapperClass . 'Table';
        $this->nativeTableName = $nativeTable::NAME;

        $this->on = $attribute->on;

        if (empty($this->on)) {
            $this->on = $this->getDefaultOn();
        }

        if (count($this->on) == 1) {
            $this->foreignStrategy = new ForeignSimple($this->foreignTableName, $this->on);
        } else {
            $this->foreignStrategy = new ForeignComposite($this->foreignTableName, $this->on);
        }
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
        array $more = []
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

        if (empty($more)) {
            return;
        }

        foreach ($more as $relatedSpec => $relatedMore) {
            if (is_int($relatedSpec)) {
                $relatedSpec = $relatedMore;
                $relatedMore = [];
            }

            $foreignRelationshipLocator = $this->getForeignMapper()->getRelationshipLocator();
            list($relatedName, $join, $nextForeignAlias) = $foreignRelationshipLocator->listRelatedSpec($relatedSpec);

            $foreignRelationshipLocator->get($relatedName)->joinSelect(
                $select,
                $join,
                $foreignAlias, // current "foreign" alias becomes "native" one
                $nextForeignAlias,
                $relatedMore
            );
        }
    }

    protected function fetchForeignRecords(array $records, ?callable $custom) : array
    {
        if (! $records) {
            return [];
        }

        $select = $this->getForeignMapper()->select();
        $this->foreignStrategy->modifySelect($select, $records);
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

    protected function generateMatchHash(Record $record, array $colNames): string
    {
        $row = $record->getRow();
        $array = [];

        foreach ($colNames as $col) {
            $array[] = $row->$col;
        }

        $sep = "|\x1F"; // a pipe, and ASCII 31 ("unit separator")
        return $sep . implode($sep, $array). $sep;
    }
}
