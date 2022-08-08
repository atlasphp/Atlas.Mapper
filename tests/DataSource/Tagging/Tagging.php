<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Mapper;

/**
 * @method TaggingTable getTable()
 * @method TaggingRelationships getRelationships()
 * @method TaggingRecord|null fetchRecord($primaryVal, array $with = [])
 * @method TaggingRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method TaggingRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method TaggingRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method TaggingRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method TaggingRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method TaggingSelect select(array $whereEquals = [])
 * @method TaggingRecord newRecord(array $fields = [])
 * @method TaggingRecord[] newRecords(array $fieldSets)
 * @method TaggingRecordSet newRecordSet(array $records = [])
 * @method TaggingRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method TaggingRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Tagging extends Mapper
{
}
