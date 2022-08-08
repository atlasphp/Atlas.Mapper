<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Mapper\Record;

/**
 * @method CourseRow getRow()
 */
class CourseRecord extends Record
{
    use CourseFields;
}
