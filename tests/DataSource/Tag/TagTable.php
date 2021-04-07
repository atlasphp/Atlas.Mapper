<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Table\Table;

/**
 * @method TagRow|null fetchRow($primaryVal)
 * @method TagRow[] fetchRows(array $primaryVals)
 * @method TagTableSelect select(array $whereEquals = [])
 * @method TagRow newRow(array $cols = [])
 * @method TagRow newSelectedRow(array $cols)
 */
class TagTable extends Table
{
    const DRIVER = 'sqlite';

    const NAME = 'tags';

    const COLUMNS = [
        'tag_id' => [
            'name' => 'tag_id',
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
        'tag_id',
        'name',
    ];

    const COLUMN_DEFAULTS = [
        'tag_id' => null,
        'name' => null,
    ];

    const PRIMARY_KEY = [
        'tag_id',
    ];

    const AUTOINC_COLUMN = 'tag_id';

    const AUTOINC_SEQUENCE = null;
}