<?php
namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Comment\Comment;

class VideoRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->oneToMany('comments', Comment::CLASS, ['page_id' => 'related_id'])
            ->where('related_type = ', 'video');
    }
}
