<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Container;
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

class ManyToOneByReferenceTest extends \PHPUnit\Framework\TestCase
{
    use Assertions;

    protected $mapperLocator;

    protected function setUp()
    {
        $connection = (new SqliteFixture())->exec();

        $container = new Container($connection);
        $container->setMappers([
            CommentMapper::CLASS,
            PageMapper::CLASS,
            PostMapper::CLASS,
            VideoMapper::CLASS,
        ]);

        $this->mapperLocator = $container->newMapperLocator();
    }

    public function testFetchByReference()
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

    public function testInsertByReference()
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

    public function testPersistByReference()
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

    public function testPersistByReference_noSuchReferenceValue()
    {
        $page = $this->mapperLocator->get(PageMapper::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'related_type' => 'NO_SUCH_TYPE',
            'body' => 'New comment on page',
        ]);

        $this->expectException(
            Exception::CLASS,
            "Reference relationship for 'NO_SUCH_TYPE' not defined in Atlas\Testing\DataSource\Comment\CommentMapper."
        );
        $this->mapperLocator->get(PageMapper::CLASS)->persist($page);
    }

    public function testPersistByReference_noSuchReferenceMapper()
    {
        $page = $this->mapperLocator->get(PageMapper::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'commentable' => $this->mapperLocator->get(CommentMapper::CLASS)->newRecord(),
            'body' => 'New comment on page',
        ]);

        $this->expectException(
            Exception::CLASS,
            "Reference relationship for '' not defined in Atlas\Testing\DataSource\Comment\CommentMapper."
        );
        $this->mapperLocator->get(PageMapper::CLASS)->persist($page);
    }

    public function testWhere()
    {
        $relationship = $this->mapperLocator
            ->get(CommentMapper::CLASS)
            ->getRelationships()
            ->get('commentable');

        $this->expectException(Exception::CLASS);
        $relationship->where('foo = ', 'bar');
    }

    public function testIgnoreCase()
    {
        $relationship = $this->mapperLocator
            ->get(CommentMapper::CLASS)
            ->getRelationships()
            ->get('commentable');

        $this->expectException(Exception::CLASS);
        $relationship->ignoreCase();
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
