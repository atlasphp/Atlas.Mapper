<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Mapper;

/**
 * @method ThreadTable getTable()
 * @method ThreadRelationships getRelationships()
 * @method ThreadRecord|null fetchRecord($primaryVal, array $with = [])
 * @method ThreadRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method ThreadRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method ThreadRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method ThreadRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method ThreadRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method ThreadSelect select(array $whereEquals = [])
 * @method ThreadRecord newRecord(array $fields = [])
 * @method ThreadRecord[] newRecords(array $fieldSets)
 * @method ThreadRecordSet newRecordSet(array $records = [])
 * @method ThreadRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method ThreadRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Thread extends Mapper
{
}
