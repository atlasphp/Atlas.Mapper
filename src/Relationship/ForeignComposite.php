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

class ForeignComposite
{
    protected $foreignTableName;

    protected $on;

    public function __construct(string $foreignTableName, array $on)
    {
        $this->foreignTableName = $foreignTableName;
        $this->on = $on;
    }

    public function modifySelect(MapperSelect $select, array $records) : void
    {
        $uniques = $this->getUniqueCompositeKeys($records);
        $all = [];
        foreach ($uniques as $unique) {
            $one = [];
            foreach ($unique as $col => $val) {
                $qftn = $select->quoteIdentifier($this->foreignTableName);
                $qcol = $select->quoteIdentifier($col);
                $one[] = "{$qftn}.{$qcol} = " . $select->bindInline($val);
            }
            $all[] = '(' . implode(' AND ', $one) . ')';
        }

        $cond = '( -- composite keys' . PHP_EOL . '    '
            . implode(PHP_EOL . '    OR ', $all)
            . PHP_EOL . ')';

        $select->where($cond);
    }

    protected function getUniqueCompositeKeys(array $records) : array
    {
        $uniques = [];
        foreach ($records as $record) {
            $row = $record->getRow();
            $vals = [];
            foreach ($this->on as $nativeCol => $foreignCol) {
                $vals[$nativeCol] = $row->$nativeCol;
            }
            // a pipe, and ASCII 31 ("unit separator").
            // identical composite values should have identical array keys,
            // which means this will automatically unique them.
            $key = implode("|\x1F", $vals);
            $uniques[$key] = $vals;
        }
        return $uniques;
    }
}
