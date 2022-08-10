<?php
namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Thread\Thread;
use Atlas\Mapper\DataSource\Tag\Tag;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;
use Atlas\Mapper\DataSource\Tag\TagRecord;

class TaggingRelationships extends MapperRelationships
{
    #[Define\ManyToOne]
    protected ?ThreadRecord $thread;

    #[Define\ManyToOne]
    protected ?TagRecord $tag;

    // protected function define()
    // {
    //     $this->manyToOne('thread', Thread::CLASS);
    //     $this->manyToOne('tag', Tag::CLASS);
    // }
}
