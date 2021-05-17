<?php
namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\ManyToOneVariant;
use Atlas\Mapper\Related\Variant;
use Atlas\Mapper\DataSource\Page\Page;
use Atlas\Mapper\DataSource\Post\Post;
use Atlas\Mapper\DataSource\Video\Video;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class CommentRelated extends Related
{
    #[ManyToOneVariant(column: 'related_type')]
    #[Variant(value: 'page',  mapper: Page::CLASS,  on: ['related_id' => 'page_id'])]
    #[Variant(value: 'post',  mapper: Post::CLASS,  on: ['related_id' => 'post_id'])]
    #[Variant(value: 'video', mapper: Video::CLASS, on: ['related_id' => 'video_id'])]
    protected mixed $commentable;
}
