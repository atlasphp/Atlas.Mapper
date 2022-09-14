<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRelatedNameConflict;
use Atlas\Mapper\MapperLocator;
use Atlas\Testing\DataSource\Employee\Employee;
use Atlas\Testing\DataSource\Employee\EmployeeTable;
use Atlas\Testing\DataSourceFixture;

class RelationshipLocatorTest extends \PHPUnit\Framework\TestCase
{
    public function testRelatedNameConflict()
    {
        $connection = (new DataSourceFixture())->exec();
        $mapperLocator = MapperLocator::new($connection);

        $this->expectException(Exception\RelatedNameConflict::CLASS);
        $this->expectExceptionMessage("Atlas\Testing\DataSource\Employee\EmployeeRelated::\$id property conflicts with existing Atlas\Testing\DataSource\Employee\EmployeeTable column also named 'id'.");

        new RelationshipLocator(
            $mapperLocator,
            Employee::CLASS,
            EmployeeTable::CLASS,
            FakeRelatedNameConflict::CLASS
        );
    }
}
