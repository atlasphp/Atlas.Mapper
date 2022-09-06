<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidibar\_generated;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\DataSource\Bidibar\BidibarRecord;
use Atlas\Mapper\DataSource\Bidibar\BidibarRecordSet;
use Atlas\Mapper\DataSource\Bidibar\BidibarRelated;
use Atlas\Mapper\DataSource\Bidibar\BidibarSelect;
use Atlas\Mapper\DataSource\Bidibar\BidibarTable;

/**
 * @method BidibarTable getTable()
 * @method ?BidibarRecord fetchRecord(mixed $primaryVal, array $loadRelated = [])
 * @method ?BidibarRecord fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method BidibarRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method BidibarRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method BidibarRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method BidibarRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method BidibarSelect select(array $whereEquals = [])
 * @method BidibarRecord newRecord(array $fields = [])
 * @method BidibarRecord[] newRecords(array $fieldSets)
 * @method BidibarRecordSet newRecordSet(array $records = [])
 * @method BidibarRecord turnRowIntoRecord(BidibarRow $row, array $loadRelated = [])
 * @method BidibarRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
abstract class Bidibar_ extends Mapper
{
    public const RECORD_CLASS = BidibarRecord::CLASS;
    public const RECORD_SET_CLASS = BidibarRecordSet::CLASS;
    public const RELATED_CLASS = BidibarRelated::CLASS;
    public const SELECT_CLASS = BidibarSelect::CLASS;
}
