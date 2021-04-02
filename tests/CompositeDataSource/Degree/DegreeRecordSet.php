<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\RecordSet;

/**
 * @method DegreeRecord offsetGet($offset)
 * @method DegreeRecord appendNew(array $fields = [])
 * @method DegreeRecord|null getOneBy(array $whereEquals)
 * @method DegreeRecordSet getAllBy(array $whereEquals)
 * @method DegreeRecord|null detachOneBy(array $whereEquals)
 * @method DegreeRecordSet detachAllBy(array $whereEquals)
 * @method DegreeRecordSet detachAll()
 * @method DegreeRecordSet detachDeleted()
 */
class DegreeRecordSet extends RecordSet
{
}
