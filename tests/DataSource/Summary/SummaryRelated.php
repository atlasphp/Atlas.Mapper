<?php
namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class SummaryRelated extends Related
{
    #[OneToOne]
    protected NotLoaded|null|ThreadRecord $thread;
}
