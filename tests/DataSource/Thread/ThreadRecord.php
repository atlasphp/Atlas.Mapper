<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Record;

/**
 * @method ThreadRow getRow()
 */
class ThreadRecord extends Record
{
    use ThreadFields;
}
