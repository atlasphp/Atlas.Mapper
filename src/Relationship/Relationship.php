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
    protected bool $ignoreCase = false;

    protected array $where = [];

    public function where(string $condition, mixed ...$bindInline) : void
    {
        $this->where[] = func_get_args();
    }

    public function ignoreCase(bool $ignoreCase = true) : void
    {
        $this->ignoreCase = (bool) $ignoreCase;
    }

    abstract public function getOn() : array;

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

    abstract public function getPersistOrder() : string;

    abstract public function persistForeign(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void;
}
