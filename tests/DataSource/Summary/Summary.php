<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Mapper;

/**
 * @method SummaryTable getTable()
 * @method SummaryRelationships getRelationships()
 * @method SummaryRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method SummaryRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method SummaryRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method SummaryRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method SummaryRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method SummaryRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method SummarySelect select(array $whereEquals = [])
 * @method SummaryRecord newRecord(array $fields = [])
 * @method SummaryRecord[] newRecords(array $fieldSets)
 * @method SummaryRecordSet newRecordSet(array $records = [])
 * @method SummaryRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method SummaryRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Summary extends Mapper
{
}
