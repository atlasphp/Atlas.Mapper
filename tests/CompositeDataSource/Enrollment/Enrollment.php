<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Mapper;

/**
 * @method EnrollmentTable getTable()
 * @method EnrollmentRelationships getRelationships()
 * @method EnrollmentRecord|null fetchRecord($primaryVal, array $with = [])
 * @method EnrollmentRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method EnrollmentRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method EnrollmentRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method EnrollmentRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method EnrollmentRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method EnrollmentSelect select(array $whereEquals = [])
 * @method EnrollmentRecord newRecord(array $fields = [])
 * @method EnrollmentRecord[] newRecords(array $fieldSets)
 * @method EnrollmentRecordSet newRecordSet(array $records = [])
 * @method EnrollmentRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method EnrollmentRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Enrollment extends Mapper
{
}
