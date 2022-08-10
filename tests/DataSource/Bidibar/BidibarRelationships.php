<?php
namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Bidifoo\Bidifoo;
use Atlas\Mapper\DataSource\Bidifoo\BidifooRecord;

class BidibarRelationships extends MapperRelationships
{
    #[Define\OneToOneBidi(on: [
        'bidifoo_id' => 'bidifoo_id'
    ])]
    protected ?BidifooRecord $bidifoo;

    // protected function define()
    // {
    //     $this->oneToOneBidi('bidifoo', Bidifoo::CLASS, [
    //         'bidifoo_id' => 'bidifoo_id'
    //     ]);
    // }
}
