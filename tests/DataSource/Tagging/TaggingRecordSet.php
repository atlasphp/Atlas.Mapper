<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\RecordSet;

/**
 * @method TaggingRecord offsetGet($offset)
 * @method TaggingRecord appendNew(array $fields = [])
 * @method TaggingRecord|null getOneBy(array $whereEquals)
 * @method TaggingRecordSet getAllBy(array $whereEquals)
 * @method TaggingRecord|null detachOneBy(array $whereEquals)
 * @method TaggingRecordSet detachAllBy(array $whereEquals)
 * @method TaggingRecordSet detachAll()
 * @method TaggingRecordSet detachDeleted()
 */
class TaggingRecordSet extends RecordSet
{
}
