<?php
namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;
use Atlas\Mapper\DataSource\Tag\TagRecord;

class TaggingRelated extends Related
{
    #[Define\ManyToOne]
    protected ?ThreadRecord $thread;

    #[Define\ManyToOne]
    protected ?TagRecord $tag;
}
