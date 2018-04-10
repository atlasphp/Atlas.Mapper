<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRelationship;
use Atlas\Testing\DataSource\Author\AuthorMapper;
use Atlas\Testing\DataSource\Thread\ThreadMapper;

class RegularRelationshipTest extends RelationshipTest
{
    public function testClassDoesNotExist()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("Class 'NoSuchClass' does not exist.");
        $fake = new FakeRelationship(
            'fake',
            $this->mapperLocator,
            ThreadMapper::CLASS,
            'NoSuchClass'
        );
    }

    public function testValuesDontMatch()
    {
        $fake = new FakeRelationship(
            'fake',
            $this->mapperLocator,
            ThreadMapper::CLASS,
            AuthorMapper::CLASS
        );
        $this->assertFalse($fake->valuesMatch('1', 'a'));
    }

    public function testFetchForeignRecords_empty()
    {
        $fake = new FakeRelationship(
            'fake',
            $this->mapperLocator,
            ThreadMapper::CLASS,
            AuthorMapper::CLASS
        );
        $this->assertSame([], $fake->fetchForeignRecords([], null));
    }
}
