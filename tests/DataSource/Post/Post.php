<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Mapper;

/**
 * @method PostTable getTable()
 * @method PostRelationships getRelationships()
 * @method PostRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method PostRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method PostRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method PostRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method PostRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method PostRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method PostSelect select(array $whereEquals = [])
 * @method PostRecord newRecord(array $fields = [])
 * @method PostRecord[] newRecords(array $fieldSets)
 * @method PostRecordSet newRecordSet(array $records = [])
 * @method PostRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method PostRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Post extends Mapper
{
}
