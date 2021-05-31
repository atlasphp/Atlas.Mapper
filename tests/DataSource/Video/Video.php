<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\Mapper;

/**
 * @method VideoTable getTable()
 * @method VideoRelationships getRelationships()
 * @method VideoRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method VideoRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method VideoRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method VideoRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method VideoRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method VideoRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method VideoSelect select(array $whereEquals = [])
 * @method VideoRecord newRecord(array $fields = [])
 * @method VideoRecord[] newRecords(array $fieldSets)
 * @method VideoRecordSet newRecordSet(array $records = [])
 * @method VideoRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method VideoRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Video extends Mapper
{
}
