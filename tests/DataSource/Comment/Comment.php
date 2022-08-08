<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Mapper;

/**
 * @method CommentTable getTable()
 * @method CommentRelationships getRelationships()
 * @method CommentRecord|null fetchRecord($primaryVal, array $with = [])
 * @method CommentRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method CommentRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method CommentRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method CommentRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method CommentRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method CommentSelect select(array $whereEquals = [])
 * @method CommentRecord newRecord(array $fields = [])
 * @method CommentRecord[] newRecords(array $fieldSets)
 * @method CommentRecordSet newRecordSet(array $records = [])
 * @method CommentRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method CommentRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Comment extends Mapper
{
}
