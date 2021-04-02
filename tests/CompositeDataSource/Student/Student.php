<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Mapper;

/**
 * @method StudentTable getTable()
 * @method StudentRelationships getRelationships()
 * @method StudentRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method StudentRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method StudentRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method StudentRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method StudentRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method StudentRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method StudentSelect select(array $whereEquals = [])
 * @method StudentRecord newRecord(array $fields = [])
 * @method StudentRecord[] newRecords(array $fieldSets)
 * @method StudentRecordSet newRecordSet(array $records = [])
 * @method StudentRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method StudentRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Student extends Mapper
{
}
