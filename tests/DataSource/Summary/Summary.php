<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Mapper;

/**
 * @method SummaryTable getTable()
 * @method SummaryRelationships getRelationships()
 * @method SummaryRecord|null fetchRecord($primaryVal, array $with = [])
 * @method SummaryRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method SummaryRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method SummaryRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method SummaryRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method SummaryRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method SummarySelect select(array $whereEquals = [])
 * @method SummaryRecord newRecord(array $fields = [])
 * @method SummaryRecord[] newRecords(array $fieldSets)
 * @method SummaryRecordSet newRecordSet(array $records = [])
 * @method SummaryRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method SummaryRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Summary extends Mapper
{
}
