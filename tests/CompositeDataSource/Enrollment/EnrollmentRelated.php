<?php
namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\CompositeDataSource\Course\CourseRecord;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecord;
use Atlas\Mapper\Related;

class EnrollmentRelated extends Related
{
    #[Related\ManyToOne]
    protected ?CourseRecord $course;

    #[Related\ManyToOne]
    protected ?StudentRecord $student;
}
