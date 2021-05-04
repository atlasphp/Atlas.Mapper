<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Record;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class FakeRelated extends Related
{
    protected ?Record $zim;
    protected ?Record $irk;
}
