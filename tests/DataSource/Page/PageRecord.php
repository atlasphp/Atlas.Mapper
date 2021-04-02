<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Record;

/**
 * @method PageRow getRow()
 */
class PageRecord extends Record
{
    use PageFields;
}
