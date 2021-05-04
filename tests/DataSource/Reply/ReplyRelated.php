<?php
namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOne;
use Atlas\Mapper\DataSource\Author\AuthorRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class ReplyRelated extends Related
{
    #[ManyToOne]
    protected ?AuthorRecord $author;
}
