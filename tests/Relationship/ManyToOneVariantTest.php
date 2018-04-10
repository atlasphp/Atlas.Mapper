<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use Atlas\Pdo\Connection;
use Atlas\Pdo\Profiler;
use Atlas\Testing\Assertions;
use Atlas\Testing\DataSource\Comment\CommentMapper;
use Atlas\Testing\DataSource\Comment\CommentRecord;
use Atlas\Testing\DataSource\Page\PageMapper;
use Atlas\Testing\DataSource\Page\PageRecord;
use Atlas\Testing\DataSource\Post\PostMapper;
use Atlas\Testing\DataSource\Post\PostRecord;
use Atlas\Testing\DataSource\SqliteFixture;
use Atlas\Testing\DataSource\Video\VideoMapper;
use Atlas\Testing\DataSource\Video\VideoRecord;

class ManyToOneVariantTest extends \PHPUnit\Framework\TestCase
{
    use Assertions;

    protected $mapperLocator;

    protected function setUp()
    {
        $connection = (new SqliteFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
    }

    public function testFetchVariant()
    {
        $comments = $this->mapperLocator->get(CommentMapper::CLASS)
            ->select()
            ->orderBy('comment_id')
            ->with(['commentable'])
            ->limit(3)
            ->fetchRecords();

        $this->assertInstanceOf(PageRecord::CLASS, $comments[0]->commentable);
        $this->assertInstanceOf(PostRecord::CLASS, $comments[1]->commentable);
        $this->assertInstanceOf(VideoRecord::CLASS, $comments[2]->commentable);
    }

    public function testInsertVariant()
    {
        $page = $this->mapperLocator->get(PageMapper::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'commentable' => $page,
            'body' => 'New comment on page',
        ]);

        $this->assertNull($comment->related_type);
        $this->assertNull($comment->related_id);
        $this->mapperLocator->get(CommentMapper::CLASS)->insert($comment);
        $this->assertEquals('page', $comment->related_type);
        $this->assertEquals($page->page_id, $comment->related_id);
    }

    public function testPersistVariant()
    {
        $page = $this->mapperLocator->get(PageMapper::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'commentable' => $page,
            'body' => 'New comment on page',
        ]);

        $this->assertNull($comment->related_type);
        $this->assertNull($comment->related_id);
        $success = $this->mapperLocator->get(PageMapper::CLASS)->persist($page);
        $this->assertTrue($success);
        $this->assertEquals('page', $comment->related_type);
        $this->assertEquals($page->page_id, $comment->related_id);
    }

    public function testPersistVariant_noSuchType()
    {
        $page = $this->mapperLocator->get(PageMapper::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'related_type' => 'NO_SUCH_TYPE',
            'body' => 'New comment on page',
        ]);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Variant relationship type 'NO_SUCH_TYPE' not defined in Atlas\Testing\DataSource\Comment\CommentMapper."
        );
        $this->mapperLocator->get(PageMapper::CLASS)->persist($page);
    }

    public function testPersistVariant_emptyType()
    {
        $page = $this->mapperLocator->get(PageMapper::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'commentable' => $this->mapperLocator->get(CommentMapper::CLASS)->newRecord(),
            'body' => 'New comment on page',
        ]);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Variant relationship type '' not defined in Atlas\Testing\DataSource\Comment\CommentMapper."
        );
        $this->mapperLocator->get(PageMapper::CLASS)->persist($page);
    }

    public function testStitchIntoRecords_noNativeRecords()
    {
        $relationship = $this->mapperLocator
            ->get(CommentMapper::CLASS)
            ->getRelationships()
            ->get('commentable');

        $nativeRecords = [];
        $relationship->stitchIntoRecords($nativeRecords);
        $this->assertSame([], $nativeRecords);
    }
}
