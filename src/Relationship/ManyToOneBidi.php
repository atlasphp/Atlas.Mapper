<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Record;
use SplObjectStorage;

class ManyToOneBidi extends ManyToOne
{
    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        parent::persistForeign($nativeRecord, $tracker);
        $this->fixNativeRecord($nativeRecord);
        $this->mapperLocator->get($this->nativeMapperClass)->update($nativeRecord);
    }
}
