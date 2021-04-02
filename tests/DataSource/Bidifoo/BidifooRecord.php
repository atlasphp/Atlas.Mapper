<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Record;

/**
 * @method BidifooRow getRow()
 */
class BidifooRecord extends Record
{
    use BidifooFields;
}
