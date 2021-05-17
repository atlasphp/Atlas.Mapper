<?php
namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related\Where;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class VideoRelated extends Related
{
    #[OneToMany(on: ['video_id' => 'related_id'])]
    #[Where('related_type = ', 'video')]
    protected CommentRecordSet $comments;
}
