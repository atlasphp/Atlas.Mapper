<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Assertions;
use Atlas\Mapper\DataSource\Author\Author;
use Atlas\Mapper\DataSource\Employee\Employee;
use Atlas\Mapper\DataSourceFixture;
use Iterator;
use PDO;

class MapperSelectTest extends \PHPUnit\Framework\TestCase
{
    use Assertions;

    protected $select;

    protected function setUp() : void
    {
        $connection = (new DataSourceFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
        $this->mapper = $this->mapperLocator->get(Employee::CLASS);
        $this->select = $this->mapper->select();
    }

    public function testGetStatement()
    {
        $this->select->columns('*');
        $expect = '
            SELECT
                *
            FROM
                "employee"
        ';
        $actual = $this->select->getQueryString();
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
                "employee"
        ';

        $this->select
            ->columns('id', 'name')
            ->fetchRecord();

        $actual = $this->select->getQueryString();

        $this->assertSameSql($expect, $actual);
    }

    public function testLoadRelated_noSuchRelationship()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Cannot load 'no_such_related' for "
            . "Atlas\Mapper\DataSource\Employee\EmployeeSelect because there "
            . "is no Atlas\Mapper\DataSource\Employee\EmployeeRelated property "
            . "defined for it."
        );
        $this->select->loadRelated(['no_such_related']);
    }

    public function testJoinRelated_withMoreRelated()
    {
        $select = $this->mapperLocator
            ->get(Author::CLASS)
            ->select()
            ->columns('*')
            ->joinRelated([
                'LEFT threads' => [
                    'INNER taggings AS taggings_alias' => [
                        'tag',
                    ],
                ],
            ]);

        $expect = '
            SELECT
                *
            FROM
                "authors"
                    LEFT JOIN "threads" ON "authors"."author_id" = "threads"."author_id"
                    INNER JOIN "taggings" AS "taggings_alias" ON "threads"."thread_id" = "taggings_alias"."thread_id"
                    JOIN "tags" AS "tag" ON "taggings_alias"."tag_id" = "tag"."tag_id"
        ';

        $this->assertSameSql($expect, $select->getQueryString());
    }
}
