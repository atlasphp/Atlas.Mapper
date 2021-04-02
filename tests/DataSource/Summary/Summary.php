<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Mapper;

/**
 * @method SummaryTable getTable()
 * @method SummaryRelationships getRelationships()
 * @method SummaryRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method SummaryRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method SummaryRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method SummaryRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method SummaryRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method SummaryRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method SummarySelect select(array $whereEquals = [])
 * @method SummaryRecord newRecord(array $fields = [])
 * @method SummaryRecord[] newRecords(array $fieldSets)
 * @method SummaryRecordSet newRecordSet(array $records = [])
 * @method SummaryRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method SummaryRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Summary extends Mapper
{
}
