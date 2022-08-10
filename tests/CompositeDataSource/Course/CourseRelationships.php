<?php
namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;

class CourseRelationships extends MapperRelationships
{
    #[Define\OneToMany]
    protected EnrollmentRecordSet $enrollments;

    // protected function define()
    // {
    //     $this->oneToMany('enrollments', Enrollment::CLASS);
    // }
}
