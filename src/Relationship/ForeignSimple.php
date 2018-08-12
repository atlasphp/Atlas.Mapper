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

class ForeignSimple
{
    protected $foreignTableName;

    protected $nativeCol;

    protected $foriegnCol;

    public function __construct(string $foreignTableName, array $on)
    {
        $this->foreignTableName = $foreignTableName;
        $this->nativeCol = key($on);
        $this->foreignCol = current($on);
    }

    public function modifySelect(MapperSelect $select, array $records) : void
    {
        $vals = [];
        foreach ($records as $record) {
            $row = $record->getRow();
            $vals[] = $row->{$this->nativeCol};
        }

        $qftn = $select->quoteIdentifier($this->foreignTableName);
        $qcol = $select->quoteIdentifier($this->foreignCol);
        $where = "{$qftn}.{$qcol} IN ";
        $select->where($where, array_unique($vals));
    }
}
