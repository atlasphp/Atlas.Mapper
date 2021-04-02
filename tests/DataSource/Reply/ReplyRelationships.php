<?php
namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Author\Author;

class ReplyRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->manyToOne('author', Author::CLASS);
    }
}
