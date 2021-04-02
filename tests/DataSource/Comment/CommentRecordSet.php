<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\RecordSet;

/**
 * @method CommentRecord offsetGet($offset)
 * @method CommentRecord appendNew(array $fields = [])
 * @method CommentRecord|null getOneBy(array $whereEquals)
 * @method CommentRecordSet getAllBy(array $whereEquals)
 * @method CommentRecord|null detachOneBy(array $whereEquals)
 * @method CommentRecordSet detachAllBy(array $whereEquals)
 * @method CommentRecordSet detachAll()
 * @method CommentRecordSet detachDeleted()
 */
class CommentRecordSet extends RecordSet
{
}
