<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\RecordSet;

/**
 * @method CourseRecord offsetGet($offset)
 * @method CourseRecord appendNew(array $fields = [])
 * @method CourseRecord|null getOneBy(array $whereEquals)
 * @method CourseRecordSet getAllBy(array $whereEquals)
 * @method CourseRecord|null detachOneBy(array $whereEquals)
 * @method CourseRecordSet detachAllBy(array $whereEquals)
 * @method CourseRecordSet detachAll()
 * @method CourseRecordSet detachDeleted()
 */
class CourseRecordSet extends RecordSet
{
}
