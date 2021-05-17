<?php
namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Related;

use Atlas\Mapper\CompositeDataSource\Gpa\GpaRecord;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecord;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;

class StudentRelated extends Related
{
    #[Related\OneToOne]
    protected ?GpaRecord $gpa;

    #[Related\ManyToOne]
    #[Related\IgnoreCase]
    protected ?DegreeRecord $degree;

    #[Related\OneToMany]
    protected EnrollmentRecordSet $enrollments;

    #[Related\OneToMany]
    #[Related\Where('course_subject = ', 'ENGL')]
    protected EnrollmentRecordSet $engl_enrollments;
}
