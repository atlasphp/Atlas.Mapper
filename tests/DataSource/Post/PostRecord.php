<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Record;

/**
 * @method PostRow getRow()
 */
class PostRecord extends Record
{
    use PostFields;
}
