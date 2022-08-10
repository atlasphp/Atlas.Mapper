<?php
namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Comment\Comment;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class VideoRelationships extends MapperRelationships
{
    #[Define\OneToMany(on: ['video_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'video')]
    protected CommentRecordSet $comments;

    // protected function define()
    // {
    //     $this->oneToMany('comments', Comment::CLASS, ['page_id' => 'related_id'])
    //         ->where('related_type = ', 'video');
    // }
}
