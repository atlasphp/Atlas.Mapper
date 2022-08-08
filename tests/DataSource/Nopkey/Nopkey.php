<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Nopkey;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method NopkeyTable getTable()
 * @method NopkeyRelationships getRelationships()
 * @method NopkeyRecord|null fetchRecord($primaryVal, array $with = [])
 * @method NopkeyRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method NopkeyRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method NopkeyRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method NopkeyRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method NopkeyRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method NopkeySelect select(array $whereEquals = [])
 * @method NopkeyRecord newRecord(array $fields = [])
 * @method NopkeyRecord[] newRecords(array $fieldSets)
 * @method NopkeyRecordSet newRecordSet(array $records = [])
 * @method NopkeyRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method NopkeyRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Nopkey extends Mapper
{
}
