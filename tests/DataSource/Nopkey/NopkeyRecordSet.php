<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Nopkey;

use Atlas\Mapper\RecordSet;

/**
 * @method NopkeyRecord offsetGet($offset)
 * @method NopkeyRecord appendNew(array $fields = [])
 * @method NopkeyRecord|null getOneBy(array $whereEquals)
 * @method NopkeyRecordSet getAllBy(array $whereEquals)
 * @method NopkeyRecord|null detachOneBy(array $whereEquals)
 * @method NopkeyRecordSet detachAllBy(array $whereEquals)
 * @method NopkeyRecordSet detachAll()
 * @method NopkeyRecordSet detachDeleted()
 */
class NopkeyRecordSet extends RecordSet
{
}
