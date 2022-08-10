<?php
namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Thread\Thread;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;

class SummaryRelationships extends MapperRelationships
{
    #[Define\OneToOne]
    protected ?ThreadRecord $thread;

    // protected function define()
    // {
    //     $this->oneToOne('thread', Thread::CLASS);
    // }
}
