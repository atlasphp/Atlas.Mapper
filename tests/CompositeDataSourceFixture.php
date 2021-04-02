<?php
namespace Atlas\Mapper;

use Atlas\Pdo\Connection;

class CompositeDataSourceFixture
{
    protected $connection;

    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }

    public function exec()
    {
        if ($this->connection === null) {
            $this->connection = Connection::new('sqlite::memory:');
        }

        $this->degrees();
        $this->students();
        $this->courses();
        $this->enrollments();
        $this->gpas();

        return $this->connection;
    }

    protected function degrees()
    {
        $this->connection->query("CREATE TABLE degrees (
            degree_type CHAR(2) CONSTRAINT dtnocase COLLATE NOCASE,
            degree_subject CHAR(4) CONSTRAINT dsnocase COLLATE NOCASE,
            title VARCHAR(50),
            PRIMARY KEY (degree_type, degree_subject)
        )");

        $stm = "INSERT INTO degrees (degree_type, degree_subject, title) VALUES (?, ?, ?)";
        $this->connection->perform($stm, ['ba', 'engl', 'Bachelor of Arts, English']);
        $this->connection->perform($stm, ['ma', 'hist', 'Master of Arts, History']);
        $this->connection->perform($stm, ['bs', 'math', 'Bachelor of Science, Mathematics']);
    }

    protected function students()
    {
        $this->connection->query("CREATE TABLE students (
            student_fn VARCHAR(10),
            student_ln VARCHAR(10),
            degree_type CHAR(2),
            degree_subject CHAR(4),
            PRIMARY KEY (student_fn, student_ln)
        )");

        $stm = "INSERT INTO students (student_fn, student_ln, degree_type, degree_subject) VALUES (?, ?, ?, ?)";
        $rows = [
            ['Anna', 'Alpha', 'BA', 'ENGL'],
            ['Betty', 'Beta', 'MA', 'HIST'],
            ['Clara', 'Clark', 'BS', 'MATH'],
            ['Donna', 'Delta', 'BA', 'ENGL'],
            ['Edna', 'Epsilon', 'MA', 'HIST'],
            ['Fiona', 'Phi', 'BS', 'MATH'],
            ['Gina', 'Gamma', 'BA', 'ENGL'],
            ['Hanna', 'Eta', 'MA', 'HIST'],
            ['Ione', 'Iota', 'BS', 'MATH'],
            ['Julia', 'Jones', 'BA', 'ENGL'],
            ['Kara', 'Kappa', 'MA', 'HIST'],
            ['Lana', 'Lambda', 'BS', 'MATH'],
        ];
        foreach ($rows as $row) {
            $this->connection->perform($stm, $row);
        }
    }

    protected function courses()
    {
        $this->connection->query("CREATE TABLE courses (
            course_subject CHAR(4),
            course_number INT,
            title VARCHAR(20),
            PRIMARY KEY (course_subject, course_number)
        )");

        $stm = "INSERT INTO courses (course_subject, course_number, title) VALUES (?, ?, ?)";
        $rows = [
            ['ENGL', 100, 'Composition'],
            ['ENGL', 200, 'Creative Writing'],
            ['ENGL', 300, 'Shakespeare'],
            ['ENGL', 400, 'Dickens'],
            ['HIST', 100, 'World History'],
            ['HIST', 200, 'US History'],
            ['HIST', 300, 'Victorian History'],
            ['HIST', 400, 'Recent History'],
            ['MATH', 100, 'Algebra'],
            ['MATH', 200, 'Trigonometry'],
            ['MATH', 300, 'Calculus'],
            ['MATH', 400, 'Statistics'],
        ];
        foreach ($rows as $row) {
            $this->connection->perform($stm, $row);
        }
    }

    protected function enrollments()
    {
        $this->connection->query("CREATE TABLE enrollments (
            student_fn VARCHAR(10),
            student_ln VARCHAR(10),
            course_subject CHAR(4),
            course_number INT,
            grade INT,
            points INT,
            PRIMARY KEY (student_ln, student_fn, course_subject, course_number)
        )");

        $courses = $this->connection->fetchAll('SELECT * FROM courses ORDER BY course_number, course_subject');
        $students = $this->connection->fetchAll('SELECT * FROM students');

        $stm = 'INSERT INTO enrollments (
            student_fn, student_ln, course_subject, course_number, grade, points
        ) VALUES (
            :student_fn, :student_ln, :course_subject, :course_number, :grade, :points
        )';

        foreach ($students as $i => $student) {
            $keys = [
                (($i + 0) % 12),
                (($i + 1) % 12),
                (($i + 2) % 12),
            ];
            foreach ($keys as $key) {
                $grade = 65 + $key * 3;
                switch (true) {
                    case $grade >= 90:
                        $points = 4;
                        break;
                    case $grade >= 80:
                        $points = 3;
                        break;
                    case $grade >= 70:
                        $points = 2;
                        break;
                    case $grade >= 60:
                        $points = 1;
                        break;
                    default:
                        $points = 0;
                }
                $this->connection->perform($stm, [
                    'student_fn' => $student['student_fn'],
                    'student_ln' => $student['student_ln'],
                    'course_subject' => $courses[$key]['course_subject'],
                    'course_number' => $courses[$key]['course_number'],
                    'grade' => $grade,
                    'points' => $points,
                ]);
            }
        }
    }

    public function gpas()
    {
        $this->connection->query("CREATE TABLE gpas (
            student_fn VARCHAR(10),
            student_ln VARCHAR(10),
            gpa DECIMAL(4,3),
            PRIMARY KEY (student_fn, student_ln)
        )");

        $students = $this->connection->fetchAll(
            'SELECT student_fn, student_ln, ROUND(AVG(points), 3) AS gpa FROM enrollments GROUP BY student_fn, student_ln'
        );

        $stm = 'INSERT INTO gpas (student_fn, student_ln, gpa) VALUES (?, ?, ?)';
        foreach ($students as $student) {
            $this->connection->perform($stm, [
                $student['student_fn'],
                $student['student_ln'],
                $student['gpa']
            ]);
        }
    }
}
