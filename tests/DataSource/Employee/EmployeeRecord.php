<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Employee;

use Atlas\Mapper\Record;

/**
 * @method EmployeeRow getRow()
 */
class EmployeeRecord extends Record
{
    use EmployeeFields;
}
