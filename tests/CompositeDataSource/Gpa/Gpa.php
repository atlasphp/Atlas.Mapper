<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Gpa;

use Atlas\Mapper\Mapper;

/**
 * @method GpaTable getTable()
 * @method GpaRelationships getRelationships()
 * @method GpaRecord|null fetchRecord($primaryVal, array $with = [])
 * @method GpaRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method GpaRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method GpaRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method GpaRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method GpaRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method GpaSelect select(array $whereEquals = [])
 * @method GpaRecord newRecord(array $fields = [])
 * @method GpaRecord[] newRecords(array $fieldSets)
 * @method GpaRecordSet newRecordSet(array $records = [])
 * @method GpaRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method GpaRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Gpa extends Mapper
{
}
