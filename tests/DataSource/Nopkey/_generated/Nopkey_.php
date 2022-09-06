<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Nopkey\_generated;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\DataSource\Nopkey\NopkeyRecord;
use Atlas\Mapper\DataSource\Nopkey\NopkeyRecordSet;
use Atlas\Mapper\DataSource\Nopkey\NopkeyRelated;
use Atlas\Mapper\DataSource\Nopkey\NopkeySelect;
use Atlas\Mapper\DataSource\Nopkey\NopkeyTable;

/**
 * @method NopkeyTable getTable()
 * @method ?NopkeyRecord fetchRecord(mixed $primaryVal, array $loadRelated = [])
 * @method ?NopkeyRecord fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method NopkeyRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method NopkeyRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method NopkeyRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method NopkeyRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method NopkeySelect select(array $whereEquals = [])
 * @method NopkeyRecord newRecord(array $fields = [])
 * @method NopkeyRecord[] newRecords(array $fieldSets)
 * @method NopkeyRecordSet newRecordSet(array $records = [])
 * @method NopkeyRecord turnRowIntoRecord(NopkeyRow $row, array $loadRelated = [])
 * @method NopkeyRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
abstract class Nopkey_ extends Mapper
{
    public const RECORD_CLASS = NopkeyRecord::CLASS;
    public const RECORD_SET_CLASS = NopkeyRecordSet::CLASS;
    public const RELATED_CLASS = NopkeyRelated::CLASS;
    public const SELECT_CLASS = NopkeySelect::CLASS;
}
