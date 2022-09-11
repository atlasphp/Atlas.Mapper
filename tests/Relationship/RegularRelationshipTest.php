<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Testing\DataSource\Author\Author;
use Atlas\Testing\DataSource\Thread\Thread;
use Atlas\Mapper\Define;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRegularRelationship;
use Atlas\Mapper\Fake\FakeRelationshipLocator;
use Atlas\Mapper\MapperRelationships;

class RegularRelationshipTest extends RelationshipTest
{
    protected $fakeRelationshipLocator;

    protected function setUp() : void
    {
        parent::setUp();

        $this->fakeRelationshipLocator = new FakeRelationshipLocator(
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
            $this->mapperLocator,
            Thread::CLASS,
            'NoSuchClass',
            new Define\OneToOne(),
            $this->fakeRelationshipLocator,
        );
    }

    public function testValuesDontMatch()
    {
        $fake = new FakeRegularRelationship(
            'fake',
            $this->mapperLocator,
            Thread::CLASS,
            Author::CLASS,
            new Define\OneToOne(),
            $this->fakeRelationshipLocator,
        );
        $this->assertFalse($fake->valuesMatch('1', 'a'));
    }

    public function testFetchForeignRecords_empty()
    {
        $fake = new FakeRegularRelationship(
            'fake',
            $this->mapperLocator,
            Thread::CLASS,
            Author::CLASS,
            new Define\OneToOne(),
            $this->fakeRelationshipLocator,
        );
        $this->assertSame([], $fake->fetchForeignRecords([], null));
    }
}
