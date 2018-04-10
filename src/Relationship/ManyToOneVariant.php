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

use Atlas\Mapper\Exception;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use SplObjectStorage;

class ManyToOneVariant extends Relationship
{
    protected $name;

    protected $mapperLocator;

    protected $nativeMapperClass;

    protected $discriminatorCol;

    protected $relationships = [];

    public function __construct(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        string $discriminatorCol
    ) {
        $this->name = $name;
        $this->mapperLocator = $mapperLocator;
        $this->nativeMapperClass = $nativeMapperClass;
        $this->discriminatorCol = $discriminatorCol;
    }

    public function type(
        string $discriminatorVal,
        string $foreignMapperClass,
        array $on
    ) : self
    {
        $relationship = new ManyToOne(
            $this->name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
            $on
        );

        $relationship->where = $this->where;
        $relationship->ignoreCase = $this->ignoreCase;

        $this->relationships[$discriminatorVal] = $relationship;
        return $this;
    }

    public function where(string $cond, ...$bind) : Relationship
    {
        if (empty($this->relationships)) {
            return parent::where($cond, ...$bind);
        }

        $relationship = end($this->relationships);
        $relationship->where($cond, ...$bind);
        return $this;
    }

    public function ignoreCase(bool $ignoreCase = true) : Relationship
    {
        if (empty($this->relationships)) {
            return parent::ignoreCase($ignoreCase);
        }

        $relationship = end($this->relationships);
        $relationship->ignoreCase($ignoreCase);
        return $this;
    }

    public function joinSelect(string $join, MapperSelect $select) : void
    {
        throw Exception::cannotJoinOnVariantRelationships();
    }

    protected function getRelationship($discriminatorVal)
    {
        if (isset($this->relationships[$discriminatorVal])) {
            return $this->relationships[$discriminatorVal];
        }

        throw Exception::noSuchType($this->nativeMapperClass, $discriminatorVal);
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void
    {
        if (! $nativeRecords) {
            return;
        }

        $nativeSubsets = [];
        foreach ($nativeRecords as $nativeRecord) {
            $nativeSubsets[$nativeRecord->{$this->discriminatorCol}][] = $nativeRecord;
        }

        foreach ($nativeSubsets as $discriminatorVal => $nativeSubset) {
            $relationship = $this->getRelationship($discriminatorVal);
            $relationship->stitchIntoRecords($nativeSubset, $custom);
        }
    }

    public function fixNativeRecordKeys(Record $nativeRecord) : void
    {
        $this->fixNativeDiscriminatorVal($nativeRecord);
        $relationship = $this->getRelationship($nativeRecord->{$this->discriminatorCol});
        $relationship->fixNativeRecordKeys($nativeRecord);
    }

    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        $this->fixNativeDiscriminatorVal($nativeRecord);
        $relationship = $this->getRelationship($nativeRecord->{$this->discriminatorCol});
        $relationship->persistForeign($nativeRecord, $tracker);
    }

    protected function fixNativeDiscriminatorVal(Record $nativeRecord) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        $foreignRecordMapperClass = $foreignRecord->getMapperClass();
        foreach ($this->relationships as $discriminatorVal => $relationship) {
            if ($foreignRecordMapperClass == $relationship->foreignMapperClass) {
                $nativeRecord->{$this->discriminatorCol} = $discriminatorVal;
                return;
            }
        }
    }
}
