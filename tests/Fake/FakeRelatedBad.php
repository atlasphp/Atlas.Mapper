<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;
use Atlas\Mapper\Related\OneToOne;

class FakeRelatedBad extends Related
{
    #[OneToOne]
    protected ?Record $id;
}
