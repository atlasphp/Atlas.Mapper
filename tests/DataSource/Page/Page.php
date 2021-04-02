<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Mapper;

/**
 * @method PageTable getTable()
 * @method PageRelationships getRelationships()
 * @method PageRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method PageRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method PageRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method PageRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method PageRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method PageRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method PageSelect select(array $whereEquals = [])
 * @method PageRecord newRecord(array $fields = [])
 * @method PageRecord[] newRecords(array $fieldSets)
 * @method PageRecordSet newRecordSet(array $records = [])
 * @method PageRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method PageRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Page extends Mapper
{
}
