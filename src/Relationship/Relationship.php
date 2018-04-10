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

use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use SplObjectStorage;

abstract class Relationship
{
    protected $foreignMapperClass;

    protected $ignoreCase = false;

    protected $where = [];

    public function where(string $cond, ...$bind) : Relationship
    {
        $this->where[] = func_get_args();
        return $this;
    }

    public function ignoreCase(bool $ignoreCase = true) : Relationship
    {
        $this->ignoreCase = (bool) $ignoreCase;
        return $this;
    }

    abstract public function joinSelect(string $join, MapperSelect $select) : void;

    abstract public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void;

    public function fixNativeRecordKeys(Record $nativeRecord) : void
    {
        // by default do nothing
    }

    public function fixForeignRecordKeys(Record $nativeRecord) : void
    {
        // by default do nothing
    }

    abstract public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void;
}
