<?php
namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\DataSource\Page\Page;
use Atlas\Mapper\DataSource\Post\Post;
use Atlas\Mapper\DataSource\Video\Video;
use Atlas\Mapper\Related;

class CommentRelated extends Related
{
    #[Related\ManyToOneVariant(column: 'related_type')]
    #[Related\Variant(value: 'page',  mapper: Page::CLASS,  on: ['related_id' => 'page_id'])]
    #[Related\Variant(value: 'post',  mapper: Post::CLASS,  on: ['related_id' => 'post_id'])]
    #[Related\Variant(value: 'video', mapper: Video::CLASS, on: ['related_id' => 'video_id'])]
    protected mixed $commentable;
}
