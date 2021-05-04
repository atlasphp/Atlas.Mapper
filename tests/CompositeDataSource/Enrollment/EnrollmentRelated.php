<?php
namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
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
