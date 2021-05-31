<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Mapper;

/**
 * @method TaggingTable getTable()
 * @method TaggingRelationships getRelationships()
 * @method TaggingRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method TaggingRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method TaggingRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method TaggingRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method TaggingRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method TaggingRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method TaggingSelect select(array $whereEquals = [])
 * @method TaggingRecord newRecord(array $fields = [])
 * @method TaggingRecord[] newRecords(array $fieldSets)
 * @method TaggingRecordSet newRecordSet(array $records = [])
 * @method TaggingRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method TaggingRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Tagging extends Mapper
{
}
