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

class SubJoinRelated
{
    protected $relationshipLocator;

    protected $select;

    protected $nativeAlias;

    public function __construct(
        RelationshipLocator $relationshipLocator,
        MapperSelect $select,
        string $nativeAlias
    ) {
        $this->relationshipLocator = $relationshipLocator;
        $this->select = $select;
        $this->nativeAlias = $nativeAlias;
    }

    public function joinRelated($relatedSpec, callable $sub = null) : void
    {
        list($relatedName, $join, $foreignAlias) = $this->relationshipLocator->listRelatedSpec($relatedSpec);

        $this->relationshipLocator->get($relatedName)->appendJoin(
            $this->select,
            $join,
            $this->nativeAlias,
            $foreignAlias,
            $sub
        );
    }
}
