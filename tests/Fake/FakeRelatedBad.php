<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;
use Atlas\Mapper\Attribute\OneToOne;

class FakeRelatedBad extends Related
{
    #[OneToOne]
    protected NotLoaded|null|Record $id;
}
