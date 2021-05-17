<?php
namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class SummaryRelated extends Related
{
    #[OneToOne]
    protected ?ThreadRecord $thread;
}
