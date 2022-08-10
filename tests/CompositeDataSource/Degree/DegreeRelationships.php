<?php
namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Student\Student;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecordSet;

class DegreeRelationships extends MapperRelationships
{
    #[Define\OneToMany]
    #[Define\IgnoreCase]
    protected StudentRecordSet $students;

    // protected function define()
    // {
    //     $this->oneToMany('students', Student::CLASS)
    //         ->ignoreCase();
    // }
}
