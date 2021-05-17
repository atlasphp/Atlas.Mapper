<?php
namespace Atlas\Mapper\DataSource\Tag;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related\OnDelete;
use Atlas\Mapper\DataSource\Tagging\TaggingRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class TagRelated extends Related
{
    #[OneToMany]
    #[OnDelete('cascade')]
    protected TaggingRecordSet $taggings;
}
