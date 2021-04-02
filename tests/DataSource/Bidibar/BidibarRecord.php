<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Record;

/**
 * @method BidibarRow getRow()
 */
class BidibarRecord extends Record
{
    use BidibarFields;
}
