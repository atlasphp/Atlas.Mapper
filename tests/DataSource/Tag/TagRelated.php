<?php
namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Tagging\TaggingRecordSet;

class TagRelated extends Related
{
    #[Define\OneToMany]
    #[Define\OnDelete\Cascade]
    protected TaggingRecordSet $taggings;
}
