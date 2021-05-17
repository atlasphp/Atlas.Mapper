<?php
namespace Atlas\Mapper\Fake;

use Atlas\Mapper\Record;
use Atlas\Mapper\Related;

class FakeRelated extends Related
{
    protected ?Record $zim;
    protected ?Record $irk;
}
