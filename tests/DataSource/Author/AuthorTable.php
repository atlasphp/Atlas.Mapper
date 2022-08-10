<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Author;

use Atlas\Table\Table;

/**
 * @method AuthorRow|null fetchRow($primaryVal)
 * @method AuthorRow[] fetchRows(array $primaryVals)
 * @method AuthorTableSelect select(array $whereEquals = [])
 * @method AuthorRow newRow(array $cols = [])
 * @method AuthorRow newSelectedRow(array $cols)
 */
class AuthorTable extends Table
{
    public const ROW_CLASS = AuthorRow::CLASS;

    const DRIVER = 'sqlite';

    const NAME = 'authors';

    const COLUMNS = [
        'author_id' => [
            'name' => 'author_id',
            'type' => 'INTEGER',
            'size' => null,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => true,
            'primary' => true,
            'options' => null,
        ],
        'name' => [
            'name' => 'name',
            'type' => 'VARCHAR',
            'size' => 10,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'author_id',
        'name',
    ];

    const COLUMN_DEFAULTS = [
        'author_id' => null,
        'name' => null,
    ];

    const PRIMARY_KEY = [
        'author_id',
    ];

    const AUTOINC_COLUMN = 'author_id';

    const AUTOINC_SEQUENCE = null;
}