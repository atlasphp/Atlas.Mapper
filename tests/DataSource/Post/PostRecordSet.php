<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\RecordSet;

/**
 * @method PostRecord offsetGet($offset)
 * @method PostRecord appendNew(array $fields = [])
 * @method PostRecord|null getOneBy(array $whereEquals)
 * @method PostRecordSet getAllBy(array $whereEquals)
 * @method PostRecord|null detachOneBy(array $whereEquals)
 * @method PostRecordSet detachAllBy(array $whereEquals)
 * @method PostRecordSet detachAll()
 * @method PostRecordSet detachDeleted()
 */
class PostRecordSet extends RecordSet
{
}
