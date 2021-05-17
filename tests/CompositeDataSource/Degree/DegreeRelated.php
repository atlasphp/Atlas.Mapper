<?php
namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\CompositeDataSource\Student\StudentRecordSet;
use Atlas\Mapper\Related;

class DegreeRelated extends Related
{
    #[Related\OneToMany]
    #[Related\IgnoreCase]
    protected StudentRecordSet $students;
}
