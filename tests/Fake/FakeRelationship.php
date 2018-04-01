<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Record;
use Atlas\Mapper\Relationship\Relationship;
use SplObjectStorage;

class FakeRelationship extends Relationship
{
    public function __construct()
    {
    }

    public function __call($func, $args)
    {
        return $this->$func(...$args);
    }

    protected function stitchIntoRecord(
        Record $nativeRecord,
        array $foreignRecords
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
