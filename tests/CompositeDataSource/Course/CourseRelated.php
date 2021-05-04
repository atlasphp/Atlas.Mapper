<?php
namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\Related;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;
use Atlas\Mapper\NotLoaded;

class CourseRelated extends Related
{
    #[OneToMany]
    protected EnrollmentRecordSet $enrollments;
}
