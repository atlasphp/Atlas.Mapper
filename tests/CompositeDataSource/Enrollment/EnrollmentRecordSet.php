<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\RecordSet;

/**
 * @method EnrollmentRecord offsetGet($offset)
 * @method EnrollmentRecord appendNew(array $fields = [])
 * @method EnrollmentRecord|null getOneBy(array $whereEquals)
 * @method EnrollmentRecordSet getAllBy(array $whereEquals)
 * @method EnrollmentRecord|null detachOneBy(array $whereEquals)
 * @method EnrollmentRecordSet detachAllBy(array $whereEquals)
 * @method EnrollmentRecordSet detachAll()
 * @method EnrollmentRecordSet detachDeleted()
 */
class EnrollmentRecordSet extends RecordSet
{
}
