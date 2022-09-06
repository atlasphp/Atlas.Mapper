<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Define;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;

class CourseRelated extends _generated\CourseRelated_
{
    #[Define\OneToMany]
    protected EnrollmentRecordSet $enrollments;
}
