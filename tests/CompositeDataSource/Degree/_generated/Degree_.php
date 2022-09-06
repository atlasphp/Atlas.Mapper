<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Degree\_generated;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecord;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecordSet;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRelated;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeSelect;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeTable;

/**
 * @method DegreeTable getTable()
 * @method ?DegreeRecord fetchRecord(mixed $primaryVal, array $loadRelated = [])
 * @method ?DegreeRecord fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method DegreeRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method DegreeRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method DegreeRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method DegreeRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method DegreeSelect select(array $whereEquals = [])
 * @method DegreeRecord newRecord(array $fields = [])
 * @method DegreeRecord[] newRecords(array $fieldSets)
 * @method DegreeRecordSet newRecordSet(array $records = [])
 * @method DegreeRecord turnRowIntoRecord(DegreeRow $row, array $loadRelated = [])
 * @method DegreeRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
abstract class Degree_ extends Mapper
{
    public const RECORD_CLASS = DegreeRecord::CLASS;
    public const RECORD_SET_CLASS = DegreeRecordSet::CLASS;
    public const RELATED_CLASS = DegreeRelated::CLASS;
    public const SELECT_CLASS = DegreeSelect::CLASS;
}
