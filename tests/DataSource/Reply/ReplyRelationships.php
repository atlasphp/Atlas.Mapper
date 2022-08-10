<?php
namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Author\Author;
use Atlas\Mapper\DataSource\Author\AuthorRecord;

class ReplyRelationships extends MapperRelationships
{
    #[Define\ManyToOne]
    protected ?AuthorRecord $author;

    // protected function define()
    // {
    //     $this->manyToOne('author', Author::CLASS);
    // }
}
