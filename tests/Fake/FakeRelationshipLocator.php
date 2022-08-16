<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Relationship\RelationshipLocator;

class FakeRelationshipLocator extends RelationshipLocator
{
    public function setFake($name, $relationship)
    {
        $this->instances[$name] = $relationship;
    }
}
