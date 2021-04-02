<?php
namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Tagging\Tagging;

class TagRelationships extends MapperRelationships
{
    protected function define() : void
    {
        $this->oneToMany('taggings', Tagging::CLASS)
            ->onDeleteCascade();
    }
}
