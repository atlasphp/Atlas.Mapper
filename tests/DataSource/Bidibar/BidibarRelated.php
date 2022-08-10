<?php
namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Bidifoo\BidifooRecord;

class BidibarRelated extends Related
{
    #[Define\OneToOneBidi(on: ['bidifoo_id' => 'bidifoo_id'])]
    protected ?BidifooRecord $bidifoo;
}
