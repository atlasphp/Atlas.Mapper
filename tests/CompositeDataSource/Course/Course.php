<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Mapper;

/**
 * @method CourseTable getTable()
 * @method CourseRelationships getRelationships()
 * @method CourseRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method CourseRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method CourseRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method CourseRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method CourseRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method CourseRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method CourseSelect select(array $whereEquals = [])
 * @method CourseRecord newRecord(array $fields = [])
 * @method CourseRecord[] newRecords(array $fieldSets)
 * @method CourseRecordSet newRecordSet(array $records = [])
 * @method CourseRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method CourseRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Course extends Mapper
{
}
