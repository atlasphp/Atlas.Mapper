<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Mapper;

/**
 * @method BidifooTable getTable()
 * @method BidifooRelationships getRelationships()
 * @method BidifooRecord|null fetchRecord($primaryVal, array $with = [])
 * @method BidifooRecord|null fetchRecordBy(array $whereEquals, array $with = [])
 * @method BidifooRecord[] fetchRecords(array $primaryVals, array $with = [])
 * @method BidifooRecord[] fetchRecordsBy(array $whereEquals, array $with = [])
 * @method BidifooRecordSet fetchRecordSet(array $primaryVals, array $with = [])
 * @method BidifooRecordSet fetchRecordSetBy(array $whereEquals, array $with = [])
 * @method BidifooSelect select(array $whereEquals = [])
 * @method BidifooRecord newRecord(array $fields = [])
 * @method BidifooRecord[] newRecords(array $fieldSets)
 * @method BidifooRecordSet newRecordSet(array $records = [])
 * @method BidifooRecord turnRowIntoRecord(Row $row, array $with = [])
 * @method BidifooRecord[] turnRowsIntoRecords(array $rows, array $with = [])
 */
class Bidifoo extends Mapper
{
}
