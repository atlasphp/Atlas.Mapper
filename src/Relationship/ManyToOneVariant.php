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

        if ($foreignMapperClass !== 'mixed') {
            throw new Exception\UnexpectedVariantTypehint(
                $nativeMapperClass,
                $name,
                $foreignMapperClass
            );
        }
    }

    public function getPersistencePriority() : string
    {
        return static::BEFORE_NATIVE;
    }

    public function type(Define\Variant $attr) : self
    {
        $foreignMapperClass = ResolveRelated::mapperClass(
            $this->nativeMapperClass,
            $this->name,
            $attr->class
        );

        $variant = new ManyToOne(
            $this->name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
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
        throw new Exception\CannotJoinRelatedVariant(
            $this->nativeMapperClass,
            $this->name
        );
    }

    protected function getVariant(int|string|null $typeVal) : ManyToOne
    {
        if (isset($this->variants[$typeVal])) {
            return $this->variants[$typeVal];
        }

        throw new Exception\VariantDoesNotExist(
            $this->nativeMapperClass,
            $this->name,
            $typeVal,
        );
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
