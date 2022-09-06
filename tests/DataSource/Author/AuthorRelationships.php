<?php
namespace Atlas\Mapper\DataSource\Author;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Reply\Reply;
use Atlas\Mapper\DataSource\Thread\Thread;

class AuthorRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->oneToMany('replies', Reply::CLASS);
        $this->oneToMany('threads', Thread::CLASS);
    }
}
