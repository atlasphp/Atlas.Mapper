<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Summary;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Thread\ThreadRecord;

class SummaryRelated extends _generated\SummaryRelated_
{
    #[Define\OneToOne]
    protected ?ThreadRecord $thread;
}
