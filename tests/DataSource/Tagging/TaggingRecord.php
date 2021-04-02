<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Record;

/**
 * @method TaggingRow getRow()
 */
class TaggingRecord extends Record
{
    use TaggingFields;
}
