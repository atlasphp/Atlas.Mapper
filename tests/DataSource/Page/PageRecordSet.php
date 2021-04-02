<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\RecordSet;

/**
 * @method PageRecord offsetGet($offset)
 * @method PageRecord appendNew(array $fields = [])
 * @method PageRecord|null getOneBy(array $whereEquals)
 * @method PageRecordSet getAllBy(array $whereEquals)
 * @method PageRecord|null detachOneBy(array $whereEquals)
 * @method PageRecordSet detachAllBy(array $whereEquals)
 * @method PageRecordSet detachAll()
 * @method PageRecordSet detachDeleted()
 */
class PageRecordSet extends RecordSet
{
}
