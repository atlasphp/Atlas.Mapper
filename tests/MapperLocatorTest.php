<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Related;
use Atlas\Table\Primary;
use Atlas\Table\Row;
use Atlas\Testing\DataSource\Employee\EmployeeMapper;
use Atlas\Testing\DataSource\Employee\EmployeeRecord;
use Atlas\Testing\DataSource\Employee\EmployeeRecordSet;
use Atlas\Testing\DataSource\Employee\EmployeeTable;
use Atlas\Testing\DataSource\SqliteFixture;

class MapperLocatorTest extends \PHPUnit\Framework\TestCase
{
    protected $mapperLocator;

    protected function setUp()
    {
        $connection = (new SqliteFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
    }

    public function testHas()
    {
        $this->assertFalse($this->mapperLocator->has('NoSuchMapper'));
        $this->assertTrue($this->mapperLocator->has(EmployeeMapper::CLASS));
    }

    public function testGet()
    {
        $expect = EmployeeMapper::CLASS;
        $this->assertInstanceOf($expect, $this->mapperLocator->get(EmployeeMapper::CLASS));

        $this->expectException(Exception::CLASS, "NoSuchMapper not found in mapper locator");
        $this->mapperLocator->get('NoSuchMapper');
    }
}
