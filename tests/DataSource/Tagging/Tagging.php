<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Mapper;

/**
 * @method TaggingTable getTable()
 * @method TaggingRelationships getRelationships()
 * @method TaggingRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method TaggingRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method TaggingRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method TaggingRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method TaggingRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method TaggingRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method TaggingSelect select(array $whereEquals = [])
 * @method TaggingRecord newRecord(array $fields = [])
 * @method TaggingRecord[] newRecords(array $fieldSets)
 * @method TaggingRecordSet newRecordSet(array $records = [])
 * @method TaggingRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method TaggingRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Tagging extends Mapper
{
}
