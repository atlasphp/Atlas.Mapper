<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class PostRelated extends _generated\PostRelated_
{
    #[Define\OneToMany(on: ['post_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'post')]
    protected CommentRecordSet $comments;
}
