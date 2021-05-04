<?php
namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\DataSource\Tag\TagRecord;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class TaggingRelated extends Related
{
    #[ManyToOne]
    protected ?ThreadRecord $thread;

    #[ManyToOne]
    protected ?TagRecord $tag;
}
