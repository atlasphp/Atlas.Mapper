<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Mapper;

/**
 * @method TagTable getTable()
 * @method TagRelationships getRelationships()
 * @method TagRecord|null fetchRecord($primaryVal, array $with = [])
 * @method TagRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method TagRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method TagRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method TagRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method TagRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method TagSelect select(array $whereEquals = [])
 * @method TagRecord newRecord(array $fields = [])
 * @method TagRecord[] newRecords(array $fieldSets)
 * @method TagRecordSet newRecordSet(array $records = [])
 * @method TagRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method TagRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Tag extends Mapper
{
}
