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
use Atlas\Mapper\RecordSet;
use SplObjectStorage;

class OneToMany extends DeletableRelationship
{
    public function getPersistencePriority() : string
    {
        return static::AFTER_NATIVE;
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

        $foreignRecordHashes = [];
        $foreignMatchColumns = array_values($this->on);
        foreach ($foreignRecords as $foreignRecord) {
            $foreignHash = $this->generateMatchHash($foreignRecord, $foreignMatchColumns);
            if (!isset($foreignRecordHashes[$foreignHash])) {
                $foreignRecordHashes[$foreignHash] = [];
            }
            $foreignRecordHashes[$foreignHash][] = $foreignRecord;
        }

        $nativeMatchColumns = array_keys($this->on);
        foreach ($nativeRecords as $nativeRecord) {
            $nativeHash = $this->generateMatchHash($nativeRecord, $nativeMatchColumns);
            $nativeRecord->{$this->name} = $this->getForeignMapper()
                ->newRecordSet($foreignRecordHashes[$nativeHash] ?? []);
        }
    }

    public function fixForeignRecord(Record $nativeRecord) : void
    {
        $foreignRecordSet = $nativeRecord->{$this->name};
        if (! $foreignRecordSet instanceof RecordSet) {
            return;
        }

        /** @var Record $foreignRecord */
        foreach ($foreignRecordSet as $foreignRecord) {
            foreach ($this->on as $nativeField => $foreignField) {
                $foreignRecord->$foreignField = $nativeRecord->$nativeField;
            }
            $this->fixForeignRecordDeleted($nativeRecord, $foreignRecord);
        }
    }

    public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        $foreignRecordSet = $nativeRecord->{$this->name};
        if (! $foreignRecordSet instanceof RecordSet) {
            return;
        }

        $foreignMapper = $this->getForeignMapper();

        /** @var Record $foreignRecord */
        foreach ($foreignRecordSet as $foreignRecord) {
            $foreignMapper->persist($foreignRecord, $tracker);
        }

        $foreignRecordSet->detachDeleted();
    }
}
