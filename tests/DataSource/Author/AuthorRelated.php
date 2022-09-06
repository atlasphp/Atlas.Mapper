<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Thread\ThreadRecordSet;

class AuthorRelated extends _generated\AuthorRelated_
{
    #[Define\OneToMany]
    protected ReplyRecordSet $replies;

    #[Define\OneToMany]
    protected ThreadRecordSet $threads;
}
