<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Container;
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

        $container = new Container($connection);
        $container->setMappers([
            EmployeeMapper::CLASS,
        ]);

        $this->select = $container
            ->newMapperLocator()
            ->get(EmployeeMapper::CLASS)
            ->select();
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

    public function testFetchRecordGetStatement()
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
        $this->expectException(
            'Atlas\Mapper\Exception',
            "Relationship 'no_such_related' does not exist."
        );
        $this->select->with(['no_such_related']);
    }
}
