<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Mapper;

/**
 * @method ThreadTable getTable()
 * @method ThreadRelationships getRelationships()
 * @method ThreadRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method ThreadRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method ThreadRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method ThreadRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method ThreadRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method ThreadRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method ThreadSelect select(array $whereEquals = [])
 * @method ThreadRecord newRecord(array $fields = [])
 * @method ThreadRecord[] newRecords(array $fieldSets)
 * @method ThreadRecordSet newRecordSet(array $records = [])
 * @method ThreadRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method ThreadRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Thread extends Mapper
{
}
