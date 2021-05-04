<?php
namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\Attribute\OnDelete;
use Atlas\Mapper\DataSource\Tagging\TaggingRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class TagRelated extends Related
{
    #[OneToMany]
    #[OnDelete('cascade')]
    protected TaggingRecordSet $taggings;
}
