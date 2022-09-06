<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Define;
use Atlas\Mapper\CompositeDataSource\Course\CourseRecord;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecord;

class EnrollmentRelated extends _generated\EnrollmentRelated_
{
    #[Define\ManyToOne]
    protected ?CourseRecord $course;

    #[Define\ManyToOne]
    protected ?StudentRecord $student;
}
