<?php
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Student;

use Atlas\Mapper\Record;

/**
 * @method StudentRow getRow()
 */
class StudentRecord extends Record
{
    use StudentFields;
}
