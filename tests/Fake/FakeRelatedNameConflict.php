<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Testing\DataSource\Summary\SummaryRecord;

class FakeRelatedNameConflict extends Related
{
	#[Define\OneToOne]
	protected SummaryRecord $id;
}
