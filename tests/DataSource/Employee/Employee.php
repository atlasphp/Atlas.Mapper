<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Employee;

use Atlas\Mapper\Mapper;

/**
 * @method EmployeeTable getTable()
 * @method EmployeeRelationships getRelationships()
 * @method EmployeeRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method EmployeeRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method EmployeeRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method EmployeeRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method EmployeeRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method EmployeeRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method EmployeeSelect select(array $whereEquals = [])
 * @method EmployeeRecord newRecord(array $fields = [])
 * @method EmployeeRecord[] newRecords(array $fieldSets)
 * @method EmployeeRecordSet newRecordSet(array $records = [])
 * @method EmployeeRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method EmployeeRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Employee extends Mapper
{
}
