<?php
declare(strict_types=1);

namespace Atlas\Mapper\DataSource\Page;

use Atlas\Mapper\Define;
use Atlas\Mapper\DataSource\Comment\CommentRecordSet;

class PageRelated extends _generated\PageRelated_
{
    #[Define\OneToMany(on: ['page_id' => 'related_id'])]
    #[Define\Where('related_type = ', 'page')]
    protected CommentRecordSet $comments;
}
