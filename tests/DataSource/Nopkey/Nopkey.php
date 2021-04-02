<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Nopkey;

use Atlas\Mapper\Mapper;
use Atlas\Table\Row;

/**
 * @method NopkeyTable getTable()
 * @method NopkeyRelationships getRelationships()
 * @method NopkeyRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method NopkeyRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method NopkeyRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method NopkeyRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method NopkeyRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method NopkeyRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method NopkeySelect select(array $whereEquals = [])
 * @method NopkeyRecord newRecord(array $fields = [])
 * @method NopkeyRecord[] newRecords(array $fieldSets)
 * @method NopkeyRecordSet newRecordSet(array $records = [])
 * @method NopkeyRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method NopkeyRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Nopkey extends Mapper
{
}
