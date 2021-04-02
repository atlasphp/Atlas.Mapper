<?php
namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Thread\Thread;
use Atlas\Mapper\DataSource\Tag\Tag;

class TaggingRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->manyToOne('thread', Thread::CLASS);
        $this->manyToOne('tag', Tag::CLASS);
    }
}
