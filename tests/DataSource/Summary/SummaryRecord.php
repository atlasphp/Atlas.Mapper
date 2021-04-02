<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Record;

/**
 * @method SummaryRow getRow()
 */
class SummaryRecord extends Record
{
    use SummaryFields;
}
