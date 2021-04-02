<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Mapper;

/**
 * @method BidibarTable getTable()
 * @method BidibarRelationships getRelationships()
 * @method BidibarRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method BidibarRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method BidibarRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method BidibarRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method BidibarRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method BidibarRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method BidibarSelect select(array $whereEquals = [])
 * @method BidibarRecord newRecord(array $fields = [])
 * @method BidibarRecord[] newRecords(array $fieldSets)
 * @method BidibarRecordSet newRecordSet(array $records = [])
 * @method BidibarRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method BidibarRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Bidibar extends Mapper
{
}
