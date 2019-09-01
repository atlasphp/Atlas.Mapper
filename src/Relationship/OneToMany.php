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
    protected function stitchIntoRecord(
        Record $nativeRecord,
        array &$foreignRecords
    ) : void
    {
        $matches = [];
        foreach ($foreignRecords as $index => $foreignRecord) {
            if ($this->recordsMatch($nativeRecord, $foreignRecord)) {
                $matches[] = $foreignRecord;
                unset($foreignRecords[$index]);
            }
        }

        $nativeRecord->{$this->name} = $this->getForeignMapper()->newRecordSet($matches);
    }

    public function fixForeignRecord(Record $nativeRecord) : void
    {
        $foreignRecordSet = $nativeRecord->{$this->name};
        if (! $foreignRecordSet instanceof RecordSet) {
            return;
        }

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
        foreach ($foreignRecordSet as $foreignRecord) {
            $foreignMapper->persist($foreignRecord, $tracker);
        }
    }
}
