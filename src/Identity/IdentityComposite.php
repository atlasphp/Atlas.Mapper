<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Identity;

use Atlas\Mapper\Exception;
use Atlas\Table\Row;
use SplObjectStorage;

class IdentityComposite extends IdentityMap
{
    public function __construct(array $primaryKey)
    {
        $this->primaryKey = $primaryKey;
        $this->rowToSerial = new SplObjectStorage();
    }

    protected function getArrayFromRow(Row $row) : array
    {
        $identity = [];
        foreach ($this->primaryKey as $col) {
            $identity[$col] = $row->{$col};
        }
        return $identity;
    }

    protected function getArray(array $primaryVal) : array
    {
        $identity = [];

        foreach ($this->primaryKey as $col) {
            if (! isset($primaryVal[$col])) {
                throw Exception::primaryValueMissing($col);
            }
            if (! is_scalar($primaryVal[$col])) {
                throw Exception::primaryValueNotScalar($col, $primaryVal[$col]);
            }
            $identity[$col] = $primaryVal[$col];
        }

        return $identity;
    }
}
