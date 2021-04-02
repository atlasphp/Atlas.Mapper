<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Record;

/**
 * @method ReplyRow getRow()
 */
class ReplyRecord extends Record
{
    use ReplyFields;
}
