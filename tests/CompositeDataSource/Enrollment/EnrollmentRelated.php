<?php
namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\CompositeDataSource\Course\CourseRecord;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecord;

class EnrollmentRelated extends Related
{
    #[Define\ManyToOne]
    protected ?CourseRecord $course;

    #[Define\ManyToOne]
    protected ?StudentRecord $student;
}
