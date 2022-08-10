<?php
namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecordSet;

class DegreeRelated extends Related
{
    #[Define\OneToMany]
    #[Define\IgnoreCase]
    protected StudentRecordSet $students;
}
