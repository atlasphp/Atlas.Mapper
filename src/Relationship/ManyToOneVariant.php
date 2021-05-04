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
    protected array $variants = [];

    public function __construct(
        protected string $name,
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass,
        protected string $typeCol
    ) {
    }

    public function getPersistOrder() : string
    {
        return 'beforeNative';
    }

    public function type(
        string $typeVal,
        string $foreignMapperClass,
        array $on
    ) : void
    {
        $variant = new ManyToOne(
            $this->name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
            $on
        );

        $variant->where = $this->where;
        $variant->ignoreCase = $this->ignoreCase;

        $this->variants[$typeVal] = $variant;
    }

    public function where(string $condition, mixed ...$bindInline) : void
    {
        if (empty($this->variants)) {
            parent::where($condition, ...$bindInline);
            return;
        }

        $variant = end($this->variants);
        $variant->where($condition, ...$bindInline);
    }

    public function ignoreCase(bool $ignoreCase = true) : void
    {
        if (empty($this->variants)) {
            parent::ignoreCase($ignoreCase);
            return;
        }

        $variant = end($this->variants);
        $variant->ignoreCase($ignoreCase);
    }

    public function joinSelect(
        MapperSelect $select,
        string $join,
        string $nativeAlias,
        string $foreignAlias,
        callable $sub = null
    ) : void
    {
        throw Exception::cannotJoinOnVariantRelationships();
    }

    public function getOn() : array
    {
        throw new Exception('Cannot get "on" for variant relationships');
    }

    protected function getVariant(mixed $typeVal) : ManyToOne
    {
        if (isset($this->variants[$typeVal])) {
            return $this->variants[$typeVal];
        }

        throw Exception::noSuchType($this->nativeMapperClass, $typeVal);
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
            $nativeSubsets[$nativeRecord->{$this->typeCol}][] = $nativeRecord;
        }

        foreach ($nativeSubsets as $typeVal => $nativeSubset) {
            $variant = $this->getVariant($typeVal);
            $variant->stitchIntoRecords($nativeSubset, $custom);
        }
    }

    public function fixNativeRecord(Record $nativeRecord) : void
    {
        $this->fixNativeTypeVal($nativeRecord);
        $variant = $this->getVariant($nativeRecord->{$this->typeCol});
        $variant->fixNativeRecord($nativeRecord);
    }

    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        $this->fixNativeTypeVal($nativeRecord);
        $variant = $this->getVariant($nativeRecord->{$this->typeCol});
        $variant->persistForeign($nativeRecord, $tracker);
    }

    protected function fixNativeTypeVal(Record $nativeRecord) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        $foreignRecordMapperClass = $foreignRecord->getMapperClass();
        foreach ($this->variants as $typeVal => $variant) {
            if ($foreignRecordMapperClass == $variant->getForeignMapperClass()) {
                $nativeRecord->{$this->typeCol} = $typeVal;
                return;
            }
        }
    }
}
