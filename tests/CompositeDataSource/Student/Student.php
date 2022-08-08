<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Mapper;

/**
 * @method StudentTable getTable()
 * @method StudentRelationships getRelationships()
 * @method StudentRecord|null fetchRecord($primaryVal, array $with = [])
 * @method StudentRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method StudentRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method StudentRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method StudentRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method StudentRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method StudentSelect select(array $whereEquals = [])
 * @method StudentRecord newRecord(array $fields = [])
 * @method StudentRecord[] newRecords(array $fieldSets)
 * @method StudentRecordSet newRecordSet(array $records = [])
 * @method StudentRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method StudentRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Student extends Mapper
{
}
