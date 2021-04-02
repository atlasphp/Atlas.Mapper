<?php
namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Comment\Comment;

class PageRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->oneToMany('comments', Comment::CLASS, ['page_id' => 'related_id'])
            ->where('related_type = ', 'page');
    }
}
