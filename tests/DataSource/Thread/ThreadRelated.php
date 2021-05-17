<?php
namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related\OnDelete;
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
    protected ?AuthorRecord $author;

    #[OneToOne]
    #[OnDelete('initDeleted')]
    protected ?SummaryRecord $summary;

    #[OneToMany]
    #[OnDelete('setDelete')]
    protected ReplyRecordSet $replies;

    #[OneToMany]
    #[OnDelete('setNull')]
    protected TaggingRecordSet $taggings;

    #[ManyToMany(through: 'taggings')]
    protected TagRecordSet $tags;
}
