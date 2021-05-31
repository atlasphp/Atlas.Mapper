<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Mapper;

/**
 * @method ReplyTable getTable()
 * @method ReplyRelationships getRelationships()
 * @method ReplyRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method ReplyRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method ReplyRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method ReplyRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method ReplyRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method ReplyRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method ReplySelect select(array $whereEquals = [])
 * @method ReplyRecord newRecord(array $fields = [])
 * @method ReplyRecord[] newRecords(array $fieldSets)
 * @method ReplyRecordSet newRecordSet(array $records = [])
 * @method ReplyRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method ReplyRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Reply extends Mapper
{
}
