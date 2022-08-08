<?php
namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Student\Student;

class DegreeRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->oneToMany('students', Student::CLASS)
            ->ignoreCase();
    }
}
