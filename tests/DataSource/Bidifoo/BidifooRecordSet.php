<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\RecordSet;

/**
 * @method BidifooRecord offsetGet($offset)
 * @method BidifooRecord appendNew(array $fields = [])
 * @method BidifooRecord|null getOneBy(array $whereEquals)
 * @method BidifooRecordSet getAllBy(array $whereEquals)
 * @method BidifooRecord|null detachOneBy(array $whereEquals)
 * @method BidifooRecordSet detachAllBy(array $whereEquals)
 * @method BidifooRecordSet detachAll()
 * @method BidifooRecordSet detachDeleted()
 */
class BidifooRecordSet extends RecordSet
{
}
