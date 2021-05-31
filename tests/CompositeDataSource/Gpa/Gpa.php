<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Gpa;

use Atlas\Mapper\Mapper;

/**
 * @method GpaTable getTable()
 * @method GpaRelationships getRelationships()
 * @method GpaRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method GpaRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method GpaRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method GpaRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method GpaRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method GpaRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method GpaSelect select(array $whereEquals = [])
 * @method GpaRecord newRecord(array $fields = [])
 * @method GpaRecord[] newRecords(array $fieldSets)
 * @method GpaRecordSet newRecordSet(array $records = [])
 * @method GpaRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method GpaRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Gpa extends Mapper
{
}
