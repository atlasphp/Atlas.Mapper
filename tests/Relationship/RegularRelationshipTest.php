<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRegularRelationship;
use Atlas\Testing\DataSource\Author\Author;
use Atlas\Testing\DataSource\Thread\Thread;

class RegularRelationshipTest extends RelationshipTest
{
    public function testClassDoesNotExist()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("Class 'NoSuchClass' does not exist.");
        $fake = new FakeRegularRelationship(
            'fake',
            $this->mapperLocator,
            Thread::CLASS,
            'NoSuchClass'
        );
    }

    public function testValuesDontMatch()
    {
        $fake = new FakeRegularRelationship(
            'fake',
            $this->mapperLocator,
            Thread::CLASS,
            Author::CLASS
        );
        $this->assertFalse($fake->valuesMatch('1', 'a'));
    }

    public function testFetchForeignRecords_empty()
    {
        $fake = new FakeRegularRelationship(
            'fake',
            $this->mapperLocator,
            Thread::CLASS,
            Author::CLASS
        );
        $this->assertSame([], $fake->fetchForeignRecords([], null));
    }
}
