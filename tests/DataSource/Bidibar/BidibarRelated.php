<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Bidifoo\BidifooRecord;

class BidibarRelated extends _generated\BidibarRelated_
{
    #[Define\OneToOneBidi(on: ['bidifoo_id' => 'bidifoo_id'])]
    protected ?BidifooRecord $bidifoo;
}
