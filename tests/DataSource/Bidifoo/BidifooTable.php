<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Table\Table;

/**
 * @method BidifooRow|null fetchRow($primaryVal)
 * @method BidifooRow[] fetchRows(array $primaryVals)
 * @method BidifooTableSelect select(array $whereEquals = [])
 * @method BidifooRow newRow(array $cols = [])
 * @method BidifooRow newSelectedRow(array $cols)
 */
class BidifooTable extends Table
{
    const DRIVER = 'sqlite';

    const NAME = 'bidifoos';

    const COLUMNS = [
        'bidifoo_id' => [
            'name' => 'bidifoo_id',
            'type' => 'INTEGER',
            'size' => null,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => true,
            'primary' => true,
            'options' => null,
        ],
        'bidibar_id' => [
            'name' => 'bidibar_id',
            'type' => 'INTEGER',
            'size' => null,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'name' => [
            'name' => 'name',
            'type' => 'VARCHAR',
            'size' => 10,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'bidifoo_id',
        'bidibar_id',
        'name',
    ];

    const COLUMN_DEFAULTS = [
        'bidifoo_id' => null,
        'bidibar_id' => null,
        'name' => null,
    ];

    const PRIMARY_KEY = [
        'bidifoo_id',
    ];

    const AUTOINC_COLUMN = 'bidifoo_id';

    const AUTOINC_SEQUENCE = null;
}