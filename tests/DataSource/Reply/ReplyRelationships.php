<?php
namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Author\Author;

class ReplyRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->manyToOne('author', Author::CLASS);
    }
}
