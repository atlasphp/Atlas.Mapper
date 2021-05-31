<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Mapper;

/**
 * @method PageTable getTable()
 * @method PageRelationships getRelationships()
 * @method PageRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method PageRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method PageRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method PageRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method PageRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method PageRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method PageSelect select(array $whereEquals = [])
 * @method PageRecord newRecord(array $fields = [])
 * @method PageRecord[] newRecords(array $fieldSets)
 * @method PageRecordSet newRecordSet(array $records = [])
 * @method PageRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method PageRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Page extends Mapper
{
}
