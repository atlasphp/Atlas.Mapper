<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Degree;

use Atlas\Mapper\Define;
use Atlas\Mapper\CompositeDataSource\Student\StudentRecordSet;

class DegreeRelated extends _generated\DegreeRelated_
{
    #[Define\OneToMany]
    #[Define\IgnoreCase]
    protected StudentRecordSet $students;
}
