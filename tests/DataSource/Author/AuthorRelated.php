<?php
namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Thread\ThreadRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class AuthorRelated extends Related
{
    #[OneToMany]
    protected ReplyRecordSet $replies;

    #[OneToMany]
    protected ThreadRecordSet $threads;
}
