<?php
namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Bidifoo\Bidifoo;

class BidibarRelationships extends MapperRelationships
{
    protected function define()
    {
        $this->oneToOneBidi('bidifoo', Bidifoo::CLASS, [
            'bidifoo_id' => 'bidifoo_id'
        ]);
    }
}
