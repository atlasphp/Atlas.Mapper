<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\RecordSet;

/**
 * @method SummaryRecord offsetGet($offset)
 * @method SummaryRecord appendNew(array $fields = [])
 * @method SummaryRecord|null getOneBy(array $whereEquals)
 * @method SummaryRecordSet getAllBy(array $whereEquals)
 * @method SummaryRecord|null detachOneBy(array $whereEquals)
 * @method SummaryRecordSet detachAllBy(array $whereEquals)
 * @method SummaryRecordSet detachAll()
 * @method SummaryRecordSet detachDeleted()
 */
class SummaryRecordSet extends RecordSet
{
}
