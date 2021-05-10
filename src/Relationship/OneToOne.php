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

class OneToOne extends DeletableRelationship
{
    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void
    {
        if (empty($nativeRecords)) {
            return;
        }

        $foreignRecords = $this->fetchForeignRecords($nativeRecords, $custom);

        $foreignRecordHashes = [];
        $foreignMatchColumns = array_values($this->on);
        foreach ($foreignRecords as $foreignRecord) {
            $foreignHash = $this->generateMatchHash($foreignRecord, $foreignMatchColumns);
            if (!isset($foreignRecordHashes[$foreignHash])) {
                $foreignRecordHashes[$foreignHash] = $foreignRecord;
            }
        }

        $nativeMatchColumns = array_keys($this->on);
        foreach ($nativeRecords as $nativeRecord) {
            $nativeHash = $this->generateMatchHash($nativeRecord, $nativeMatchColumns);
            $nativeRecord->{$this->name} = $foreignRecordHashes[$nativeHash] ?? false;
        }
    }

    protected function stitchIntoRecord(
        Record $nativeRecord,
        array &$foreignRecords
    ) : void {
        $nativeRecord->{$this->name} = false;
        foreach ($foreignRecords as $index => $foreignRecord) {
            if ($this->recordsMatch($nativeRecord, $foreignRecord)) {
                $nativeRecord->{$this->name} = $foreignRecord;
                unset($foreignRecords[$index]);
                return;
            }
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

        $this->fixForeignRecordDeleted($nativeRecord, $foreignRecord);
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
