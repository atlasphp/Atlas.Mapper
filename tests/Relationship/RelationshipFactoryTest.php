<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Fake\FakeAttribute;
use Atlas\Mapper\MapperLocator;
use Atlas\Testing\DataSource\Employee\Employee;
use Atlas\Testing\DataSource\Employee\EmployeeRelated;
use Atlas\Testing\DataSource\Employee\EmployeeTable;
use ReflectionClass;

class RelationshipFactoryTest extends \PHPUnit\Framework\TestCase
{
    #[FakeAttribute]
    protected $fakeProperty;

    public function testNewFromProperty_noRelationshipAttribute()
    {
        $mapperLocator = MapperLocator::new('sqlite::memory:');

        $relationshipFactory = new RelationshipFactory(
            $mapperLocator,
            new RelationshipLocator(
                $mapperLocator,
                Employee::CLASS,
                EmployeeTable::CLASS,
                EmployeeRelated::CLASS
            ),
            Employee::CLASS,
            EmployeeTable::CLASS,
        );

        $property = (new ReflectionClass($this))
            ->getProperty('fakeProperty');

        $this->assertNull($relationshipFactory->newFromProperty($property));
    }
}
