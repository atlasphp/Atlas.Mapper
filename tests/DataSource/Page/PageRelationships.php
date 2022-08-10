<?php
namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Comment\Comment;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class PageRelationships extends MapperRelationships
{
    #[Define\OneToMany(on: ['page_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'page')]
    protected CommentRecordSet $comments;

    // protected function define()
    // {
    //     $this->oneToMany('comments', Comment::CLASS, ['page_id' => 'related_id'])
    //         ->where('related_type = ', 'page');
    // }
}
