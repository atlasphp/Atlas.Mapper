<?php
namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related\IgnoreCase;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class DegreeRelated extends Related
{
    #[OneToMany]
    #[IgnoreCase]
    protected StudentRecordSet $students;
}
