<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Record;

/**
 * @method TagRow getRow()
 */
class TagRecord extends Record
{
    use TagFields;
}
