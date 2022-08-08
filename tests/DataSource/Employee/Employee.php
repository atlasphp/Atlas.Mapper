<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Employee;

use Atlas\Mapper\Mapper;

/**
 * @method EmployeeTable getTable()
 * @method EmployeeRelationships getRelationships()
 * @method EmployeeRecord|null fetchRecord($primaryVal, array $with = [])
 * @method EmployeeRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method EmployeeRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method EmployeeRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method EmployeeRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method EmployeeRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method EmployeeSelect select(array $whereEquals = [])
 * @method EmployeeRecord newRecord(array $fields = [])
 * @method EmployeeRecord[] newRecords(array $fieldSets)
 * @method EmployeeRecordSet newRecordSet(array $records = [])
 * @method EmployeeRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method EmployeeRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Employee extends Mapper
{
}
