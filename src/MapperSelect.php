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

use Atlas\Table\TableSelect;
use Atlas\Mapper\Relationship\ResolveRelated;

abstract class MapperSelect extends TableSelect
{
    public static function new(mixed $arg, mixed ...$args) : static
    {
        /** @var Mapper */
        $mapper = array_pop($args);
        $select = parent::new($arg, ...$args);
        $select->mapper = $mapper;
        return $select;
    }

    protected Mapper $mapper;

    protected array $loadRelated = [];

    public function joinRelated(string|array $relatedSpecs) : self
    {
        $relatedSpecs = (array) $relatedSpecs;

        $relationshipLocator = $this->mapper->getRelationshipLocator();

        foreach ($relatedSpecs as $relatedSpec => $relatedMore) {
            if (is_int($relatedSpec)) {
                $relatedSpec = $relatedMore;
                $relatedMore = [];
            }

            list($relatedName, $join, $foreignAlias) = ResolveRelated::listJoinSpec($relatedSpec);

            $relationshipLocator->get($relatedName)->joinSelect(
                $this,
                $join,
                $this->table::NAME,
                $foreignAlias,
                $relatedMore
            );
        }

        return $this;
    }

    public function loadRelated(array $loadRelated) : self
    {
        // make sure that all loadRelated() are on relateds that actually exist
        $fields = $this->mapper->getRelationshipLocator()->getNames();
        foreach ($loadRelated as $key => $val) {
            $relatedName = $key;
            if (is_int($key)) {
                $relatedName = $val;
            }
            if (! in_array($relatedName, $fields)) {
                throw new Exception\CannotLoadRelated(
                    $relatedName,
                    get_class($this),
                    get_class($this->mapper),
                );
            }
        }
        $this->loadRelated = $loadRelated;
        return $this;
    }

    public function fetchRecord() : ?Record
    {
        $row = $this->fetchRow();
        if (! $row) {
            return null;
        }

        return $this->mapper->turnRowIntoRecord($row, $this->loadRelated);
    }

    public function fetchRecords() : array
    {
        $rows = $this->fetchRows();
        return $this->mapper->turnRowsIntoRecords($rows, $this->loadRelated);
    }

    public function fetchRecordSet() : RecordSet
    {
        $records = $this->fetchRecords();
        return $this->mapper->newRecordSet($records);
    }
}
