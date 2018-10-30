<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Exception;
use Atlas\Mapper\MapperLocator;
use Atlas\Testing\DataSource\Author\Author;
use Atlas\Testing\DataSource\Thread\Thread;
use Atlas\Testing\DataSource\Tag\Tag;
use Atlas\Testing\DataSource\Tagging\Tagging;
use Atlas\Testing\Assertions;

class ManyToManyTest extends RelationshipTest
{
    use Assertions;

    public function testForeignPersist_basic()
    {
        $threadMapper = $this->mapperLocator->get(Thread::CLASS);

        $before = $threadMapper->fetchRecord(1, [
            'tags'
        ]);

        $tags = $this->mapperLocator->get(Tag::CLASS)
            ->select()
            ->fetchRecordSet();

        // remove tag 'foo'
        $before->tags->detachOneBy(['name' => 'foo']);

        // add tag 'zim'
        $tag = $tags->getOneBy(['name' => 'zim']);
        $before->tags[] = $tag;

        // persist it
        $threadMapper->persist($before);

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
                'thread' => NULL,
                'tag' => NULL,
            ),
            array (
                'tagging_id' => '3',
                'thread_id' => '1',
                'tag_id' => '3',
                'thread' => NULL,
                'tag' => NULL,
            ),
            array (
                'tagging_id' => '58',
                'thread_id' => '1',
                'tag_id' => '5',
                'thread' => NULL,
                'tag' => NULL,
            ),
        );
        $actual = $after->taggings->getArrayCopy();
        $this->assertEquals($expect, $actual);

        $this->assertNotSame($before->tags, $after->tags);
        $expect = array (
            array (
                'tag_id' => '2',
                'name' => 'bar',
                'taggings' => NULL,
            ),
            array (
                'tag_id' => '3',
                'name' => 'baz',
                'taggings' => NULL,
            ),
            array (
                'tag_id' => '5',
                'name' => 'zim',
                'taggings' => NULL,
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

    public function testJoinSelect()
    {
        $actual = $this->mapperLocator->get(Thread::CLASS)
            ->select()
            ->joinWith('tags')
            ->where('tags.name = ', 'foo')
            ->getStatement();

        $expect = '
            SELECT

            FROM
                "threads"
                    JOIN "taggings" ON "threads"."thread_id" = "taggings"."thread_id"
                    JOIN "tags" ON "taggings"."tag_id" = "tags"."tag_id"
            WHERE
                tags.name = :__1__
        ';

        $this->assertSameSql($expect, $actual);
    }

    public function testMissingThroughNativeRelated()
    {
        $through = new OneToMany(
            'thread_taggings',
            $this->mapperLocator,
            Thread::CLASS,
            Tagging::CLASS
        );

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Could not find ManyToOne native relationship through "
            . "'thread_taggings' for ManyToMany 'tag_authors' on "
            . "Atlas\Testing\DataSource\Author\Author"
        );

        $mtm = new ManyToMany(
            'tag_authors',
            $this->mapperLocator,
            Author::CLASS,
            Tag::CLASS,
            $through
        );
    }

    public function testMissingThroughForeignRelated()
    {
        $through = new OneToMany(
            'thread_taggings',
            $this->mapperLocator,
            Thread::CLASS,
            Tagging::CLASS
        );

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Could not find ManyToOne foreign relationship through "
            . "'thread_taggings' for ManyToMany 'tag_authors' on "
            . "Atlas\Testing\DataSource\Tag\Tag"
        );

        $mtm = new ManyToMany(
            'tag_authors',
            $this->mapperLocator,
            Tag::CLASS,
            Author::CLASS,
            $through
        );
    }
}
