<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use SplObjectStorage;

class OneToMany extends Relationship
{
    protected function stitchIntoRecord(
        Record $nativeRecord,
        array $foreignRecords
    ) : void
    {
        $matches = [];
        foreach ($foreignRecords as $foreignRecord) {
            if ($this->recordsMatch($nativeRecord, $foreignRecord)) {
                $matches[] = $foreignRecord;
            }
        }

        $nativeRecord->{$this->name} = $this->foreignMapper->newRecordSet($matches);
    }

    public function fixForeignRecordKeys(Record $nativeRecord) : void
    {
        $foreignRecordSet = $nativeRecord->{$this->name};
        if (! $foreignRecordSet instanceof RecordSet) {
            return;
        }

        $this->initialize();

        foreach ($foreignRecordSet as $foreignRecord) {
            foreach ($this->getOn() as $nativeField => $foreignField) {
                $foreignRecord->$foreignField = $nativeRecord->$nativeField;
            }
        }
    }

    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        $this->persistForeignRecordSet($nativeRecord, $tracker);
    }
}
