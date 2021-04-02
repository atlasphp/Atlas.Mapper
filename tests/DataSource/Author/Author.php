<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Mapper;

/**
 * @method AuthorTable getTable()
 * @method AuthorRelationships getRelationships()
 * @method AuthorRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method AuthorRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method AuthorRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method AuthorRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method AuthorRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method AuthorRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method AuthorSelect select(array $whereEquals = [])
 * @method AuthorRecord newRecord(array $fields = [])
 * @method AuthorRecord[] newRecords(array $fieldSets)
 * @method AuthorRecordSet newRecordSet(array $records = [])
 * @method AuthorRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method AuthorRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Author extends Mapper
{
}
