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

class OneToOneBidi extends OneToOne
{
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

    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        parent::persistForeign($nativeRecord, $tracker);
        $this->fixNativeRecord($nativeRecord);
        $this->mapperLocator->get($this->nativeMapperClass)->update($nativeRecord);
    }
}
