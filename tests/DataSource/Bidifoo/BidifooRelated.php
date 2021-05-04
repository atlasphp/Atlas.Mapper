<?php
namespace Atlas\Mapper\DataSource\Bidifoo;

use Atlas\Mapper\Attribute\ManyToMany;
use Atlas\Mapper\Attribute\ManyToOne;
use Atlas\Mapper\Attribute\OneToMany;
use Atlas\Mapper\Attribute\OneToOneBidi;
use Atlas\Mapper\DataSource\Bidibar\BidibarRecord;
use Atlas\Mapper\Related;
use Atlas\Mapper\NotLoaded;

class BidifooRelated extends Related
{
    #[OneToOneBidi(on: ['bidibar_id' => 'bidibar_id'])]
    protected ?BidibarRecord $bidibar;
}
