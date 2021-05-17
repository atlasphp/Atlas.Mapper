<?php
namespace Atlas\Mapper\DataSource\Post;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related\Where;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class PostRelated extends Related
{
    #[OneToMany(on: ['post_id' => 'related_id'])]
    #[Where('related_type = ', 'post')]
    protected CommentRecordSet $comments;
}
