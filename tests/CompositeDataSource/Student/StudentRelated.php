<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Define;
use Atlas\Mapper\CompositeDataSource\Gpa\GpaRecord;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecord;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;

class StudentRelated extends _generated\StudentRelated_
{
    #[Define\OneToOne]
    protected ?GpaRecord $gpa;

    #[Define\ManyToOne]
    #[Define\IgnoreCase]
    protected ?DegreeRecord $degree;

    #[Define\OneToMany]
    protected EnrollmentRecordSet $enrollments;

    #[Define\OneToMany]
    #[Define\Where('course_subject = ', 'ENGL')]
    protected EnrollmentRecordSet $engl_enrollments;
}
