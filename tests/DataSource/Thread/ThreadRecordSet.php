<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\RecordSet;

/**
 * @method ThreadRecord offsetGet($offset)
 * @method ThreadRecord appendNew(array $fields = [])
 * @method ThreadRecord|null getOneBy(array $whereEquals)
 * @method ThreadRecordSet getAllBy(array $whereEquals)
 * @method ThreadRecord|null detachOneBy(array $whereEquals)
 * @method ThreadRecordSet detachAllBy(array $whereEquals)
 * @method ThreadRecordSet detachAll()
 * @method ThreadRecordSet detachDeleted()
 */
class ThreadRecordSet extends RecordSet
{
}
