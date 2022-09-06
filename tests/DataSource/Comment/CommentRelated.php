<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Page\PageRecord;
use Atlas\Mapper\DataSource\Post\PostRecord;
use Atlas\Mapper\DataSource\Video\VideoRecord;

class CommentRelated extends _generated\CommentRelated_
{
    #[Define\ManyToOneVariant(column: 'related_type')]
    #[Define\Variant('page', PageRecord::CLASS, on: ['related_id' => 'page_id'])]
    #[Define\Variant('post', PostRecord::CLASS, on: ['related_id' => 'post_id'])]
    #[Define\Variant('video', VideoRecord::CLASS, on: ['related_id' => 'video_id'])]
    protected mixed $commentable;
}
