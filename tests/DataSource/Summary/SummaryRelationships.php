<?php
namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Thread\Thread;

class SummaryRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->oneToOne('thread', Thread::CLASS);
    }
}
