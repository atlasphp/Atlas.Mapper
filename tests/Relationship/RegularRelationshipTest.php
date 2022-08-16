<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Define;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeMapperRelationships;
use Atlas\Mapper\Fake\FakeRegularRelationship;
use Atlas\Mapper\DataSource\Author\Author;
use Atlas\Mapper\DataSource\Thread\Thread;

class RegularRelationshipTest extends RelationshipTest
{
    protected $fakeMapperRelationships;

    protected function setUp() : void
    {
        parent::setUp();
        $this->fakeMapperRelationships = new FakeMapperRelationships(
            $this->mapperLocator,
            Thread::CLASS
        );
    }

    public function testClassDoesNotExist()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage("Class 'NoSuchClass' does not exist.");
        $fake = new FakeRegularRelationship(
            'fake',
            new Define\OneToOne(),
            $this->mapperLocator,
            Thread::CLASS,
            'NoSuchClass',
            $this->fakeMapperRelationships,
        );
    }

    public function testValuesDontMatch()
    {
        $fake = new FakeRegularRelationship(
            'fake',
            new Define\OneToOne(),
            $this->mapperLocator,
            Thread::CLASS,
            Author::CLASS,
            $this->fakeMapperRelationships,
        );
        $this->assertFalse($fake->valuesMatch('1', 'a'));
    }

    public function testFetchForeignRecords_empty()
    {
        $fake = new FakeRegularRelationship(
            'fake',
            new Define\OneToOne(),
            $this->mapperLocator,
            Thread::CLASS,
            Author::CLASS,
            $this->fakeMapperRelationships,
        );
        $this->assertSame([], $fake->fetchForeignRecords([], null));
    }
}
