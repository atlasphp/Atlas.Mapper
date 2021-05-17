<?php
namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;
use Atlas\Mapper\NotLoaded;

class CourseRelated extends Related
{
    #[OneToMany]
    protected EnrollmentRecordSet $enrollments;
}
