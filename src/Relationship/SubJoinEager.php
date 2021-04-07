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

use Atlas\Mapper\MapperRelationships;
use Atlas\Mapper\MapperSelect;

class SubJoinEager
{
    public function __construct(
        protected MapperRelationships $relationships,
        protected MapperSelect $select,
        protected string $nativeAlias
    ) {
        $this->relationships = $relationships;
        $this->select = $select;
        $this->nativeAlias = $nativeAlias;
    }

    public function joinEager(string $relatedName, callable $sub = null) : void
    {
        $this->relationships->joinSelect(
            $this->select,
            $this->nativeAlias,
            $relatedName,
            $sub
        );
    }
}