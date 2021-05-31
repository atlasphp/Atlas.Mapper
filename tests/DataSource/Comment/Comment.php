<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Mapper;

/**
 * @method CommentTable getTable()
 * @method CommentRelationships getRelationships()
 * @method CommentRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method CommentRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method CommentRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method CommentRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method CommentRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method CommentRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method CommentSelect select(array $whereEquals = [])
 * @method CommentRecord newRecord(array $fields = [])
 * @method CommentRecord[] newRecords(array $fieldSets)
 * @method CommentRecordSet newRecordSet(array $records = [])
 * @method CommentRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method CommentRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Comment extends Mapper
{
}
