<?php
namespace Atlas\Mapper\CompositeDataSource\Enrollment;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Course\Course;
use Atlas\Mapper\CompositeDataSource\Student\Student;

class EnrollmentRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->manyToOne('course', Course::CLASS);
        $this->manyToOne('student', Student::CLASS);
    }
}
