<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Record;

/**
 * @method AuthorRow getRow()
 */
class AuthorRecord extends Record
{
    use AuthorFields;
}
