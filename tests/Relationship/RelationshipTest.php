<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\MapperLocator;
use Atlas\Testing\DataSource\SqliteFixture;

abstract class RelationshipTest extends \PHPUnit\Framework\TestCase
{
    protected $mapperLocator;

    protected function setUp()
    {
        $connection = (new SqliteFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
    }
}
