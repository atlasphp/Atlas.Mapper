<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Video;

use Atlas\Table\Table;

/**
 * @method VideoRow|null fetchRow($primaryVal)
 * @method VideoRow[] fetchRows(array $primaryVals)
 * @method VideoTableSelect select(array $whereEquals = [])
 * @method VideoRow newRow(array $cols = [])
 * @method VideoRow newSelectedRow(array $cols)
 */
class VideoTable extends Table
{
    public const ROW_CLASS = VideoRow::CLASS;

    const DRIVER = 'sqlite';

    const NAME = 'videos';

    const COLUMNS = [
        'video_id' => [
            'name' => 'video_id',
            'type' => 'INTEGER',
            'size' => null,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => true,
            'primary' => true,
            'options' => null,
        ],
        'title' => [
            'name' => 'title',
            'type' => 'VARCHAR',
            'size' => 255,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'url' => [
            'name' => 'url',
            'type' => 'VARCHAR',
            'size' => 255,
            'scale' => null,
            'notnull' => false,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'video_id',
        'title',
        'url',
    ];

    const COLUMN_DEFAULTS = [
        'video_id' => null,
        'title' => null,
        'url' => null,
    ];

    const PRIMARY_KEY = [
        'video_id',
    ];

    const AUTOINC_COLUMN = 'video_id';

    const AUTOINC_SEQUENCE = null;
}