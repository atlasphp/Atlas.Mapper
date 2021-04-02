<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Employee;

use Atlas\Mapper\RecordSet;

/**
 * @method EmployeeRecord offsetGet($offset)
 * @method EmployeeRecord appendNew(array $fields = [])
 * @method EmployeeRecord|null getOneBy(array $whereEquals)
 * @method EmployeeRecordSet getAllBy(array $whereEquals)
 * @method EmployeeRecord|null detachOneBy(array $whereEquals)
 * @method EmployeeRecordSet detachAllBy(array $whereEquals)
 * @method EmployeeRecordSet detachAll()
 * @method EmployeeRecordSet detachDeleted()
 */
class EmployeeRecordSet extends RecordSet
{
}
