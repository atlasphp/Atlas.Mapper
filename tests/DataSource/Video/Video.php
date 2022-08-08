<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\Mapper;

/**
 * @method VideoTable getTable()
 * @method VideoRelationships getRelationships()
 * @method VideoRecord|null fetchRecord($primaryVal, array $with = [])
 * @method VideoRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method VideoRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method VideoRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method VideoRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method VideoRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method VideoSelect select(array $whereEquals = [])
 * @method VideoRecord newRecord(array $fields = [])
 * @method VideoRecord[] newRecords(array $fieldSets)
 * @method VideoRecordSet newRecordSet(array $records = [])
 * @method VideoRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method VideoRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Video extends Mapper
{
}
