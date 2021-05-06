<?php
namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\Attribute\IgnoreCase;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class DegreeRelated extends Related
{
    #[OneToMany]
    #[IgnoreCase]
    protected StudentRecordSet $students;
}