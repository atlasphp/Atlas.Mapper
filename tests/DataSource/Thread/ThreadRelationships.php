<?php
namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Author\Author;
use Atlas\Mapper\DataSource\Summary\Summary;
use Atlas\Mapper\DataSource\Reply\Reply;
use Atlas\Mapper\DataSource\Tag\Tag;
use Atlas\Mapper\DataSource\Tagging\Tagging;
use Atlas\Mapper\DataSource\Author\AuthorRecord;
use Atlas\Mapper\DataSource\Summary\SummaryRecord;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Tag\TagRecordSet;
use Atlas\Mapper\DataSource\Tagging\TaggingRecordSet;

class ThreadRelationships extends MapperRelationships
{
    #[Define\ManyToOne]
    protected ?AuthorRecord $author;

    #[Define\OneToOne]
    #[Define\OnDelete\InitDeleted]
    protected ?SummaryRecord $summary;

    #[Define\OneToMany]
    #[Define\OnDelete\SetDelete]
    protected ReplyRecordSet $replies;

    #[Define\OneToMany]
    #[Define\OnDelete\SetNull]
    protected TaggingRecordSet $taggings;

    #[Define\ManyToMany(through: 'taggings')]
    protected TagRecordSet $tags;

    // protected function define()
    // {
    //     $this->manyToOne('author', Author::CLASS);

    //     $this->oneToOne('summary', Summary::CLASS)
    //         ->onDeleteInitDeleted();

    //     $this->oneToMany('replies', Reply::CLASS)
    //         ->onDeleteSetDelete();

    //     $this->oneToMany('taggings', Tagging::CLASS)
    //         ->onDeleteSetNull();

    //     $this->manyToMany('tags', Tag::CLASS, 'taggings');
    // }
}
