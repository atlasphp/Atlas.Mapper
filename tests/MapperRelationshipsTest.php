<?php
namespace Atlas\Mapper;

use Atlas\Testing\DataSource\Employee\EmployeeMapper;
use Atlas\Testing\DataSource\SqliteFixture;
use Atlas\Mapper\Fake\FakeMapperRelationships;

class MapperRelationshipsTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $connection = (new SqliteFixture())->exec();
        $mapperLocator = MapperLocator::new($connection);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("Relationship 'id' conflicts with existing column name.");

        new FakeMapperRelationships($mapperLocator, EmployeeMapper::CLASS);
    }
}