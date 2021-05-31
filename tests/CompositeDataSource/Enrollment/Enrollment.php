<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Mapper;

/**
 * @method EnrollmentTable getTable()
 * @method EnrollmentRelationships getRelationships()
 * @method EnrollmentRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method EnrollmentRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method EnrollmentRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method EnrollmentRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method EnrollmentRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method EnrollmentRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method EnrollmentSelect select(array $whereEquals = [])
 * @method EnrollmentRecord newRecord(array $fields = [])
 * @method EnrollmentRecord[] newRecords(array $fieldSets)
 * @method EnrollmentRecordSet newRecordSet(array $records = [])
 * @method EnrollmentRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method EnrollmentRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Enrollment extends Mapper
{
}
