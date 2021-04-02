<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Gpa;

use Atlas\Mapper\Mapper;

/**
 * @method GpaTable getTable()
 * @method GpaRelationships getRelationships()
 * @method GpaRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method GpaRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method GpaRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method GpaRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method GpaRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method GpaRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method GpaSelect select(array $whereEquals = [])
 * @method GpaRecord newRecord(array $fields = [])
 * @method GpaRecord[] newRecords(array $fieldSets)
 * @method GpaRecordSet newRecordSet(array $records = [])
 * @method GpaRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method GpaRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Gpa extends Mapper
{
}
