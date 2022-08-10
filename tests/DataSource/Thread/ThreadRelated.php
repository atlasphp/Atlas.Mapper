<?php
namespace Atlas\Mapper\DataSource\Thread;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Author\AuthorRecord;
use Atlas\Mapper\DataSource\Summary\SummaryRecord;
use Atlas\Mapper\DataSource\Reply\ReplyRecordSet;
use Atlas\Mapper\DataSource\Tag\TagRecordSet;
use Atlas\Mapper\DataSource\Tagging\TaggingRecordSet;

class ThreadRelated extends Related
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
}