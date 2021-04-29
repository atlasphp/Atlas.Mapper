<?php
namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\Attribute\Where;
use Atlas\Mapper\Attribute\IgnoreCase;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

use Atlas\Mapper\CompositeDataSource\Gpa\GpaRecord;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecord;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;

class StudentRelated extends Related
{
    #[OneToOne]
    protected NotLoaded|null|GpaRecord $gpa;

    #[ManyToOne]
    #[IgnoreCase]
    protected NotLoaded|null|DegreeRecord $degree;

    #[OneToMany]
    protected NotLoaded|EnrollmentRecordSet $enrollments;

    #[OneToMany]
    #[Where('course_subject = ', 'ENGL')]
    protected NotLoaded|EnrollmentRecordSet $engl_enrollments;
}
