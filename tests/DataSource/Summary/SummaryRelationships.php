<?php
namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Thread\Thread;

class SummaryRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->oneToOne('thread', Thread::CLASS);
    }
}
