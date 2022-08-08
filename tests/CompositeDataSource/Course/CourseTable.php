<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Course;

use Atlas\Table\Table;

/**
 * @method CourseRow|null fetchRow($primaryVal)
 * @method CourseRow[] fetchRows(array $primaryVals)
 * @method CourseTableSelect select(array $whereEquals = [])
 * @method CourseRow newRow(array $cols = [])
 * @method CourseRow newSelectedRow(array $cols)
 */
class CourseTable extends Table
{
    public const ROW_CLASS = CourseRow::CLASS;

    public const COMPOSITE_KEY = true;

    const DRIVER = 'sqlite';

    const NAME = 'courses';

    const COLUMNS = [
        'course_subject' => [
            'name' => 'course_subject',
            'type' => 'CHAR',
            'size' => 4,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => true,
            'options' => null,
        ],
        'course_number' => [
            'name' => 'course_number',
            'type' => 'INT',
            'size' => null,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => true,
            'options' => null,
        ],
        'title' => [
            'name' => 'title',
            'type' => 'VARCHAR',
            'size' => 20,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'course_subject',
        'course_number',
        'title',
    ];

    const COLUMN_DEFAULTS = [
        'course_subject' => null,
        'course_number' => null,
        'title' => null,
    ];

    const PRIMARY_KEY = [
        'course_subject',
        'course_number',
    ];

    const AUTOINC_COLUMN = null;

    const AUTOINC_SEQUENCE = null;
}
