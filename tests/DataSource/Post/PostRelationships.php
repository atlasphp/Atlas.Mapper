<?php
namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Comment\Comment;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class PostRelationships extends MapperRelationships
{
    #[Define\OneToMany(on: ['post_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'post')]
    protected CommentRecordSet $comments;

    // protected function define()
    // {
    //     $this->oneToMany('comments', Comment::CLASS, ['post_id' => 'related_id'])
    //         ->where('related_type = ', 'post');
    // }
}
