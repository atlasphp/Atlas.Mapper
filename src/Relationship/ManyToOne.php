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

use Atlas\Mapper\Record;
use SplObjectStorage;

class ManyToOne extends RegularRelationship
{
    protected function setOn(array $on) : void
    {
        if (! empty($on)) {
            $this->on = $on;
            return;
        }

        $foreignTableClass = substr($this->foreignMapperClass, 0, -6) . 'Table';
        foreach ($foreignTableClass::PRIMARY_KEY as $col) {
            $this->on[$col] = $col;
        }
    }

    protected function stitchIntoRecord(
        Record $nativeRecord,
        array $foreignRecords
    ) : void {
        $nativeRecord->{$this->name} = false;
        foreach ($foreignRecords as $foreignRecord) {
            if ($this->recordsMatch($nativeRecord, $foreignRecord)) {
                $nativeRecord->{$this->name} = $foreignRecord;
            }
        }
    }

    public function fixNativeRecord(Record $nativeRecord) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        foreach ($this->on as $nativeField => $foreignField) {
            $nativeRecord->$nativeField = $foreignRecord->$foreignField;
        }
    }

    public function fixForeignRecord(Record $nativeRecord) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        foreach ($this->on as $nativeField => $foreignField) {
            $foreignRecord->$foreignField = $nativeRecord->$nativeField;
        }
    }

    public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        $this->getForeignMapper()->persist($foreignRecord, $tracker);

        $row = $foreignRecord->getRow();
        if ($row->getStatus() === $row::DELETED) {
            $nativeRecord->{$this->name} = false;
        }
    }
}
