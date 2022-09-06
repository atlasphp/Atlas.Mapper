<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course\_generated;

use Atlas\Mapper\Record;

/**
 * @property mixed $course_subject CHAR(4)
 * @property mixed $course_number INT
 * @property mixed $title VARCHAR(20)
 * @property \Atlas\Mapper\CompositeDataSource\Enrollment\EnrollmentRecordSet $enrollments
 * @method \Atlas\Mapper\CompositeDataSource\Course\CourseRow getRow()
 */
abstract class CourseRecord_ extends Record
{
}
