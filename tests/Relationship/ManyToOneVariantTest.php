<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Assertions;
use Atlas\Mapper\DataSource\Comment\Comment;
use Atlas\Mapper\DataSource\Comment\CommentRecord;
use Atlas\Mapper\DataSource\Page\Page;
use Atlas\Mapper\DataSource\Page\PageRecord;
use Atlas\Mapper\DataSource\Post\Post;
use Atlas\Mapper\DataSource\Post\PostRecord;
use Atlas\Mapper\DataSource\Video\Video;
use Atlas\Mapper\DataSource\Video\VideoRecord;
use Atlas\Mapper\DataSourceFixture;
use Atlas\Mapper\Define\Variant;
use Atlas\Mapper\Define;
use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeMapperRelationships;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use Atlas\Pdo\Connection;
use Atlas\Pdo\Profiler;

class ManyToOneVariantTest extends RelationshipTest
{
    use Assertions;

    public function testFetchVariant()
    {
        $comments = $this->mapperLocator->get(Comment::CLASS)
            ->select()
            ->orderBy('comment_id')
            ->loadRelated(['commentable'])
            ->limit(3)
            ->fetchRecords();

        $this->assertInstanceOf(PageRecord::CLASS, $comments[0]->commentable);
        $this->assertInstanceOf(PostRecord::CLASS, $comments[1]->commentable);
        $this->assertInstanceOf(VideoRecord::CLASS, $comments[2]->commentable);
    }

    public function testInsertVariant()
    {
        $page = $this->mapperLocator->get(Page::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'commentable' => $page,
            'body' => 'New comment on page',
        ]);

        $this->assertNull($comment->related_type);
        $this->assertNull($comment->related_id);
        $this->mapperLocator->get(Comment::CLASS)->insert($comment);
        $this->assertEquals('page', $comment->related_type);
        $this->assertEquals($page->page_id, $comment->related_id);
    }

    public function testPersistVariant()
    {
        $page = $this->mapperLocator->get(Page::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'commentable' => $page,
            'body' => 'New comment on page',
        ]);

        $this->assertNull($comment->related_type);
        $this->assertNull($comment->related_id);
        $this->mapperLocator->get(Page::CLASS)->persist($page);
        $this->assertEquals('page', $comment->related_type);
        $this->assertEquals($page->page_id, $comment->related_id);
    }

    public function testPersistVariant_noSuchType()
    {
        $page = $this->mapperLocator->get(Page::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'related_type' => 'NO_SUCH_TYPE',
            'body' => 'New comment on page',
        ]);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Variant relationship type 'NO_SUCH_TYPE' not defined in Atlas\Mapper\DataSource\Comment\CommentRelationships."
        );
        $this->mapperLocator->get(Page::CLASS)->persist($page);
    }

    public function testPersistVariant_emptyType()
    {
        $page = $this->mapperLocator->get(Page::CLASS)->fetchRecord(1, ['comments']);
        $comment = $page->comments->appendNew([
            'commentable' => $this->mapperLocator->get(Comment::CLASS)->newRecord(),
            'body' => 'New comment on page',
        ]);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Variant relationship type '' not defined in Atlas\Mapper\DataSource\Comment\CommentRelationships."
        );
        $this->mapperLocator->get(Page::CLASS)->persist($page);
    }

    public function testStitchIntoRecords_noNativeRecords()
    {
        $relationship = $this->mapperLocator
            ->get(Comment::CLASS)
            ->getRelationshipLocator()
            ->get('commentable');

        $nativeRecords = [];
        $relationship->stitchIntoRecords($nativeRecords);
        $this->assertSame([], $nativeRecords);
    }

    public function testJoinSelect()
    {
        $select = $this->mapperLocator->get(Comment::CLASS)->select();
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Cannot JOIN on variant relationships.');
        $select->joinRelated('LEFT commentable');
    }

    public function testWhere()
    {
        $relationship = new ManyToOneVariant(
            'foo',
            $this->mapperLocator,
            Comment::CLASS,
            'UNKNOWN',
            new Define\ManyToOneVariant(column: 'related_type'),
            new RelationshipLocator($this->mapperLocator, Comment::CLASS),
        );

        // should apply as a default to all types
        $relationship->where('foo = 1');

        // default condition
        $relationship
            ->type(new Variant('page', Page::CLASS, ['related_id' => 'page_id']));

        // additional condition
        $relationship
            ->type(new Variant('post', Post::CLASS, ['related_id' => 'post_id']))
            ->where('bar = 2');

        // different additional condition
        $relationship
            ->type(new Variant('video', Post::CLASS, ['related_id' => 'video_id']))
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
            Comment::CLASS,
            'UNKNOWN',
            new Define\ManyToOneVariant(column: 'related_type'),
            new RelationshipLocator($this->mapperLocator, Comment::CLASS),
        );

        // should apply as a default to all types
        $relationship->ignoreCase(true);

        // flag should apply
        $relationship
            ->type(new Variant('page', Page::CLASS, ['related_id' => 'page_id']));

        // different flag
        $relationship
            ->type(new Variant('post', Post::CLASS, ['related_id' => 'post_id']))
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
