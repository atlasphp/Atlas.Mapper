<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Mapper;

/**
 * @method CourseTable getTable()
 * @method CourseRelationships getRelationships()
 * @method CourseRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method CourseRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method CourseRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method CourseRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method CourseRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method CourseRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method CourseSelect select(array $whereEquals = [])
 * @method CourseRecord newRecord(array $fields = [])
 * @method CourseRecord[] newRecords(array $fieldSets)
 * @method CourseRecordSet newRecordSet(array $records = [])
 * @method CourseRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method CourseRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Course extends Mapper
{
}
