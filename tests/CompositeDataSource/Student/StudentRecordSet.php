<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\RecordSet;

/**
 * @method StudentRecord offsetGet($offset)
 * @method StudentRecord appendNew(array $fields = [])
 * @method StudentRecord|null getOneBy(array $whereEquals)
 * @method StudentRecordSet getAllBy(array $whereEquals)
 * @method StudentRecord|null detachOneBy(array $whereEquals)
 * @method StudentRecordSet detachAllBy(array $whereEquals)
 * @method StudentRecordSet detachAll()
 * @method StudentRecordSet detachDeleted()
 */
class StudentRecordSet extends RecordSet
{
}
