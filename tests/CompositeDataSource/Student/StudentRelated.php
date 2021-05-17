<?php
namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related\Where;
use Atlas\Mapper\Related\IgnoreCase;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

use Atlas\Mapper\CompositeDataSource\Gpa\GpaRecord;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecord;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;

class StudentRelated extends Related
{
    #[OneToOne]
    protected ?GpaRecord $gpa;

    #[ManyToOne]
    #[IgnoreCase]
    protected ?DegreeRecord $degree;

    #[OneToMany]
    protected EnrollmentRecordSet $enrollments;

    #[OneToMany]
    #[Where('course_subject = ', 'ENGL')]
    protected EnrollmentRecordSet $engl_enrollments;
}
