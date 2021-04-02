<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Mapper;

/**
 * @method TagTable getTable()
 * @method TagRelationships getRelationships()
 * @method TagRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method TagRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method TagRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method TagRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method TagRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method TagRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method TagSelect select(array $whereEquals = [])
 * @method TagRecord newRecord(array $fields = [])
 * @method TagRecord[] newRecords(array $fieldSets)
 * @method TagRecordSet newRecordSet(array $records = [])
 * @method TagRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method TagRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Tag extends Mapper
{
}
