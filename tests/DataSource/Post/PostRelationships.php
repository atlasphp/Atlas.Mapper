<?php
namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Comment\Comment;

class PostRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->oneToMany('comments', Comment::CLASS, ['post_id' => 'related_id'])
            ->where('related_type = ', 'post');
    }
}
