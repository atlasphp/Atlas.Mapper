<?php
namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\CompositeDataSource\Degree\DegreeRecord;
use Atlas\Mapper\CompositeDataSource\Gpa\GpaRecord;
use Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet;
use Atlas\Mapper\CompositeDataSource\Degree\Degree;
use Atlas\Mapper\CompositeDataSource\Gpa\Gpa;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;

class StudentRelationships extends MapperRelationships
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

    // protected function define()
    // {
    //     $this->oneToOne('gpa', Gpa::CLASS);
    //     $this->manyToOne('degree', Degree::CLASS)->ignoreCase();
    //     $this->oneToMany('enrollments', Enrollment::CLASS);
    //     $this->oneToMany('engl_enrollments', Enrollment::CLASS)
    //         ->where('course_subject = ', 'ENGL');
    // }
}
