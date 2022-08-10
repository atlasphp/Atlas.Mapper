<?php
namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Thread\ThreadRecordSet;

class AuthorRelated extends Related
{
    #[Define\OneToMany]
    protected ReplyRecordSet $replies;

    #[Define\OneToMany]
    protected ThreadRecordSet $threads;
}
