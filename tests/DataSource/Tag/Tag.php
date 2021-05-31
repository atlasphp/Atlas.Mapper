<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Mapper;

/**
 * @method TagTable getTable()
 * @method TagRelationships getRelationships()
 * @method TagRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method TagRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method TagRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method TagRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method TagRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method TagRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method TagSelect select(array $whereEquals = [])
 * @method TagRecord newRecord(array $fields = [])
 * @method TagRecord[] newRecords(array $fieldSets)
 * @method TagRecordSet newRecordSet(array $records = [])
 * @method TagRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method TagRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Tag extends Mapper
{
}
