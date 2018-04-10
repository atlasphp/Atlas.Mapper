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

class ManyToOneVariantTest extends RelationshipTest
{
    use Assertions;

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

    public function testJoinSelect()
    {
        $select = $this->mapperLocator->get(CommentMapper::CLASS)->select();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Cannot JOIN on variant relationships.');
        $select->joinWith('LEFT', 'commentable');
    }

    public function testWhere()
    {
        $relationship = new ManyToOneVariant(
            'foo',
            $this->mapperLocator,
            COmmentMapper::CLASS,
            'related_type'
        );

        // should apply as a default to all types
        $relationship->where('foo = 1');

        // default condition
        $relationship
            ->type('page', PageMapper::CLASS, ['related_id' => 'page_id']);

        // additional condition
        $relationship
            ->type('post', PostMapper::CLASS, ['related_id' => 'post_id'])
            ->where('bar = 2');

        // different additional condition
        $relationship
            ->type('video', PostMapper::CLASS, ['related_id' => 'video_id'])
            ->where('baz = 3');

        // brittle assertions
        $variants = $this->getProperty($relationship, 'variants');

        $actual = $this->getProperty($variants['page'], 'where');
        $expect = [
            ['foo = 1'],
        ];
        $this->assertSame($expect, $actual);

        $actual = $this->getProperty($variants['post'], 'where');
        $expect = [
            ['foo = 1'],
            ['bar = 2'],
        ];
        $this->assertSame($expect, $actual);

        $actual = $this->getProperty($variants['video'], 'where');
        $expect = [
            ['foo = 1'],
            ['baz = 3'],
        ];
        $this->assertSame($expect, $actual);
    }

    public function testIgnoreCase()
    {
        $relationship = new ManyToOneVariant(
            'foo',
            $this->mapperLocator,
            CommentMapper::CLASS,
            'related_type'
        );

        // should apply as a default to all types
        $relationship->ignoreCase(true);

        // flag should apply
        $relationship
            ->type('page', PageMapper::CLASS, ['related_id' => 'page_id']);

        // different flag
        $relationship
            ->type('post', PostMapper::CLASS, ['related_id' => 'post_id'])
            ->ignoreCase(false);

        // brittle assertions
        $variants = $this->getProperty($relationship, 'variants');

        $actual = $this->getProperty($variants['page'], 'ignoreCase');
        $this->assertTrue($actual);

        $actual = $this->getProperty($variants['post'], 'ignoreCase');
        $this->assertFalse($actual);
    }

    protected function getProperty($object, $name)
    {
        $rclass = new \ReflectionClass(get_class($object));
        $rprop = $rclass->getProperty($name);
        $rprop->setAccessible(true);
        return $rprop->getValue($object);
    }
}
