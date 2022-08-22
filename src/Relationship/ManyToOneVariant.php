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

use Atlas\Mapper\Define;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use ReflectionProperty;
use SplObjectStorage;

class ManyToOneVariant extends Relationship
{
    protected string $typeCol;

    /** @var ManyToOne[] */
    protected array $variants = [];

    protected RelationshipLocator $relationshipLocator;

    public function __construct(
        protected string $name,
        protected MapperLocator $mapperLocator,
        protected string $nativeMapperClass,
        string $foreignMapperClass,
        Define\ManyToOneVariant $attribute,
        RelationshipLocator $relationshipLocator
    ) {
        $this->name = $name;
        $this->mapperLocator = $mapperLocator;
        $this->nativeMapperClass = $nativeMapperClass;
        $this->typeCol = $attribute->column;
        $this->relationshipLocator = $relationshipLocator;

        /*
         * @phpstan-ignore-next-line because $foreignMapperClass is unused,
         * but needed for constructor consistency
         */
        $foreignMapperClass;
    }

    public function getPersistencePriority() : string
    {
        return static::BEFORE_NATIVE;
    }

    public function type(Define\Variant $attr) : self
    {
        $variant = new ManyToOne(
            $this->name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            Mapper::classFrom($attr->class),
            new Define\ManyToOne(on: $attr->on),
        );

        $variant->where = $this->where;
        $variant->ignoreCase = $this->ignoreCase;

        $this->variants[$attr->value] = $variant;
        return $this;
    }

    public function where(
        string $condition,
        mixed ...$bindInline
    ) : Relationship
    {
        if (empty($this->variants)) {
            return parent::where($condition, ...$bindInline);
        }

        $variant = end($this->variants);
        $variant->where($condition, ...$bindInline);
        return $this;
    }

    public function ignoreCase(bool $ignoreCase = true) : Relationship
    {
        if (empty($this->variants)) {
            return parent::ignoreCase($ignoreCase);
        }

        $variant = end($this->variants);
        $variant->ignoreCase($ignoreCase);
        return $this;
    }

    public function joinSelect(
        MapperSelect $select,
        string $join,
        string $nativeAlias,
        string $foreignAlias,
        array $more = []
    ) : void
    {
        throw Exception::cannotJoinOnVariantRelationships();
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
            if ($foreignRecordMapperClass == $variant->foreignMapperClass) {
                $nativeRecord->{$this->typeCol} = $typeVal;
                return;
            }
        }
    }
}
