<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Related;
use Atlas\Table\Primary;
use Atlas\Table\Row;
use Atlas\Table\TableLocator;
use Atlas\Mapper\DataSource\Employee\Employee;
use Atlas\Mapper\DataSource\Employee\EmployeeRecord;
use Atlas\Mapper\DataSource\Employee\EmployeeRecordSet;
use Atlas\Mapper\DataSource\Employee\EmployeeTable;
use Atlas\Mapper\DataSourceFixture;

class MapperLocatorTest extends \PHPUnit\Framework\TestCase
{
    protected $mapperLocator;

    protected function setUp() : void
    {
        $connection = (new DataSourceFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
    }

    public function testHas()
    {
        $this->assertFalse($this->mapperLocator->has('NoSuchMapper'));
        $this->assertTrue($this->mapperLocator->has(Employee::CLASS));
    }

    public function testGet()
    {
        $expect = Employee::CLASS;
        $this->assertInstanceOf($expect, $this->mapperLocator->get(Employee::CLASS));

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("Class 'NoSuchMapper' does not exist.");
        $this->mapperLocator->get('NoSuchMapper');
    }

    public function testGetTableLocator()
    {
        $this->assertInstanceOf(
            TableLocator::CLASS,
            $this->mapperLocator->getTableLocator()
        );
    }
}
