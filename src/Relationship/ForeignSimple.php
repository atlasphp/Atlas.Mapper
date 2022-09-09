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

use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;

class ForeignSimple
{
    protected string $nativeCol;

    protected string $foreignCol;

    public function __construct(
        protected string $foreignTableName,
        array $on
    ) {
        $this->nativeCol = (string) key($on);
        $this->foreignCol = (string) current($on);
    }

    /**
     * Given an array of native records, select foreign rows related to those
     * records.
     *
     * @param Record[] $records
     */
    public function modifySelect(MapperSelect $select, array $records) : void
    {
        $vals = [];
        foreach ($records as $record) {
            $vals[] = $record->{$this->nativeCol};
        }

        $qftn = $select->quoteIdentifier($this->foreignTableName);
        $qcol = $select->quoteIdentifier($this->foreignCol);
        $where = "{$qftn}.{$qcol} IN ";
        $select->where($where, array_unique($vals));
    }
}
