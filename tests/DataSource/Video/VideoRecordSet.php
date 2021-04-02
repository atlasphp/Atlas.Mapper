<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\RecordSet;

/**
 * @method VideoRecord offsetGet($offset)
 * @method VideoRecord appendNew(array $fields = [])
 * @method VideoRecord|null getOneBy(array $whereEquals)
 * @method VideoRecordSet getAllBy(array $whereEquals)
 * @method VideoRecord|null detachOneBy(array $whereEquals)
 * @method VideoRecordSet detachAllBy(array $whereEquals)
 * @method VideoRecordSet detachAll()
 * @method VideoRecordSet detachDeleted()
 */
class VideoRecordSet extends RecordSet
{
}
