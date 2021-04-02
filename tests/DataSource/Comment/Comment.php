<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Mapper;

/**
 * @method CommentTable getTable()
 * @method CommentRelationships getRelationships()
 * @method CommentRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method CommentRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method CommentRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method CommentRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method CommentRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method CommentRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method CommentSelect select(array $whereEquals = [])
 * @method CommentRecord newRecord(array $fields = [])
 * @method CommentRecord[] newRecords(array $fieldSets)
 * @method CommentRecordSet newRecordSet(array $records = [])
 * @method CommentRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method CommentRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Comment extends Mapper
{
}
