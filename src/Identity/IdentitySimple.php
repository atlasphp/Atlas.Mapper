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

class IdentitySimple extends IdentityMap
{
    protected string $primaryKey;

    public function __construct(array $primaryKey)
    {
        $this->primaryKey = reset($primaryKey);
        $this->rowToSerial = new SplObjectStorage();
    }

    protected function getArrayFromRow(Row $row) : array
    {
        return [$row->{$this->primaryKey}];
    }

    protected function getArray(mixed $primaryVal) : array
    {
        if (! is_scalar($primaryVal)) {
            throw Exception::primaryValueNotScalar($this->primaryKey, $primaryVal);
        }

        return [$primaryVal];
    }
}
