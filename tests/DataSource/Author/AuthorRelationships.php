<?php
namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Reply\Reply;
use Atlas\Mapper\DataSource\Thread\Thread;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Thread\ThreadRecordSet;

class AuthorRelationships extends MapperRelationships
{
    #[Define\OneToMany]
    protected ReplyRecordSet $replies;

    #[Define\OneToMany]
    protected ThreadRecordSet $threads;

    // protected function define()
    // {
    //     $this->oneToMany('replies', Reply::CLASS);
    //     $this->oneToMany('threads', Thread::CLASS);
    // }
}
