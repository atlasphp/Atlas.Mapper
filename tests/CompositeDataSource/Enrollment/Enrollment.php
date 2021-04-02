<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Mapper;

/**
 * @method EnrollmentTable getTable()
 * @method EnrollmentRelationships getRelationships()
 * @method EnrollmentRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method EnrollmentRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method EnrollmentRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method EnrollmentRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method EnrollmentRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method EnrollmentRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method EnrollmentSelect select(array $whereEquals = [])
 * @method EnrollmentRecord newRecord(array $fields = [])
 * @method EnrollmentRecord[] newRecords(array $fieldSets)
 * @method EnrollmentRecordSet newRecordSet(array $records = [])
 * @method EnrollmentRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method EnrollmentRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Enrollment extends Mapper
{
}
