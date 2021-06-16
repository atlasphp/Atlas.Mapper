<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Mapper\Related;

use Atlas\Mapper\Exception;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Relationship\MapperRelationships;
use Atlas\Mapper\Relationship;
use Attribute;
use ReflectionProperty;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToMany extends RelationshipBuilder
{
    public function __construct(
        protected string $through,
        protected ?string $throughNative = null, // the native field on the through related
        protected ?string $throughForeign = null, // the foreign field on the through related
    ) {
    }

    public function __invoke(
        string $name,
        MapperLocator $mapperLocator,
        string $nativeMapperClass,
        ReflectionProperty $prop,
        array $relationships
    ) : mixed
    {
        if (! isset($relationships[$this->through])) {
            throw Exception::relationshipDoesNotExist($this->through);
        }

        $foreignMapperClass = $this->getForeignMapperClass($prop);
        $throughRelationship = $relationships[$this->through];
        $throughForeignRelationships = $throughRelationship
            ->getForeignMapper()
            ->getRelationships();

        if ($this->throughNative === null) {
            $this->throughNative = $this->getThrough(
                $throughForeignRelationships,
                $nativeMapperClass
            ) ?? throw Exception::couldNotFindThroughRelationship(
                'native',
                $this->through,
                $name,
                $nativeMapperClass
            );
        }

        if ($this->throughForeign === null) {
            $this->throughForeign = $this->getThrough(
                $throughForeignRelationships,
                $foreignMapperClass
            ) ?? throw Exception::couldNotFindThroughRelationship(
                'foreign',
                $throughRelationship->name,
                $name,
                $nativeMapperClass
            );
        }

        return new Relationship\ManyToMany(
            $name,
            $mapperLocator,
            $nativeMapperClass,
            $foreignMapperClass,
            $throughRelationship,
            $this->throughNative,
            $this->throughForeign
        );
    }

    protected function getThrough(MapperRelationships $throughForeignRelationships, string $mapperClass) : ?string
    {
        foreach ($throughForeignRelationships as $relatedName => $relationship) {
            if (! $relationship instanceof Relationship\ManyToOne) {
                continue;
            }

            $relatedForeignMapperClass = $relationship->getForeignMapperClass();

            if ($relatedForeignMapperClass === $mapperClass) {
                return $relatedName;
            }
        }

        return null;
    }
}
