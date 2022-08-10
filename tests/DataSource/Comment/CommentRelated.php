<?php
namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Page\PageRecord;
use Atlas\Mapper\DataSource\Post\PostRecord;
use Atlas\Mapper\DataSource\Video\VideoRecord;

class CommentRelated extends Related
{
    #[Define\ManyToOneVariant(column:'related_type')]
    #[Define\Variant('page', PageRecord::CLASS, ['related_id' => 'page_id'])]
    #[Define\Variant('post', PostRecord::CLASS, ['related_id' => 'post_id'])]
    #[Define\Variant('video', VideoRecord::CLASS, ['related_id' => 'video_id'])]
    protected mixed $commentable;
}
