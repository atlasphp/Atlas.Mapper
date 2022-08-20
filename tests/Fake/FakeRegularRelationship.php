<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Record;
use Atlas\Mapper\Relationship\RegularRelationship;
use SplObjectStorage;

class FakeRegularRelationship extends RegularRelationship
{
    public function __call($func, $args)
    {
        return $this->$func(...$args);
    }

    public function getPersistencePriority() : string
    {
        return static::BEFORE_NATIVE;
    }

    protected function stitchIntoRecord(
        Record $nativeRecord,
        array &$foreignRecords
    ) : void {
        return;
    }

    public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void {
        return;
    }
}
