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

abstract class IdentityMap
{
    protected $primaryKey;

    protected $serialToRow = [];

    protected $rowToSerial;

    public function setRow(Row $row) : void
    {
        if ($this->hasRow($row)) {
            throw Exception::rowAlreadyMapped($row);
        }

        $serial = $this->getSerial($row);
        $this->serialToRow[$serial] = $row;
        $this->rowToSerial[$row] = $serial;
    }

    public function hasRow(Row $row) : bool
    {
        return isset($this->rowToSerial[$row]);
    }

    public function getRow(string $serial) : ?Row
    {
        if (! isset($this->serialToRow[$serial])) {
            return null;
        }

        return $this->serialToRow[$serial];
    }

    public function setOrGetRow(Row $row) : Row
    {
        $serial = $this->getSerial($row);
        $memory = $this->getRow($serial);

        if ($memory === null) {
            $this->setRow($row);
            return $row;
        }

        return $memory;
    }

    /**
     *
     * This is a ghetto hack to serialize an identity array to a string, so it
     * can be used for array key lookups.
     *
     * All it does it implode() the identity array values with a pipe (to make
     * it easier for people to see the separator) and an ASCII "unit separator"
     * character (to include something that is unlikely to be used in a real
     * primary-key value, and thus help prevent the serial string from being
     * subverted).
     *
     * WARNING: You should sanitize your primary-key values to disallow ASCII
     * character 31 (hex 1F) to keep the lookup working properly. This is only
     * a problem with non-integer keys.
     *
     * WARNING: Null, false, and empty-string key values are treated as
     * identical by this algorithm. That means these values are interchangeable
     * and are not differentiated. You should sanitize your primary-key values
     * to disallow null, false, and empty-string values. This is only a problem
     * with non-integer keys.
     *
     * WARNING: The serial string version of the primary key depends on the
     * values always being in the same order. E.g., `['foo' => 1, 'bar' => 2]`
     * will result in a different serial than `['bar' => 2, 'foo' => 1]`, even
     * though the key-value pairs themselves are the same.
     *
     */
    public function getSerial($spec) : string
    {
        if ($spec instanceof Row) {
            $array = $this->getArrayFromRow($spec);
        } else {
            $array = $this->getArray($spec);
        }

        $sep = "|\x1F"; // a pipe, and ASCII 31 ("unit separator")
        return $sep . implode($sep, $array). $sep;
    }
}
