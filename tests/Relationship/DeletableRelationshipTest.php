<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Testing\DataSource\Author\Author;
use Atlas\Testing\DataSource\Summary\Summary;
use Atlas\Testing\DataSource\Tag\Tag;
use Atlas\Testing\DataSource\Tagging\Tagging;
use Atlas\Testing\DataSource\Thread\Thread;

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
            $this->assertSame($row::DELETED, $row->getStatus());
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
        $this->assertSame($row::DELETED, $row->getStatus());
    }

    public function testSetDelete()
    {
        $threadMapper = $this->mapperLocator->get(Thread::CLASS);
        $thread = $threadMapper->fetchRecord(1, ['replies']);

        $threadMapper->delete($thread);
        foreach ($thread->replies as $reply) {
            $row = $reply->getRow();
            $this->assertSame($row::SELECTED, $row->getStatus());
            $this->assertSame($row::DELETE, $row->getAction());
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
