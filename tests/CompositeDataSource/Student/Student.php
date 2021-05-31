<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Mapper;

/**
 * @method StudentTable getTable()
 * @method StudentRelationships getRelationships()
 * @method StudentRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method StudentRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method StudentRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method StudentRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method StudentRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method StudentRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method StudentSelect select(array $whereEquals = [])
 * @method StudentRecord newRecord(array $fields = [])
 * @method StudentRecord[] newRecords(array $fieldSets)
 * @method StudentRecordSet newRecordSet(array $records = [])
 * @method StudentRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method StudentRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Student extends Mapper
{
}
