<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Mapper;

/**
 * @method BidibarTable getTable()
 * @method BidibarRelationships getRelationships()
 * @method BidibarRecord|null fetchRecord($primaryVal, array $with = [])
 * @method BidibarRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method BidibarRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method BidibarRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method BidibarRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method BidibarRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method BidibarSelect select(array $whereEquals = [])
 * @method BidibarRecord newRecord(array $fields = [])
 * @method BidibarRecord[] newRecords(array $fieldSets)
 * @method BidibarRecordSet newRecordSet(array $records = [])
 * @method BidibarRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method BidibarRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Bidibar extends Mapper
{
}
