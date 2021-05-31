<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Mapper;

/**
 * @method BidibarTable getTable()
 * @method BidibarRelationships getRelationships()
 * @method BidibarRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method BidibarRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method BidibarRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method BidibarRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method BidibarRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method BidibarRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method BidibarSelect select(array $whereEquals = [])
 * @method BidibarRecord newRecord(array $fields = [])
 * @method BidibarRecord[] newRecords(array $fieldSets)
 * @method BidibarRecordSet newRecordSet(array $records = [])
 * @method BidibarRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method BidibarRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Bidibar extends Mapper
{
}
