<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Fake\FakeMapperRelationships;
use Atlas\Testing\DataSource\Employee\Employee;
use Atlas\Testing\DataSourceFixture;

class MapperRelationshipsTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $connection = (new DataSourceFixture())->exec();
        $mapperLocator = MapperLocator::new($connection);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("Relationship 'id' conflicts with existing column name.");

        new FakeMapperRelationships($mapperLocator, Employee::CLASS);
    }
}