<?php
namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Related\ManyToMany;
use Atlas\Mapper\Related\ManyToOne;
use Atlas\Mapper\Related\OneToMany;
use Atlas\Mapper\Related\OneToOneBidi;
use Atlas\Mapper\DataSource\Bidibar\BidibarRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class BidifooRelated extends Related
{
    #[OneToOneBidi(on: ['bidibar_id' => 'bidibar_id'])]
    protected ?BidibarRecord $bidibar;
}
