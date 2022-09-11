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

    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void
    {
    }

    public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
    }
}
