<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Mapper;

/**
 * @method ReplyTable getTable()
 * @method ReplyRelationships getRelationships()
 * @method ReplyRecord|null fetchRecord($primaryVal, array $with = [])
 * @method ReplyRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method ReplyRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method ReplyRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method ReplyRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method ReplyRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method ReplySelect select(array $whereEquals = [])
 * @method ReplyRecord newRecord(array $fields = [])
 * @method ReplyRecord[] newRecords(array $fieldSets)
 * @method ReplyRecordSet newRecordSet(array $records = [])
 * @method ReplyRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method ReplyRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Reply extends Mapper
{
}
