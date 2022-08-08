<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Record;

/**
 * @method CommentRow getRow()
 */
class CommentRecord extends Record
{
    use CommentFields;
}
