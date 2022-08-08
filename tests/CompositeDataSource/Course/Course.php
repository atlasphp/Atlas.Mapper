<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Mapper;

/**
 * @method CourseTable getTable()
 * @method CourseRelationships getRelationships()
 * @method CourseRecord|null fetchRecord($primaryVal, array $with = [])
 * @method CourseRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method CourseRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method CourseRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method CourseRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method CourseRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method CourseSelect select(array $whereEquals = [])
 * @method CourseRecord newRecord(array $fields = [])
 * @method CourseRecord[] newRecords(array $fieldSets)
 * @method CourseRecordSet newRecordSet(array $records = [])
 * @method CourseRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method CourseRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Course extends Mapper
{
}
