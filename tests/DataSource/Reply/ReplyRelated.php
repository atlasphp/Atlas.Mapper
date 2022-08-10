<?php
namespace Atlas\Mapper\DataSource\Reply;

use Atlas\Mapper\Define;
use Atlas\Mapper\Related;
use Atlas\Mapper\DataSource\Author\AuthorRecord;

class ReplyRelated extends Related
{
    #[Define\ManyToOne]
    protected ?AuthorRecord $author;
}
