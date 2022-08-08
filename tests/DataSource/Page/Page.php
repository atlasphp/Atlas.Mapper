<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Mapper;

/**
 * @method PageTable getTable()
 * @method PageRelationships getRelationships()
 * @method PageRecord|null fetchRecord($primaryVal, array $with = [])
 * @method PageRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method PageRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method PageRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method PageRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method PageRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method PageSelect select(array $whereEquals = [])
 * @method PageRecord newRecord(array $fields = [])
 * @method PageRecord[] newRecords(array $fieldSets)
 * @method PageRecordSet newRecordSet(array $records = [])
 * @method PageRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method PageRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Page extends Mapper
{
}
