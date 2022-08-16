<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\MapperRelationships;

class FakeMapperRelationships extends MapperRelationships
{
    public function setFake($name, $relationship)
    {
        $this->relationships[$name] = $relationship;
    }
}
