<?php
namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\CompositeDataSource\Course\CourseRecord;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class EnrollmentRelated extends Related
{
    #[ManyToOne]
    protected ?CourseRecord $course;

    #[ManyToOne]
    protected ?StudentRecord $student;
}
