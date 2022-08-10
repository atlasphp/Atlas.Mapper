<?php
namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Page\Page;
use Atlas\Mapper\DataSource\Post\Post;
use Atlas\Mapper\DataSource\Video\Video;
use Atlas\Mapper\DataSource\Page\PageRecord;
use Atlas\Mapper\DataSource\Post\PostRecord;
use Atlas\Mapper\DataSource\Video\VideoRecord;

class CommentRelationships extends MapperRelationships
{
    #[Define\ManyToOneVariant(column:'related_type')]
    #[Define\Variant('page', PageRecord::CLASS, ['related_id' => 'page_id'])]
    #[Define\Variant('post', PostRecord::CLASS, ['related_id' => 'post_id'])]
    #[Define\Variant('video', VideoRecord::CLASS, ['related_id' => 'video_id'])]
    protected null|PageRecord|PostRecord|VideoRecord $commentable;

    // protected function define()
    // {
    //     $this->manyToOneVariant('commentable', 'related_type')
    //         ->type('page', Page::CLASS, ['related_id' => 'page_id'])
    //         ->type('post', Post::CLASS, ['related_id' => 'post_id'])
    //         ->type('video', Video::CLASS, ['related_id' => 'video_id']);
    // }
}
