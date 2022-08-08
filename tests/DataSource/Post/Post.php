<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Mapper;

/**
 * @method PostTable getTable()
 * @method PostRelationships getRelationships()
 * @method PostRecord|null fetchRecord($primaryVal, array $with = [])
 * @method PostRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method PostRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method PostRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method PostRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method PostRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method PostSelect select(array $whereEquals = [])
 * @method PostRecord newRecord(array $fields = [])
 * @method PostRecord[] newRecords(array $fieldSets)
 * @method PostRecordSet newRecordSet(array $records = [])
 * @method PostRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method PostRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Post extends Mapper
{
}
