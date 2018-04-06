<?php
namespace Atlas\Mapper;

use Atlas\Pdo\Connection;
use Atlas\Pdo\ConnectionLocator;
use Atlas\Query\QueryFactory;
use Atlas\Table\IdentityMap;
use Atlas\Table\TableEvents;
use Atlas\Table\TableSelect;
use Atlas\Testing\Assertions;
use Atlas\Testing\DataSource\Employee\EmployeeMapper;
use Atlas\Testing\DataSource\SqliteFixture;
use Iterator;
use PDO;

class MapperSelectTest extends \PHPUnit\Framework\TestCase
{
    use Assertions;

    protected $select;

    protected function setUp()
    {
        $connection = (new SqliteFixture())->exec();
        $this->mapper = MapperLocator::new($connection)->get(EmployeeMapper::CLASS);
        $this->select = $this->mapper->select();
    }

    public function testGetStatement()
    {
        $this->select->columns('*');
        $expect = '
            SELECT
                *
            FROM
                employee
        ';
        $actual = $this->select->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testFetchRecord_missing()
    {
        $actual = $this->select->where('id = 88')->fetchRecord();
        $this->assertNull($actual);
    }

    public function testFetchRecord_getStatement()
    {
        $expect = '
            SELECT
                id,
                name
            FROM
                employee
        ';

        $this->select
            ->columns('id', 'name')
            ->fetchRecord();

        $actual = $this->select->getStatement();

        $this->assertSameSql($expect, $actual);
    }

    public function testWith_noSuchRelationship()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Relationship 'no_such_related' does not exist."
        );
        $this->select->with(['no_such_related']);
    }

    public function testMapperAlreadySet()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Mapper already set.');
        $this->select->setMapper($this->mapper);
    }
}
