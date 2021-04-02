<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Gpa;

use Atlas\Mapper\RecordSet;

/**
 * @method GpaRecord offsetGet($offset)
 * @method GpaRecord appendNew(array $fields = [])
 * @method GpaRecord|null getOneBy(array $whereEquals)
 * @method GpaRecordSet getAllBy(array $whereEquals)
 * @method GpaRecord|null detachOneBy(array $whereEquals)
 * @method GpaRecordSet detachAllBy(array $whereEquals)
 * @method GpaRecordSet detachAll()
 * @method GpaRecordSet detachDeleted()
 */
class GpaRecordSet extends RecordSet
{
}
