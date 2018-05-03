<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\MapperLocator;
use Atlas\Testing\DataSourceFixture;

abstract class RelationshipTest extends \PHPUnit\Framework\TestCase
{
    protected $mapperLocator;

    protected function setUp()
    {
        $connection = (new DataSourceFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
    }
}
