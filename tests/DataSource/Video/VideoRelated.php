<?php
namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Comment\Comment;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class VideoRelated extends Related
{
    #[Define\OneToMany(on: ['video_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'video')]
    protected CommentRecordSet $comments;
}
