<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Tagging;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;
use Atlas\Mapper\DataSource\Tag\TagRecord;

class TaggingRelated extends _generated\TaggingRelated_
{
    #[Define\ManyToOne]
    protected ?ThreadRecord $thread;

    #[Define\ManyToOne]
    protected ?TagRecord $tag;
}
