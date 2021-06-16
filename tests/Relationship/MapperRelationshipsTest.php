<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\DataSource\Employee\Employee;
use Atlas\Mapper\DataSourceFixture;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRelatedBad;
use Atlas\Mapper\MapperLocator;

class MapperRelationshipsTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $connection = (new DataSourceFixture())->exec();
        $mapperLocator = MapperLocator::new($connection);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("Relationship 'id' conflicts with existing column name.");

        new MapperRelationships($mapperLocator, Employee::CLASS, FakeRelatedBad::CLASS);
    }
}
