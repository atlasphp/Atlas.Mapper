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

    public function where(string $condition, ...$bindInline) : Relationship
    {
        $this->where[] = func_get_args();
        return $this;
    }

    public function ignoreCase(bool $ignoreCase = true) : Relationship
    {
        $this->ignoreCase = (bool) $ignoreCase;
        return $this;
    }

    abstract public function joinSelect(
        MapperSelect $select,
        string $join,
        string $nativeAlias,
        string $foreignAlias,
        callable $sub = null
    ) : void;

    abstract public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void;

    public function fixNativeRecord(Record $nativeRecord) : void
    {
        // by default do nothing
    }

    public function fixForeignRecord(Record $nativeRecord) : void
    {
        // by default do nothing
    }

    abstract public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void;
}
