<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\RecordSet;

/**
 * @method BidibarRecord offsetGet($offset)
 * @method BidibarRecord appendNew(array $fields = [])
 * @method BidibarRecord|null getOneBy(array $whereEquals)
 * @method BidibarRecordSet getAllBy(array $whereEquals)
 * @method BidibarRecord|null detachOneBy(array $whereEquals)
 * @method BidibarRecordSet detachAllBy(array $whereEquals)
 * @method BidibarRecordSet detachAll()
 * @method BidibarRecordSet detachDeleted()
 */
class BidibarRecordSet extends RecordSet
{
}
