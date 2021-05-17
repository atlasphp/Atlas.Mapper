<?php
namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
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
