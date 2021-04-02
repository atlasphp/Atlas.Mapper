<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Mapper;

/**
 * @method BidifooTable getTable()
 * @method BidifooRelationships getRelationships()
 * @method BidifooRecord|null fetchRecord($primaryVal, array $eager = [])
 * @method BidifooRecord|null fetchRecordBy(array $whereEquals, array $eager = [])
 * @method BidifooRecord[] fetchRecords(array $primaryVals, array $eager = [])
 * @method BidifooRecord[] fetchRecordsBy(array $whereEquals, array $eager = [])
 * @method BidifooRecordSet fetchRecordSet(array $primaryVals, array $eager = [])
 * @method BidifooRecordSet fetchRecordSetBy(array $whereEquals, array $eager = [])
 * @method BidifooSelect select(array $whereEquals = [])
 * @method BidifooRecord newRecord(array $fields = [])
 * @method BidifooRecord[] newRecords(array $fieldSets)
 * @method BidifooRecordSet newRecordSet(array $records = [])
 * @method BidifooRecord turnRowIntoRecord(Row $row, array $eager = [])
 * @method BidifooRecord[] turnRowsIntoRecords(array $rows, array $eager = [])
 */
class Bidifoo extends Mapper
{
}
