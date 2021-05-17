<?php
namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\Related\Where;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class PageRelated extends Related
{
    #[OneToMany(on: ['page_id' => 'related_id'])]
    #[Where('related_type = ', 'page')]
    protected CommentRecordSet $comments;
}
