<?php
namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Tagging\Tagging;

class TagRelationships extends \UpgradeRelationships
{
    public function define()
    {
        $this->oneToMany('taggings', Tagging::CLASS)
            ->onDeleteCascade();
    }
}
