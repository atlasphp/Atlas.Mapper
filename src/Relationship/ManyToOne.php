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

class ManyToOne extends OneToOne
{
    protected function initializeOn() : void
    {
        foreach ($this->foreignMapper->getTable()::PRIMARY_KEY as $col) {
            $this->on[$col] = $col;
        }
    }

    public function fixNativeRecordKeys(Record $nativeRecord) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        $this->initialize();

        foreach ($this->getOn() as $nativeField => $foreignField) {
            $nativeRecord->$nativeField = $foreignRecord->$foreignField;
        }
    }

    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        $this->persistForeignRecord($nativeRecord, $tracker);
    }
}
