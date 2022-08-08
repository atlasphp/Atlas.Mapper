<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\DataSource\Author\Author;
use Atlas\Mapper\DataSource\Summary\Summary;
use Atlas\Mapper\DataSource\Tag\Tag;
use Atlas\Mapper\DataSource\Tagging\Tagging;
use Atlas\Mapper\DataSource\Thread\Thread;

class DeletableRelationshipTest extends RelationshipTest
{
    public function testCascade()
    {
        $taggingMapper = $this->mapperLocator->get(Tagging::CLASS);
        $this->assertEquals(12, $taggingMapper
            ->select(['tag_id' => 1])
            ->columns('COUNT (*)')
            ->fetchValue()
        );

        $tagMapper = $this->mapperLocator->get(Tag::CLASS);
        $tag = $tagMapper->fetchRecord(1, ['taggings']);
        $this->assertCount(12, $tag->taggings);
        $tagMapper->delete($tag);
        foreach ($tag->taggings as $tagging) {
            $row = $tagging->getRow();
            $this->assertSame($row::DELETE, $row->getLastAction());
        }

        $this->assertEquals(0, $taggingMapper
            ->select(['tag_id' => 1])
            ->columns('COUNT (*)')
            ->fetchValue()
        );
    }

    public function testInitDeleted()
    {
        $threadMapper = $this->mapperLocator->get(Thread::CLASS);
        $thread = $threadMapper->fetchRecord(1, ['summary']);

        $threadMapper->delete($thread);

        $row = $thread->summary->getRow();
        $this->assertSame($row::DELETE, $row->getLastAction());
    }

    public function testSetDelete()
    {
        $threadMapper = $this->mapperLocator->get(Thread::CLASS);
        $thread = $threadMapper->fetchRecord(1, ['replies']);

        $threadMapper->delete($thread);
        foreach ($thread->replies as $reply) {
            $row = $reply->getRow();
            $this->assertSame($row::SELECT, $row->getLastAction());
            $this->assertSame($row::DELETE, $row->getNextAction());
        }
    }

    public function testSetNull()
    {
        $threadMapper = $this->mapperLocator->get(Thread::CLASS);
        $thread = $threadMapper->fetchRecord(1, ['taggings']);

        $threadMapper->delete($thread);
        foreach ($thread->taggings as $tagging) {
            $this->assertNull($tagging->thread_id);
        }
    }
}
