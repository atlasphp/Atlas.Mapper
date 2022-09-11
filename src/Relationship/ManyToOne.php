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
    public function getPersistencePriority() : string
    {
        return static::BEFORE_NATIVE;
    }

    protected function getDefaultOn() : array
    {
        $on = [];
        $foreignTableClass = $this->foreignMapperClass . 'Table';
        foreach ($foreignTableClass::PRIMARY_KEY as $col) {
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

    protected function stitchIntoRecord(
        Record $nativeRecord,
        array &$foreignRecords
    ) : void
    {
        foreach ($foreignRecords as $foreignRecord) {
            if ($this->recordsMatch($nativeRecord, $foreignRecord)) {
                $nativeRecord->{$this->name} = $foreignRecord;
                return;
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
    }
}
