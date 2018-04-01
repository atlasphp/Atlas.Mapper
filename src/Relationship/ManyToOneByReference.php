<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Exception;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Record;
use SplObjectStorage;

class ManyToOneByReference extends Relationship
{
    protected $referenceCol;

    protected $relationships = [];

    public function __construct(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        string $referenceCol
    ) {
        $this->name = $name;
        $this->mapperLocator = $mapperLocator;
        $this->nativeMapperClass = $nativeMapperClass;
        $this->referenceCol = $referenceCol;
    }

    public function where(string $cond, ...$bind) : Relationship
    {
        throw Exception::invalidReferenceMethod(__FUNCTION__);
    }

    public function ignoreCase(bool $ignoreCase = true) : Relationship
    {
        throw Exception::invalidReferenceMethod(__FUNCTION__);
    }

    protected function stitchIntoRecord(
        Record $nativeRecord,
        array $foreignRecords
    ) : void {
        throw Exception::invalidReferenceMethod(__FUNCTION__);
    }

    public function to(
        string $referenceVal,
        string $foreignMapperClass,
        array $on
    ) : self {
        $this->relationships[$referenceVal] = new ManyToOne(
            $this->name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
            $on
        );
        return $this;
    }

    protected function getReference($referenceVal)
    {
        if (isset($this->relationships[$referenceVal])) {
            return $this->relationships[$referenceVal];
        }

        throw Exception::noSuchReference($this->nativeMapperClass, $referenceVal);
    }

    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) : void {
        if (! $nativeRecords) {
            return;
        }

        $nativeSubsets = [];
        foreach ($nativeRecords as $nativeRecord) {
            $nativeSubsets[$nativeRecord->{$this->referenceCol}][] = $nativeRecord;
        }

        foreach ($nativeSubsets as $referenceVal => $nativeSubset) {
            $reference = $this->getReference($referenceVal);
            $reference->stitchIntoRecords($nativeSubset, $custom);
        }
    }

    public function fixNativeRecordKeys(Record $nativeRecord) : void
    {
        $this->fixNativeReferenceVal($nativeRecord);
        $relationship = $this->getReference($nativeRecord->{$this->referenceCol});
        $relationship->fixNativeRecordKeys($nativeRecord);
    }

    public function persistForeign(Record $nativeRecord, SplObjectStorage $tracker) : void
    {
        $this->fixNativeReferenceVal($nativeRecord);
        $relationship = $this->getReference($nativeRecord->{$this->referenceCol});
        $relationship->persistForeign($nativeRecord, $tracker);
    }

    protected function fixNativeReferenceVal(Record $nativeRecord) : void
    {
        $foreignRecord = $nativeRecord->{$this->name};
        if (! $foreignRecord instanceof Record) {
            return;
        }

        $foreignRecordMapperClass = $foreignRecord->getMapperClass();
        foreach ($this->relationships as $referenceVal => $relationship) {
            if ($foreignRecordMapperClass == $relationship->foreignMapperClass) {
                $nativeRecord->{$this->referenceCol} = $referenceVal;
                return;
            }
        }
    }
}
