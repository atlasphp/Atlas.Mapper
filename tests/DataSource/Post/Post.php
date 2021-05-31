<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Mapper;

/**
 * @method PostTable getTable()
 * @method PostRelationships getRelationships()
 * @method PostRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method PostRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method PostRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method PostRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method PostRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method PostRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method PostSelect select(array $whereEquals = [])
 * @method PostRecord newRecord(array $fields = [])
 * @method PostRecord[] newRecords(array $fieldSets)
 * @method PostRecordSet newRecordSet(array $records = [])
 * @method PostRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method PostRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Post extends Mapper
{
}
