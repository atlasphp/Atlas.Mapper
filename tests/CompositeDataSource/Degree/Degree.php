<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\Mapper;

/**
 * @method DegreeTable getTable()
 * @method DegreeRelationships getRelationships()
 * @method DegreeRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method DegreeRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method DegreeRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method DegreeRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method DegreeRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method DegreeRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method DegreeSelect select(array $whereEquals = [])
 * @method DegreeRecord newRecord(array $fields = [])
 * @method DegreeRecord[] newRecords(array $fieldSets)
 * @method DegreeRecordSet newRecordSet(array $records = [])
 * @method DegreeRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method DegreeRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Degree extends Mapper
{
}
