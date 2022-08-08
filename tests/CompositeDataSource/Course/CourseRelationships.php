<?php
namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;
use Atlas\Mapper\CompositeDataSource\Student\Student;

class CourseRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->oneToMany('enrollments', Enrollment::CLASS);
    }
}
