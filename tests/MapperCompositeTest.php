<?php
namespace Atlas\Mapper;

use Atlas\Testing\Assertions;
use Atlas\Testing\CompositeDataSource\Course\Course;
use Atlas\Testing\CompositeDataSource\Degree\Degree;
use Atlas\Testing\CompositeDataSource\Enrollment\Enrollment;
use Atlas\Testing\CompositeDataSource\Gpa\Gpa;
use Atlas\Testing\CompositeDataSource\Student\Student;
use Atlas\Testing\CompositeDataSourceFixture;

class MapperCompositeTest extends \PHPUnit\Framework\TestCase
{
    protected $mappers;

    // The $expect* properties are at the end, because they are so long

    protected function setUp()
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

        $this->assertSame($this->expectRecord, $actual);
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

        $this->assertSame($this->expectRecord, $actual);
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
                    $q->with(['course']);
                },
            ]
        )->getArrayCopy();

        foreach ($this->expectRecordSet as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testFetchRecordSetBy()
    {
        // note that we canno to
        $actual = $this->mapperLocator->get(Student::CLASS)->fetchRecordSetBy(
            ['student_fn' => ['Anna', 'Betty', 'Clara']],
            [
                'degree',
                'gpa',
                'enrollments' => function ($q) {
                    $q->orderBy('course_subject', 'course_number');
                    $q->with(['course']);
                },
            ]
        )->getArrayCopy();

        foreach ($this->expectRecordSet as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testSelect_fetchRecord()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)
            ->select()
            ->where('student_fn = ', 'Anna')
            ->with([
                'degree',
                'gpa',
                'enrollments' => [
                    'course'
                ],
            ])
            ->fetchRecord();

        $this->assertSame($this->expectRecord, $actual->getArrayCopy());
    }

    public function testSelect_fetchRecordSet()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)
            ->select()
            ->where('student_fn < ', 'D')
            ->with([
                'degree',
                'gpa',
                'enrollments' => function ($q) {
                    $q->orderBy('course_subject', 'course_number');
                    $q->with(['course']);
                },
            ])
            ->fetchRecordSet()
            ->getArrayCopy();

        foreach ($this->expectRecordSet as $i => $expect) {
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

    public function testCalcPrimaryComposite_missingKey()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Expected scalar value for primary key 'student_ln', value is missing instead."
        );
        $this->mapperLocator->get(Student::CLASS)->fetchRecord(['student_fn' => 'Anna']);
    }

    public function testCalcPrimaryComposite_nonScalar()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Expected scalar value for primary key 'student_fn', got array instead."
        );
        $this->mapperLocator->get(Student::CLASS)->fetchRecord(
            ['student_fn' => ['Anna', 'Betty', 'Clara']]
        );
    }

    public function testCalcPrimaryComposite()
    {
        $actual = $this->mapperLocator->get(Student::CLASS)->fetchRecord(
            [
                'foo' => 'bar',
                'student_fn' => 'Anna',
                'student_ln' => 'Alpha',
                'baz' => 'dib',
            ]
        );

        $this->assertSame('Anna', $actual->student_fn);
        $this->assertSame('Alpha', $actual->student_ln);
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
                'course' => NULL,
                'student' => NULL,
            ]
        ];

        $this->assertSame($expect, $actual);
    }

    protected $expectRecord = [
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
            'students' => NULL,
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
                    'enrollments' => NULL,
                ],
                'student' => NULL,
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
                    'enrollments' => NULL,
                ],
                'student' => NULL,
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
                    'enrollments' => NULL,
                ],
                'student' => NULL,
            ],
        ],
        'engl_enrollments' => NULL,
    ];

    protected $expectRecordSet = [
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
                'students' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
                ],
            ],
            'engl_enrollments' => NULL,
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
                'students' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
                ],
            ],
            'engl_enrollments' => NULL,
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
                'students' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
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
                        'enrollments' => NULL,
                    ],
                    'student' => NULL,
                ],
            ],
            'engl_enrollments' => NULL,
        ],
    ];
}
