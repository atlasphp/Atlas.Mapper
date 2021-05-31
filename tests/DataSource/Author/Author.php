<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Mapper;

/**
 * @method AuthorTable getTable()
 * @method AuthorRelationships getRelationships()
 * @method AuthorRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method AuthorRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method AuthorRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method AuthorRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method AuthorRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method AuthorRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method AuthorSelect select(array $whereEquals = [])
 * @method AuthorRecord newRecord(array $fields = [])
 * @method AuthorRecord[] newRecords(array $fieldSets)
 * @method AuthorRecordSet newRecordSet(array $records = [])
 * @method AuthorRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method AuthorRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Author extends Mapper
{
}
