<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\RecordSet;

/**
 * @method ReplyRecord offsetGet($offset)
 * @method ReplyRecord appendNew(array $fields = [])
 * @method ReplyRecord|null getOneBy(array $whereEquals)
 * @method ReplyRecordSet getAllBy(array $whereEquals)
 * @method ReplyRecord|null detachOneBy(array $whereEquals)
 * @method ReplyRecordSet detachAllBy(array $whereEquals)
 * @method ReplyRecordSet detachAll()
 * @method ReplyRecordSet detachDeleted()
 */
class ReplyRecordSet extends RecordSet
{
}
