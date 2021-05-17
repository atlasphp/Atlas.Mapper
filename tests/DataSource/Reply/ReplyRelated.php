<?php
namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOne;
use Atlas\Mapper\DataSource\Author\AuthorRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class ReplyRelated extends Related
{
    #[ManyToOne]
    protected ?AuthorRecord $author;
}
