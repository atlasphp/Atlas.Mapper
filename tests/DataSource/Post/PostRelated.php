<?php
namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class PostRelated extends Related
{
    #[Define\OneToMany(on: ['post_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'post')]
    protected CommentRecordSet $comments;
}
