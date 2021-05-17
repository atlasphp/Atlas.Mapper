<?php
namespace Atlas\Mapper\DataSource\Bidibar;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOneBidi;
use Atlas\Mapper\DataSource\Bidifoo\BidifooRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class BidibarRelated extends Related
{
    #[OneToOneBidi(on: ['bidifoo_id' => 'bidifoo_id'])]
    protected ?BidifooRecord $bidifoo;
}
