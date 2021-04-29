<?php
namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;

class CourseRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->oneToMany('enrollments', Enrollment::CLASS);
    }
}
