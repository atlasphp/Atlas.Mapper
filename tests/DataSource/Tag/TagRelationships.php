<?php
namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Define;
use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\DataSource\Tagging\Tagging;
use Atlas\Mapper\DataSource\Tagging\TaggingRecordSet;

class TagRelationships extends MapperRelationships
{
    #[Define\OneToMany]
    #[Define\OnDelete\Cascade]
    protected TaggingRecordSet $taggings;

    // protected function define()
    // {
    //     $this->oneToMany('taggings', Tagging::CLASS)
    //         ->onDeleteCascade();
    // }
}
