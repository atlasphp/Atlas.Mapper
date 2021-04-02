<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Mapper;

/**
 * @method ReplyTable getTable()
 * @method ReplyRelationships getRelationships()
 * @method ReplyRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method ReplyRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method ReplyRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method ReplyRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method ReplyRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method ReplyRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method ReplySelect select(array $whereEquals = [])
 * @method ReplyRecord newRecord(array $fields = [])
 * @method ReplyRecord[] newRecords(array $fieldSets)
 * @method ReplyRecordSet newRecordSet(array $records = [])
 * @method ReplyRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method ReplyRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Reply extends Mapper
{
}
