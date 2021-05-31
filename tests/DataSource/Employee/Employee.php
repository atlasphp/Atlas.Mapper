<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Employee;

use Atlas\Mapper\Mapper;

/**
 * @method EmployeeTable getTable()
 * @method EmployeeRelationships getRelationships()
 * @method EmployeeRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method EmployeeRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method EmployeeRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method EmployeeRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method EmployeeRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method EmployeeRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method EmployeeSelect select(array $whereEquals = [])
 * @method EmployeeRecord newRecord(array $fields = [])
 * @method EmployeeRecord[] newRecords(array $fieldSets)
 * @method EmployeeRecordSet newRecordSet(array $records = [])
 * @method EmployeeRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method EmployeeRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Employee extends Mapper
{
}
