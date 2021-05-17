<?php
namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Related;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;

class CourseRelated extends Related
{
    #[Related\OneToMany]
    protected EnrollmentRecordSet $enrollments;
}
