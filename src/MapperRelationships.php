<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper;

use Atlas\Mapper\Exception;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Record;
use Atlas\Mapper\Relationship\Relationship;
use Atlas\Mapper\Relationship\RelationshipLocator;
use SplObjectStorage;

class MapperRelationships
{
    protected $relationships = [];

    protected $fields = [];

    protected $prototypeRelated = null;

    public function __construct(
        protected RelationshipLocator $relationshipLocator
    ) {
    }

    public function getRelationshipLocator() : RelationshipLocator
    {
        return $this->relationshipLocator;
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        array $loadRelated = []
    ) : void
    {
        foreach ($this->fixLoadRelated($loadRelated) as $relatedName => $custom) {
            if (! $this->relationshipLocator->has($relatedName)) {
                throw Exception::relationshipDoesNotExist($relatedName);
            }
            $this->relationshipLocator->get($relatedName)->stitchIntoRecords(
                $nativeRecords,
                $custom
            );
        }
    }

    protected function fixLoadRelated(array $spec) : array
    {
        $loadRelated = [];
        foreach ($spec as $key => $val) {
            if (is_int($key)) {
                $loadRelated[$val] = null;
            } elseif (is_array($val) && ! is_callable($val)) {
                $loadRelated[$key] = function ($select) use ($val) {
                    $select->loadRelated($val);
                };
            } else {
                $loadRelated[$key] = $val;
            }
        }
        return $loadRelated;
    }

    public function fixNativeRecord(Record $nativeRecord) : void
    {
        foreach ($this->relationshipLocator as $relationship) {
            $relationship->fixNativeRecord($nativeRecord);
        }
    }

    public function fixForeignRecord(Record $nativeRecord) : void
    {
        foreach ($this->relationshipLocator as $relationship) {
            $relationship->fixForeignRecord($nativeRecord);
        }
    }

    public function persistBeforeNative(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        foreach ($this->relationshipLocator->getPersistBeforeNative() as $relationship) {
            $relationship->persistForeign($nativeRecord, $tracker);
        }
    }

    public function persistAfterNative(
        Record $nativeRecord,
        SplObjectStorage $tracker
    ) : void
    {
        foreach ($this->relationshipLocator->getPersistAfterNative() as $relationship) {
            $relationship->persistForeign($nativeRecord, $tracker);
        }
    }

    public function joinSelect(
        MapperSelect $select,
        string $nativeAlias,
        string $relatedName,
        callable $sub = null
    ) : void
    {
        // clean up the specification
        $relatedName = trim($relatedName);

        // extract the foreign alias
        $foreignAlias = '';
        $pos = stripos($relatedName, ' AS ');
        if ($pos !== false) {
            $foreignAlias = trim(substr($relatedName, $pos + 4));
            $relatedName = trim(substr($relatedName, 0, $pos));
        }

        // extract the join type
        $join = 'JOIN';
        $pos = strpos($relatedName, ' ');
        if ($pos !== false) {
            $join = trim(substr($relatedName, 0, $pos));
            $relatedName = trim(substr($relatedName, $pos));
        }

        // fix the foreign alias
        if ($foreignAlias == '') {
            $foreignAlias = $relatedName;
        }

        // make the join
        $this->relationshipLocator->get($relatedName)->joinSelect(
            $select,
            $join,
            $nativeAlias,
            $foreignAlias,
            $sub
        );
    }
}
