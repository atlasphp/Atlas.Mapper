<?php
namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\Attribute\Where;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class PageRelated extends Related
{
    #[OneToMany(on: ['page_id' => 'related_id'])]
    #[Where('related_type = ', 'page')]
    protected NotLoaded|CommentRecordSet $comments;
}
