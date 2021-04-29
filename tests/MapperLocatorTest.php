<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Exception;
use Atlas\Table\TableLocator;
use Atlas\Mapper\DataSource\Author\Author;
use Atlas\Mapper\DataSource\Author\AuthorTable;
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
        $this->assertTrue($this->mapperLocator->has(Author::CLASS));
    }

    public function testGet()
    {
        $expect = Author::CLASS;
        $this->assertInstanceOf($expect, $this->mapperLocator->get(Author::CLASS));

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("NoSuchMapper not found in mapper locator");
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
