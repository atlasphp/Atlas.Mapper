<?php
namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Thread\ThreadRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class AuthorRelated extends Related
{
    #[OneToMany]
    protected NotLoaded|ReplyRecordSet $replies;

    #[OneToMany]
    protected NotLoaded|ThreadRecordSet $threads;
}
