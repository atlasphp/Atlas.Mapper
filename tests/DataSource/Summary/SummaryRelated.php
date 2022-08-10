<?php
namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;

class SummaryRelated extends Related
{
    #[Define\OneToOne]
    protected ?ThreadRecord $thread;
}
