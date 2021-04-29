<?php
namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\Attribute\OnDelete;
use Atlas\Mapper\DataSource\Author\AuthorRecord;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Summary\SummaryRecord;
use Atlas\Mapper\DataSource\Tag\TagRecordSet;
use Atlas\Mapper\DataSource\Tagging\TaggingRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class ThreadRelated extends Related
{
    #[ManyToOne]
    protected NotLoaded|null|AuthorRecord $author;

    #[OneToOne]
    #[OnDelete('initDeleted')]
    protected NotLoaded|null|SummaryRecord $summary;

    #[OneToMany]
    #[OnDelete('setDelete')]
    protected NotLoaded|ReplyRecordSet $replies;

    #[OneToMany]
    #[OnDelete('setNull')]
    protected NotLoaded|TaggingRecordSet $taggings;

    #[ManyToMany(through: 'taggings')]
    protected NotLoaded|TagRecordSet $tags;
}
