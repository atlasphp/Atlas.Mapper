<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Testing\Assertions;
use Atlas\Testing\DataSource\Author\Author;
use Atlas\Testing\DataSource\Tag\Tag;
use Atlas\Testing\DataSource\Tagging\Tagging;
use Atlas\Testing\DataSource\Thread\Thread;
use Atlas\Testing\DataSource\Thread\ThreadTable;
use Atlas\Testing\DataSource\Thread\ThreadRelated;
use Atlas\Mapper\Define;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRelationshipLocator;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Relationship\NotLoaded;

class ManyToManyTest extends RelationshipTest
{
    use Assertions;

    public function testStitchIntoRecords_noNativeRecords()
    {
        $rel = $this
            ->mapperLocator
            ->get(Thread::CLASS)
            ->getRelationshipLocator()
            ->get('tags');

        $threads = [];
        $rel->stitchIntoRecords($threads);
        $this->assertEmpty($threads);
    }

    public function testForeignPersist_basic()
    {
        $threadMapper = $this->mapperLocator->get(Thread::CLASS);

        $before = $threadMapper->fetchRecord(1, [
            'tags'
        ]);

        $tags = $this->mapperLocator->get(Tag::CLASS)
            ->select()
            ->fetchRecordSet();

        // make sure there are three taggings and tags
        $this->assertCount(3, $before->tags);
        $this->assertCount(3, $before->taggings);

        // remove tag 'foo'
        $before->tags->detachOneBy(['name' => 'foo']);

        // add tag 'zim'
        $tag = $tags->getOneBy(['name' => 'zim']);
        $before->tags[] = $tag;

        // persist it
        $threadMapper->persist($before);

        // make sure the taggings are detached properly
        $this->assertCount(3, $before->tags);
        $this->assertCount(3, $before->taggings);

        // re-get the thread, with new taggings
        $after = $threadMapper->fetchRecord(1, [
            'tags'
        ]);

        $this->assertNotSame($before->taggings, $after->taggings);
        $expect = array (
            array (
                'tagging_id' => '2',
                'thread_id' => '1',
                'tag_id' => '2',
                'thread' => NotLoaded::getInstance(),
                'tag' => NotLoaded::getInstance(),
            ),
            array (
                'tagging_id' => '3',
                'thread_id' => '1',
                'tag_id' => '3',
                'thread' => NotLoaded::getInstance(),
                'tag' => NotLoaded::getInstance(),
            ),
            array (
                'tagging_id' => '58',
                'thread_id' => '1',
                'tag_id' => '5',
                'thread' => NotLoaded::getInstance(),
                'tag' => NotLoaded::getInstance(),
            ),
        );
        $actual = $after->taggings->getArrayCopy();
        $this->assertEquals($expect, $actual);

        $this->assertNotSame($before->tags, $after->tags);
        $expect = array (
            array (
                'tag_id' => '2',
                'name' => 'bar',
                'taggings' => NotLoaded::getInstance(),
            ),
            array (
                'tag_id' => '3',
                'name' => 'baz',
                'taggings' => NotLoaded::getInstance(),
            ),
            array (
                'tag_id' => '5',
                'name' => 'zim',
                'taggings' => NotLoaded::getInstance(),
            ),
        );
        $actual = $after->tags->getArrayCopy();
        $this->assertEquals($expect, $actual);
    }

    public function testForeignPersist_allNew()
    {
        $thread = $this->mapperLocator->get(Thread::CLASS)->newRecord();
        $thread->author = $this->mapperLocator->get(Author::CLASS)->fetchRecord(1);
        $thread->subject = "New subject";
        $thread->body = "New body";

        $tags = $this->mapperLocator->get(Tag::CLASS)->select()->fetchRecordSet();
        $thread->tags = $this->mapperLocator->get(Tag::CLASS)->newRecordSet();
        $thread->tags[] = $tags->getOneBy(['name' => 'foo']);
        $thread->tags[] = $tags->getOneBy(['name' => 'baz']);
        $thread->tags[] = $tags->getOneBy(['name' => 'zim']);

        $this->mapperLocator->get(Thread::CLASS)->persist($thread);

        $expect = [
            [
                'tagging_id' => '58',
                'thread_id' => '21',
                'tag_id' => '1',
            ],
            [
                'tagging_id' => '59',
                'thread_id' => '21',
                'tag_id' => '3',
            ],
            [
                'tagging_id' => '60',
                'thread_id' => '21',
                'tag_id' => '5',
            ],
        ];

        $actual = $thread->taggings->getArrayCopy();
        array_walk($actual, function (&$tagging, $key) {
            unset($tagging['thread']);
            unset($tagging['tag']);
        });

        $this->assertEquals($expect, $actual);
    }

    public function testForeignPersist_detachAll()
    {
        $threadMapper = $this->mapperLocator->get(Thread::CLASS);

        $before = $threadMapper->fetchRecord(1, [
            'tags'
        ]);

        $this->assertCount(3, $before->taggings);
        $this->assertCount(3, $before->tags);

        $before->tags->detachAll();
        $threadMapper->persist($before);

        $this->assertCount(0, $before->tags);
        $this->assertCount(0, $before->taggings);

        $after = $threadMapper->fetchRecord(1, [
            'tags'
        ]);

        $this->assertCount(0, $after->taggings);
        $this->assertCount(0, $after->tags);
    }

    public function testJoinSelect()
    {
        $actual = $this->mapperLocator->get(Thread::CLASS)
            ->select()
            ->joinRelated('tags')
            ->getQueryString();

        $expect = '
            SELECT

            FROM
                "threads"
                    JOIN "taggings" ON "threads"."thread_id" = "taggings"."thread_id"
                    JOIN "tags" ON "taggings"."tag_id" = "tags"."tag_id"
        ';

        $this->assertSameSql($expect, $actual);
    }

    public function testMissingThroughNativeRelated()
    {
        $fakeRelationshipLocator = new FakeRelationshipLocator(
            $this->mapperLocator,
            Thread::CLASS,
            ThreadTable::CLASS,
            ThreadRelated::CLASS,
        );

        $fakeRelationshipLocator->setFake('thread_taggings', new OneToMany(
            'thread_taggings',
            $this->mapperLocator,
            Thread::CLASS,
            Tagging::CLASS,
            new Define\OneToMany(),
            $fakeRelationshipLocator,
        ));

        $this->expectException(Exception\ThroughRelatedDoesNotExist::CLASS);
        $this->expectExceptionMessage(
            "ManyToMany Atlas\Testing\DataSource\Author\AuthorRelated::\$tag_authors "
            . "goes through ManyToOne \$thread_taggings, but "
            . "Atlas\Testing\DataSource\Tagging\TaggingRelated does not define "
            . "a ManyToOne property typehinted to Atlas\Testing\DataSource\Author\AuthorRecord."
        );

        $mtm = new ManyToMany(
            'tag_authors',
            $this->mapperLocator,
            Author::CLASS,
            Tag::CLASS,
            new Define\ManyToMany(through: 'thread_taggings'),
            $fakeRelationshipLocator
        );
    }

    public function testMissingThroughForeignRelated()
    {
        $fakeRelationshipLocator = new FakeRelationshipLocator(
            $this->mapperLocator,
            Thread::CLASS,
            ThreadTable::CLASS,
            ThreadRelated::CLASS,
        );

        $fakeRelationshipLocator->setFake('thread_taggings', new OneToMany(
            'thread_taggings',
            $this->mapperLocator,
            Thread::CLASS,
            Tagging::CLASS,
            new Define\OneToMany(),
            $fakeRelationshipLocator,
        ));

        $this->expectException(Exception\ThroughRelatedDoesNotExist::CLASS);
        $this->expectExceptionMessage(
            "ManyToMany Atlas\Testing\DataSource\Tag\TagRelated::\$tag_authors "
            . "goes through ManyToOne \$thread_taggings, but "
            . "Atlas\Testing\DataSource\Tagging\TaggingRelated does not define "
            . "a ManyToOne property typehinted to Atlas\Testing\DataSource\Author\AuthorRecord."
        );

        $mtm = new ManyToMany(
            'tag_authors',
            $this->mapperLocator,
            Tag::CLASS,
            Author::CLASS,
            new Define\ManyToMany(through: 'thread_taggings'),
            $fakeRelationshipLocator
        );
    }
}
