<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Assertions;
use Atlas\Mapper\CompositeDataSource\Course\Course;
use Atlas\Mapper\CompositeDataSource\Degree\Degree;
use Atlas\Mapper\CompositeDataSource\Enrollment\Enrollment;
use Atlas\Mapper\CompositeDataSource\Gpa\Gpa;
use Atlas\Mapper\CompositeDataSource\Student\Student;
use Atlas\Mapper\CompositeDataSourceFixture;
use Atlas\Mapper\Relationship\NotLoaded;

class MapperCompositeTest extends \PHPUnit\Framework\TestCase
{
    protected $mappers;

    // The $expect* properties are at the end, because they are so long

    protected function setUp() : void
    {
        $connection = (new CompositeDataSourceFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
    }

    public function testFetchRecord()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)->fetchRecord(
            ['student_fn' => 'Anna', 'student_ln' => 'Alpha'],
            [
                'degree',
                'gpa',
                'enrollments' => [
                    'course',
                ],
            ]
        )->getArrayCopy();

        $this->assertSame($this->expectRecord(), $actual);
    }

    public function testFetchRecordBy()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)->fetchRecordBy(
            ['student_fn' => 'Anna'],
            [
                'degree',
                'gpa',
                'enrollments' => [
                    'course',
                ],
            ]
        )->getArrayCopy();

        $this->assertSame($this->expectRecord(), $actual);
    }

    public function testFetchRecordSet()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)->fetchRecordSet(
            [
                ['student_fn' => 'Anna', 'student_ln' => 'Alpha'],
                ['student_fn' => 'Betty', 'student_ln' => 'Beta'],
                ['student_fn' => 'Clara', 'student_ln' => 'Clark'],
            ],
            [
                'degree',
                'gpa',
                'enrollments' => function ($q) {
                    $q->orderBy('course_subject', 'course_number');
                    $q->loadRelated(['course']);
                },
            ]
        )->getArrayCopy();

        foreach ($this->expectRecordSet() as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testFetchRecordSetBy()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)->fetchRecordSetBy(
            ['student_fn' => ['Anna', 'Betty', 'Clara']],
            [
                'degree',
                'gpa',
                'enrollments' => function ($q) {
                    $q->orderBy('course_subject', 'course_number');
                    $q->loadRelated(['course']);
                },
            ]
        )->getArrayCopy();

        foreach ($this->expectRecordSet() as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testSelect_fetchRecord()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)
            ->select()
            ->where('student_fn = ', 'Anna')
            ->loadRelated([
                'degree',
                'gpa',
                'enrollments' => [
                    'course'
                ],
            ])
            ->fetchRecord();

        $this->assertSame($this->expectRecord(), $actual->getArrayCopy());
    }

    public function testSelect_fetchRecordSet()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)
            ->select()
            ->where('student_fn < ', 'D')
            ->loadRelated([
                'degree',
                'gpa',
                'enrollments' => function ($q) {
                    $q->orderBy('course_subject', 'course_number');
                    $q->loadRelated(['course']);
                },
            ])
            ->fetchRecordSet()
            ->getArrayCopy();

        foreach ($this->expectRecordSet() as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testSingleRelatedInRecordSet()
    {
        $degree = $this->mapperLocator->get(Degree::CLASS)->fetchRecordBy(
            [
                'degree_type' => 'BS',
                'degree_subject' => 'MATH',
            ]
        );
        $expect = $degree->getRow();

        $students = $this->mapperLocator->get(Student::CLASS)->fetchRecordSetBy(
            [
                'degree_type' => 'BS',
                'degree_subject' => 'MATH',
            ],
            [
                'degree',
            ]
        );

        foreach ($students as $student) {
            $actual = $student->degree->getRow();
            $this->assertSame($expect, $actual);
        }
    }

    public function testRelationshipWhere()
    {
        $student = $this->mapperLocator->get(Student::CLASS)->fetchRecord(
            [
                'student_fn' => 'Anna',
                'student_ln' => 'Alpha',
            ],
            [
                'engl_enrollments',
            ]
        );

        $actual = $student->engl_enrollments->getArrayCopy();

        $expect = [
            [
                'student_fn' => 'Anna',
                'student_ln' => 'Alpha',
                'course_subject' => 'ENGL',
                'course_number' => '100',
                'grade' => '65',
                'points' => '1',
                'course' => NotLoaded::getInstance(),
                'student' => NotLoaded::getInstance(),
            ]
        ];

        $this->assertSame($expect, $actual);
    }

    protected function expectRecord() { return [
        'student_fn' => 'Anna',
        'student_ln' => 'Alpha',
        'degree_type' => 'BA',
        'degree_subject' => 'ENGL',
        'gpa' => [
            'student_fn' => 'Anna',
            'student_ln' => 'Alpha',
            'gpa' => '1.333',
        ],
        'degree' => [
            'degree_type' => 'ba',
            'degree_subject' => 'engl',
            'title' => 'Bachelor of Arts, English',
            'students' => NotLoaded::getInstance(),
        ],
        'enrollments' => [
            0 => [
                'student_fn' => 'Anna',
                'student_ln' => 'Alpha',
                'course_subject' => 'ENGL',
                'course_number' => '100',
                'grade' => '65',
                'points' => '1',
                'course' => [
                    'course_subject' => 'ENGL',
                    'course_number' => '100',
                    'title' => 'Composition',
                    'enrollments' => NotLoaded::getInstance(),
                ],
                'student' => NotLoaded::getInstance(),
            ],
            1 => [
                'student_fn' => 'Anna',
                'student_ln' => 'Alpha',
                'course_subject' => 'HIST',
                'course_number' => '100',
                'grade' => '68',
                'points' => '1',
                'course' => [
                    'course_subject' => 'HIST',
                    'course_number' => '100',
                    'title' => 'World History',
                    'enrollments' => NotLoaded::getInstance(),
                ],
                'student' => NotLoaded::getInstance(),
            ],
            2 => [
                'student_fn' => 'Anna',
                'student_ln' => 'Alpha',
                'course_subject' => 'MATH',
                'course_number' => '100',
                'grade' => '71',
                'points' => '2',
                'course' => [
                    'course_subject' => 'MATH',
                    'course_number' => '100',
                    'title' => 'Algebra',
                    'enrollments' => NotLoaded::getInstance(),
                ],
                'student' => NotLoaded::getInstance(),
            ],
        ],
        'engl_enrollments' => NotLoaded::getInstance(),
    ];}

    protected function expectRecordSet() { return [
        0 => [
            'student_fn' => 'Anna',
            'student_ln' => 'Alpha',
            'degree_type' => 'BA',
            'degree_subject' => 'ENGL',
            'gpa' => [
                'student_fn' => 'Anna',
                'student_ln' => 'Alpha',
                'gpa' => '1.333',
            ],
            'degree' => [
                'degree_type' => 'ba',
                'degree_subject' => 'engl',
                'title' => 'Bachelor of Arts, English',
                'students' => NotLoaded::getInstance(),
            ],
            'enrollments' => [
                0 => [
                    'student_fn' => 'Anna',
                    'student_ln' => 'Alpha',
                    'course_subject' => 'ENGL',
                    'course_number' => '100',
                    'grade' => '65',
                    'points' => '1',
                    'course' => [
                        'course_subject' => 'ENGL',
                        'course_number' => '100',
                        'title' => 'Composition',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
                1 => [
                    'student_fn' => 'Anna',
                    'student_ln' => 'Alpha',
                    'course_subject' => 'HIST',
                    'course_number' => '100',
                    'grade' => '68',
                    'points' => '1',
                    'course' => [
                        'course_subject' => 'HIST',
                        'course_number' => '100',
                        'title' => 'World History',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
                2 => [
                    'student_fn' => 'Anna',
                    'student_ln' => 'Alpha',
                    'course_subject' => 'MATH',
                    'course_number' => '100',
                    'grade' => '71',
                    'points' => '2',
                    'course' => [
                        'course_subject' => 'MATH',
                        'course_number' => '100',
                        'title' => 'Algebra',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
            ],
            'engl_enrollments' => NotLoaded::getInstance(),
        ],
        1 => [
            'student_fn' => 'Betty',
            'student_ln' => 'Beta',
            'degree_type' => 'MA',
            'degree_subject' => 'HIST',
            'gpa' => [
                'student_fn' => 'Betty',
                'student_ln' => 'Beta',
                'gpa' => '1.667',
            ],
            'degree' => [
                'degree_type' => 'ma',
                'degree_subject' => 'hist',
                'title' => 'Master of Arts, History',
                'students' => NotLoaded::getInstance(),
            ],
            'enrollments' => [
                0 => [
                    'student_fn' => 'Betty',
                    'student_ln' => 'Beta',
                    'course_subject' => 'ENGL',
                    'course_number' => '200',
                    'grade' => '74',
                    'points' => '2',
                    'course' => [
                        'course_subject' => 'ENGL',
                        'course_number' => '200',
                        'title' => 'Creative Writing',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
                1 => [
                    'student_fn' => 'Betty',
                    'student_ln' => 'Beta',
                    'course_subject' => 'HIST',
                    'course_number' => '100',
                    'grade' => '68',
                    'points' => '1',
                    'course' => [
                        'course_subject' => 'HIST',
                        'course_number' => '100',
                        'title' => 'World History',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
                2 => [
                    'student_fn' => 'Betty',
                    'student_ln' => 'Beta',
                    'course_subject' => 'MATH',
                    'course_number' => '100',
                    'grade' => '71',
                    'points' => '2',
                    'course' => [
                        'course_subject' => 'MATH',
                        'course_number' => '100',
                        'title' => 'Algebra',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
            ],
            'engl_enrollments' => NotLoaded::getInstance(),
        ],
        2 => [
            'student_fn' => 'Clara',
            'student_ln' => 'Clark',
            'degree_type' => 'BS',
            'degree_subject' => 'MATH',
            'gpa' => [
                'student_fn' => 'Clara',
                'student_ln' => 'Clark',
                'gpa' => '2',
            ],
            'degree' => [
                'degree_type' => 'bs',
                'degree_subject' => 'math',
                'title' => 'Bachelor of Science, Mathematics',
                'students' => NotLoaded::getInstance(),
            ],
            'enrollments' => [
                0 => [
                    'student_fn' => 'Clara',
                    'student_ln' => 'Clark',
                    'course_subject' => 'ENGL',
                    'course_number' => '200',
                    'grade' => '74',
                    'points' => '2',
                    'course' => [
                        'course_subject' => 'ENGL',
                        'course_number' => '200',
                        'title' => 'Creative Writing',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
                1 => [
                    'student_fn' => 'Clara',
                    'student_ln' => 'Clark',
                    'course_subject' => 'HIST',
                    'course_number' => '200',
                    'grade' => '77',
                    'points' => '2',
                    'course' => [
                        'course_subject' => 'HIST',
                        'course_number' => '200',
                        'title' => 'US History',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
                2 => [
                    'student_fn' => 'Clara',
                    'student_ln' => 'Clark',
                    'course_subject' => 'MATH',
                    'course_number' => '100',
                    'grade' => '71',
                    'points' => '2',
                    'course' => [
                        'course_subject' => 'MATH',
                        'course_number' => '100',
                        'title' => 'Algebra',
                        'enrollments' => NotLoaded::getInstance(),
                    ],
                    'student' => NotLoaded::getInstance(),
                ],
            ],
            'engl_enrollments' => NotLoaded::getInstance(),
        ],
    ];}
}
