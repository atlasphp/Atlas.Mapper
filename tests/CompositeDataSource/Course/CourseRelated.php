<?php
namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;

class CourseRelated extends Related
{
    #[Define\OneToMany]
    protected EnrollmentRecordSet $enrollments;
}
