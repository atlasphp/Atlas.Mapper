<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Assertions;
use Atlas\Mapper\DataSource\Author\Author;
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
        $this->mapper = $this->mapperLocator->get(Author::CLASS);
        $this->select = $this->mapper->select();
    }

    public function testGetStatement()
    {
        $this->select->columns('*');
        $expect = '
            SELECT
                *
            FROM
                "authors"
        ';
        $actual = $this->select->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testFetchRecord_missing()
    {
        $actual = $this->select->where('author_id = 88')->fetchRecord();
        $this->assertNull($actual);
    }

    public function testFetchRecord_getStatement()
    {
        $expect = '
            SELECT
                author_id,
                name
            FROM
                "authors"
        ';

        $this->select
            ->columns('author_id', 'name')
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
        $this->select->loadRelated(['no_such_related']);
    }

    public function testJoinWithSubRelated()
    {
        $select = $this->mapperLocator
            ->get(Author::CLASS)
            ->select()
            ->columns('*')
            ->joinRelated('LEFT threads', function ($sub) {
                $sub->joinRelated('INNER taggings AS taggings_alias', function ($sub) {
                    $sub->joinRelated('tag');
                });
            });

        $expect = '
            SELECT
                *
            FROM
                "authors"
                    LEFT JOIN "threads" ON "authors"."author_id" = "threads"."author_id"
                    INNER JOIN "taggings" AS "taggings_alias" ON "threads"."thread_id" = "taggings_alias"."thread_id"
                    JOIN "tags" AS "tag" ON "taggings_alias"."tag_id" = "tag"."tag_id"
        ';

        $this->assertSameSql($expect, $select->getStatement());
    }
}
