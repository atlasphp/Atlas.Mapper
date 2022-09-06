<?php
namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Bidifoo\Bidifoo;

class BidibarRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->oneToOneBidi('bidifoo', Bidifoo::CLASS, [
            'bidifoo_id' => 'bidifoo_id'
        ]);
    }
}
