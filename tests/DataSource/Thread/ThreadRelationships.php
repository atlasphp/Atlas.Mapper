<?php
namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Author\Author;
use Atlas\Mapper\DataSource\Summary\Summary;
use Atlas\Mapper\DataSource\Reply\Reply;
use Atlas\Mapper\DataSource\Tag\Tag;
use Atlas\Mapper\DataSource\Tagging\Tagging;

class ThreadRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->manyToOne('author', Author::CLASS);

        $this->oneToOne('summary', Summary::CLASS)
            ->onDeleteInitDeleted();

        $this->oneToMany('replies', Reply::CLASS)
            ->onDeleteSetDelete();

        $this->oneToMany('taggings', Tagging::CLASS)
            ->onDeleteSetNull();

        $this->manyToMany('tags', Tag::CLASS, 'taggings');
    }
}
