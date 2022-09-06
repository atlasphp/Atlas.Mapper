<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\CompositeDataSource\Gpa\_generated;

use Atlas\Table\Table;
use Atlas\Mapper\CompositeDataSource\Gpa\GpaRow;
use Atlas\Mapper\CompositeDataSource\Gpa\GpaTableSelect;

/**
 * @method ?GpaRow fetchRow(mixed $primaryVal)
 * @method GpaRow[] fetchRows(array $primaryVals)
 * @method GpaTableSelect select(array $whereEquals = [])
 * @method GpaRow newRow(array $cols = [])
 * @method GpaRow newSelectedRow(array $cols)
 */
abstract class GpaTable_ extends Table
{
    public const DRIVER = 'sqlite';

    public const NAME = 'gpas';

    public const COLUMNS = [
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

    public const PRIMARY_KEY = [
        'student_fn',
        'student_ln',    ];

    public const COMPOSITE_KEY = true;

    public const AUTOINC_COLUMN = null;

    public const AUTOINC_SEQUENCE = null;

    public const ROW_CLASS = GpaRow::CLASS;
}
