<?php
namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecord;
use Atlas\Mapper\CompositeDataSource\Gpa\GpaRecord;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;

class StudentRelated extends Related
{
    #[Define\OneToOne]
    protected GpaRecord $gpa;

    #[Define\ManyToOne]
    #[Define\IgnoreCase]
    protected DegreeRecord $degree;

    #[Define\OneToMany]
    protected EnrollmentRecordSet $enrollments;

    #[Define\OneToMany]
    #[Define\Where('course_subject = ', 'ENGL')]
    protected EnrollmentRecordSet $engl_enrollments;
}
