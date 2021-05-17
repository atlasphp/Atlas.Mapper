<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Record;
use Atlas\Mapper\Related;

class FakeRelatedBad extends Related
{
    #[Related\OneToOne]
    protected ?Record $id;
}
