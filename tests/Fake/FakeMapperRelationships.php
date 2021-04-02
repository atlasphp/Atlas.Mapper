<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\MapperRelationships;

class FakeMapperRelationships extends MapperRelationships
{
    protected function define() : void
    {
        // intentionally blow up
        $this->oneToOne('id', 'Foo');
    }
}
