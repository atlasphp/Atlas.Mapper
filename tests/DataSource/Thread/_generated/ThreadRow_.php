<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Thread\_generated;

use Atlas\Table\Row;

/**
 * @property mixed $thread_id INTEGER
 * @property mixed $author_id INTEGER NOT NULL
 * @property mixed $subject VARCHAR(255) NOT NULL
 * @property mixed $body TEXT NOT NULL
 */
abstract class ThreadRow_ extends Row
{
    protected mixed $thread_id = null;
    protected mixed $author_id = null;
    protected mixed $subject = null;
    protected mixed $body = null;
}
