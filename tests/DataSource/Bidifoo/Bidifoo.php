<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Mapper;

/**
 * @method BidifooTable getTable()
 * @method BidifooRelationships getRelationships()
 * @method BidifooRecord|null fetchRecord($primaryVal, array $loadRelated = [])
 * @method BidifooRecord|null fetchRecordBy(array $whereEquals, array $loadRelated = [])
 * @method BidifooRecord[] fetchRecords(array $primaryVals, array $loadRelated = [])
 * @method BidifooRecord[] fetchRecordsBy(array $whereEquals, array $loadRelated = [])
 * @method BidifooRecordSet fetchRecordSet(array $primaryVals, array $loadRelated = [])
 * @method BidifooRecordSet fetchRecordSetBy(array $whereEquals, array $loadRelated = [])
 * @method BidifooSelect select(array $whereEquals = [])
 * @method BidifooRecord newRecord(array $fields = [])
 * @method BidifooRecord[] newRecords(array $fieldSets)
 * @method BidifooRecordSet newRecordSet(array $records = [])
 * @method BidifooRecord turnRowIntoRecord(Row $row, array $loadRelated = [])
 * @method BidifooRecord[] turnRowsIntoRecords(array $rows, array $loadRelated = [])
 */
class Bidifoo extends Mapper
{
}
