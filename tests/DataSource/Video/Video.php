<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\Mapper;

/**
 * @method VideoTable getTable()
 * @method VideoRelationships getRelationships()
 * @method VideoRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method VideoRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method VideoRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method VideoRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method VideoRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method VideoRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method VideoSelect select(array $whereEquals = [])
 * @method VideoRecord newRecord(array $fields = [])
 * @method VideoRecord[] newRecords(array $fieldSets)
 * @method VideoRecordSet newRecordSet(array $records = [])
 * @method VideoRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method VideoRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Video extends Mapper
{
}
