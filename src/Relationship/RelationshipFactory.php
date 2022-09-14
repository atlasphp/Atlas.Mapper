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

use Atlas\Mapper\Define\RefinementAttribute;
use Atlas\Mapper\Define\RelationshipAttribute;
use Atlas\Mapper\Exception;
use Atlas\Mapper\MapperLocator;
use Attribute;
use ReflectionProperty;

class RelationshipFactory
{
    public function __construct(
        protected MapperLocator $mapperLocator,
        protected RelationshipLocator $relationshipLocator,
        protected string $nativeMapperClass,
        protected string $nativeTableClass
    ) {
    }

    public function newFromProperty(ReflectionProperty $property) : Relationship
    {
        $attributes = $property->getAttributes();

        while ($attribute = array_shift($attributes)) {
            if (is_subclass_of(
                $attribute->getName(),
                RelationshipAttribute::CLASS
            )) {
                /** @var RelationshipAttribute */
                $attribute = $attribute->newInstance();
                return $this->newFromPropertyAttributes(
                    $property,
                    $attribute,
                    $attributes
                );
            }
        }
    }

    /**
     * @param Attribute[] $otherAttrs
     */
    protected function newFromPropertyAttributes(
        ReflectionProperty $property,
        RelationshipAttribute $attribute,
        array $otherAttrs
    ) : Relationship
    {
        $relationshipClass = $attribute->class;

        $name = $property->getName();

        if (in_array($name, $this->nativeTableClass::COLUMN_NAMES)) {
            throw new Exception\RelatedNameConflict(
                $this->nativeMapperClass,
                $name,
            );
        }

        $type = $property->getType();
        $foreignMapperClass = ResolveRelated::mapperClass(
            $this->nativeMapperClass,
            $name,
            $type
        );

        /** @var Relationship */
        $relationship = new $relationshipClass(
            $name,
            $this->mapperLocator,
            $this->nativeMapperClass,
            $foreignMapperClass,
            $attribute,
            $this->relationshipLocator,
        );

        foreach ($otherAttrs as $otherAttr) {
            if (is_subclass_of(
                $otherAttr->getName(),
                RefinementAttribute::CLASS
            )) {
                $otherAttr = $otherAttr->newInstance();
                $otherAttr($relationship);
            }
        }

        return $relationship;
    }
}