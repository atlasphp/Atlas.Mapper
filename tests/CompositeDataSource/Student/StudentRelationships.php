<?php
namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Course\Course;
use Atlas\Mapper\CompositeDataSource\Degree\Degree;
use Atlas\Mapper\CompositeDataSource\Gpa\Gpa;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;

class StudentRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->oneToOne('gpa', Gpa::CLASS);
        $this->manyToOne('degree', Degree::CLASS)->ignoreCase();
        $this->oneToMany('enrollments', Enrollment::CLASS);
        $this->oneToMany('engl_enrollments', Enrollment::CLASS)
            ->where('course_subject = ', 'ENGL');
    }
}
