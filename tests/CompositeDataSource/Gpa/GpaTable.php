<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Gpa;

use Atlas\Table\Table;

/**
 * @method GpaRow|null fetchRow($primaryVal)
 * @method GpaRow[] fetchRows(array $primaryVals)
 * @method GpaTableSelect select(array $whereEquals = [])
 * @method GpaRow newRow(array $cols = [])
 * @method GpaRow newSelectedRow(array $cols)
 */
class GpaTable extends Table
{
    const DRIVER = 'sqlite';

    const NAME = 'gpas';

    const COLUMNS = [
        'student_fn' => [
            'name' => 'student_fn',
            'type' => 'VARCHAR',
            'size' => 10,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => true,
            'options' => null,
        ],
        'student_ln' => [
            'name' => 'student_ln',
            'type' => 'VARCHAR',
            'size' => 10,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => true,
            'options' => null,
        ],
        'gpa' => [
            'name' => 'gpa',
            'type' => 'DECIMAL',
            'size' => 4,
            'scale' => 3,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'student_fn',
        'student_ln',
        'gpa',
    ];

    const COLUMN_DEFAULTS = [
        'student_fn' => null,
        'student_ln' => null,
        'gpa' => null,
    ];

    const PRIMARY_KEY = [
        'student_fn',
        'student_ln',
    ];

    const AUTOINC_COLUMN = null;

    const AUTOINC_SEQUENCE = null;
}
