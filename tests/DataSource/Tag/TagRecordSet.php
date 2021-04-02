<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\RecordSet;

/**
 * @method TagRecord offsetGet($offset)
 * @method TagRecord appendNew(array $fields = [])
 * @method TagRecord|null getOneBy(array $whereEquals)
 * @method TagRecordSet getAllBy(array $whereEquals)
 * @method TagRecord|null detachOneBy(array $whereEquals)
 * @method TagRecordSet detachAllBy(array $whereEquals)
 * @method TagRecordSet detachAll()
 * @method TagRecordSet detachDeleted()
 */
class TagRecordSet extends RecordSet
{
}
