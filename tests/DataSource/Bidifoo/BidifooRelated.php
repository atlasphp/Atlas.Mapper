<?php
namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Bidibar\BidibarRecord;

class BidifooRelated extends Related
{
    #[Define\OneToOneBidi(on:['bidibar_id' => 'bidibar_id'])]
    protected ?BidibarRecord $bidibar;
}
