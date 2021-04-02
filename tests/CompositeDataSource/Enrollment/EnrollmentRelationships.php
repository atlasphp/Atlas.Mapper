<?php
namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Course\Course;
use Atlas\Mapper\CompositeDataSource\Student\Student;

class EnrollmentRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->manyToOne('course', Course::CLASS);
        $this->manyToOne('student', Student::CLASS);
    }
}
