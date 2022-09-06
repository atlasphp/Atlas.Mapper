<?php
namespace Atlas\Mapper\DataSource\Comment;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Page\Page;
use Atlas\Mapper\DataSource\Post\Post;
use Atlas\Mapper\DataSource\Video\Video;

class CommentRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->manyToOneVariant('commentable', 'related_type')
            ->type('page', Page::CLASS, ['related_id' => 'page_id'])
            ->type('post', Post::CLASS, ['related_id' => 'post_id'])
            ->type('video', Video::CLASS, ['related_id' => 'video_id']);
    }
}
