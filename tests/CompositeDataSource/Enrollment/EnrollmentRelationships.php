<?php
namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Course\CourseRecord;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecord;
use Atlas\Mapper\CompositeDataSource\Course\Course;
use Atlas\Mapper\CompositeDataSource\Student\Student;

class EnrollmentRelationships extends MapperRelationships
{
    #[Define\ManyToOne]
    protected ?CourseRecord $course;

    #[Define\ManyToOne]
    protected ?StudentRecord $student;

    // protected function define()
    // {
    //     $this->manyToOne('course', Course::CLASS);
    //     $this->manyToOne('student', Student::CLASS);
    // }
}
