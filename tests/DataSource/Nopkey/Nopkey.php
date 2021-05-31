<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Nopkey;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method NopkeyTable getTable()
 * @method NopkeyRelationships getRelationships()
 * @method NopkeyRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method NopkeyRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method NopkeyRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method NopkeyRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method NopkeyRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method NopkeyRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method NopkeySelect select(array $whereEquals = [])
 * @method NopkeyRecord newRecord(array $fields = [])
 * @method NopkeyRecord[] newRecords(array $fieldSets)
 * @method NopkeyRecordSet newRecordSet(array $records = [])
 * @method NopkeyRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method NopkeyRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Nopkey extends Mapper
{
}
