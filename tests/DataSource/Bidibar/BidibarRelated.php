<?php
namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOneBidi;
use Atlas\Mapper\DataSource\Bidifoo\BidifooRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class BidibarRelated extends Related
{
    #[OneToOneBidi(on: ['bidifoo_id' => 'bidifoo_id'])]
    protected NotLoaded|null|BidifooRecord $bidifoo;
}
