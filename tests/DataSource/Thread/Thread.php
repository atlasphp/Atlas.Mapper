<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Mapper;

/**
 * @method ThreadTable getTable()
 * @method ThreadRelationships getRelationships()
 * @method ThreadRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method ThreadRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method ThreadRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method ThreadRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method ThreadRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method ThreadRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method ThreadSelect select(array $whereEquals = [])
 * @method ThreadRecord newRecord(array $fields = [])
 * @method ThreadRecord[] newRecords(array $fieldSets)
 * @method ThreadRecordSet newRecordSet(array $records = [])
 * @method ThreadRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method ThreadRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Thread extends Mapper
{
}
