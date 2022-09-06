<?php
namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Bidibar\Bidibar;

class BidifooRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->oneToOneBidi('bidibar', Bidibar::CLASS, [
            'bidibar_id' => 'bidibar_id'
        ]);
    }
}
