<?php
namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Bidibar\Bidibar;
use Atlas\Mapper\DataSource\Bidibar\BidibarRecord;

class BidifooRelationships extends MapperRelationships
{
    #[Define\OneToOneBidi(on:[
        'bidibar_id' => 'bidibar_id'
    ])]
    protected ?BidibarRecord $bidibar;

    // protected function define()
    // {
    //     $this->oneToOneBidi('bidibar', Bidibar::CLASS, [
    //         'bidibar_id' => 'bidibar_id'
    //     ]);
    // }
}
