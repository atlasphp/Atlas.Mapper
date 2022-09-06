<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Video;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class VideoRelated extends _generated\VideoRelated_
{
    #[Define\OneToMany(on: ['page_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'video')]
    protected CommentRecordSet $comments;
}
