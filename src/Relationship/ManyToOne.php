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

    public function fixNativeRecordKeys(Record $nativeRecord) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        foreach ($this->on as $nativeField => $foreignField) {
            $nativeRecord->$nativeField = $foreignRecord->$foreignField;
        }
    }
}
