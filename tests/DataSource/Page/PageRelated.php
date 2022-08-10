<?php
namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class PageRelated extends Related
{
    #[Define\OneToMany(on: ['page_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'page')]
    protected CommentRecordSet $comments;
}
