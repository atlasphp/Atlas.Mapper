<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;
use Atlas\Pdo\Connection;
use Atlas\Pdo\Profiler;
use Atlas\Testing\Assertions;
use Atlas\Testing\DataSource\Author\AuthorMapper;
use Atlas\Testing\DataSource\Reply\ReplyMapper;
use Atlas\Testing\DataSource\Reply\ReplyRecord;
use Atlas\Testing\DataSource\Reply\ReplyRecordSet;
use Atlas\Testing\DataSource\SqliteFixture;
use Atlas\Testing\DataSource\Summary\SummaryMapper;
use Atlas\Testing\DataSource\Summary\SummaryTable;
use Atlas\Testing\DataSource\Tag\TagMapper;
use Atlas\Testing\DataSource\Tagging\TaggingMapper;
use Atlas\Testing\DataSource\Thread\ThreadMapper;
use Atlas\Testing\DataSource\Thread\ThreadRecord;
use Atlas\Testing\DataSource\Thread\ThreadRecordSet;

class MapperTest extends \PHPUnit\Framework\TestCase
{
    use Assertions;

    protected $mappers;

    // The $expect* properties are at the end, because they are so long

    protected function setUp()
    {
        $connection = (new SqliteFixture())->exec();
        $this->mapperLocator = MapperLocator::new($connection);
    }

    public function testNewRecord()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->newRecord();
        $this->assertInstanceOf(ThreadRecord::CLASS, $actual);

        $actual = $this->mapperLocator->get(ReplyMapper::CLASS)->newRecord();
        $this->assertInstanceOf(ReplyRecord::CLASS, $actual);
    }

    public function testNewRecordSet()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->newRecordSet();
        $this->assertInstanceOf(ThreadRecordSet::CLASS, $actual);
    }

    public function testFetchRecord()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecord(
            1,
            [
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ]
        );

        $this->assertInstanceOf(Record::CLASS, $actual->author);
        $this->assertInstanceOf(Record::CLASS, $actual->summary);
        $this->assertInstanceOf(RecordSet::CLASS, $actual->replies);
        $this->assertInstanceOf(RecordSet::CLASS, $actual->taggings);

        $this->assertSame($this->expectRecord, $actual->getArrayCopy());

        // did the rows identity-map?
        $again = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecord(1);
        $this->assertSame($actual->getRow(), $again->getRow());
    }

    public function testFetchRecord_missing()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecord(88);
        $this->assertNull($actual);
    }

    public function testFetchRecordBy()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecordBy(
            ['thread_id' => 1],
            [
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ]
        );

        $this->assertSame($this->expectRecord, $actual->getArrayCopy());

        // did the rows identity-map?
        $again = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecord(1);
        $this->assertSame($actual->getRow(), $again->getRow());
    }

    public function testFetchRecordSet()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecordSet(
            [1, 2, 3],
            [
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ]
        )->getArrayCopy();

        foreach ($this->expectRecordSet as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testFetchRecordSetBy()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecordSetBy(
            ['thread_id' => [1, 2, 3]],
            [
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ]
        )->getArrayCopy();

        foreach ($this->expectRecordSet as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testFetchRecords()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecords(
            [1, 2, 3, 88],
            [
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ]
        );

        foreach ($this->expectRecordSet as $i => $expect) {
            $array = $actual[$i]->getArrayCopy();
            $this->assertSame($expect, $array, "record $i not the same");
        }
    }

    public function testFetchRecordsBy()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecordsBy(
            ['thread_id' => [1, 2, 3]],
            [
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ]
        );

        foreach ($this->expectRecordSet as $i => $expect) {
            $array = $actual[$i]->getArrayCopy();
            $this->assertSame($expect, $array, "record $i not the same");
        }
    }

    public function testSelect_fetchRecord()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->select()
            ->where('thread_id < ', 2)
            ->with([
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ])
            ->fetchRecord();

        $this->assertSame($this->expectRecord, $actual->getArrayCopy());
    }

    public function testSelect_fetchRecordNestedArrayWith()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->select()
            ->where('thread_id < ', 2)
            ->with([
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ])
            ->fetchRecord();

        $this->assertSame($this->expectRecord, $actual->getArrayCopy());
    }

    public function testSelect_fetchRecordCallableArrayWith()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->select()
            ->where('thread_id < ', 2)
            ->with([
                'author',
                'summary',
                'replies' => [
                    new Fake\CallableWithObject(),
                    'replies'
                ],
                'taggings' => [
                    'tag',
                ],
            ])
            ->fetchRecord();

        $this->assertSame($this->expectRecord, $actual->getArrayCopy());
    }

    public function testSelect_fetchRecordSet()
    {
        $actual = $this->mapperLocator->get(ThreadMapper::CLASS)->select()
            ->where('thread_id < ', 4)
            ->with([
                'author',
                'summary',
                'replies' => function ($select) {
                    $select->with(['author']);
                },
                'taggings' => [
                    'tag',
                ],
            ])
            ->fetchRecordSet()
            ->getArrayCopy();

        foreach ($this->expectRecordSet as $i => $expect) {
            $this->assertSame($expect, $actual[$i], "record $i not the same");
        }
    }

    public function testInsert()
    {
        // create a new record
        $author = $this->mapperLocator->get(AuthorMapper::CLASS)->newRecord();
        $author->name = 'Mona';

        // does the insert *look* successful?
        $success = $this->mapperLocator->get(AuthorMapper::CLASS)->insert($author);
        $this->assertTrue($success);

        // did the autoincrement ID get retained?
        $this->assertEquals(13, $author->author_id);

        // did it save in the identity map?
        $again = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecord(13);
        $this->assertSame($author->getRow(), $again->getRow());

        // was it *actually* inserted?
        $expect = [
            'author_id' => '13',
            'name' => 'Mona',
        ];
        $actual = $this->mapperLocator
            ->get(AuthorMapper::CLASS)
            ->getTable()
            ->getReadConnection()
            ->fetchOne(
                'SELECT * FROM authors WHERE author_id = 13'
            );
        $this->assertSame($expect, $actual);
    }

    public function testUpdate()
    {
        // fetch a record, then modify and update it
        $author = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecordBy(
            ['name' => 'Anna']
        );
        $author->name = 'Annabelle';

        // did the update *look* successful?
        $success = $this->mapperLocator->get(AuthorMapper::CLASS)->update($author);
        $this->assertTrue($success);

        // is it still in the identity map?
        $again = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecordBy(
            ['name' => 'Annabelle']
        );
        $this->assertSame($author->getRow(), $again->getRow());

        // was it *actually* updated?
        $expect = $author->getRow()->getArrayCopy();
        $actual = $this->mapperLocator->get(AuthorMapper::CLASS)
            ->getTable()
            ->getReadConnection()
            ->fetchOne(
                "SELECT * FROM authors WHERE name = 'Annabelle'"
            );
        $this->assertSame($expect, $actual);

        // try to update again, should be a no-op because there are no changes
        $this->assertFalse($this->mapperLocator->get(AuthorMapper::CLASS)->update($author));
    }

    public function testDelete()
    {
        // fetch a record
        $author = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecordBy(
            ['name' => 'Anna']
        );

        // did the delete *look* successful?
        $success = $this->mapperLocator->get(AuthorMapper::CLASS)->delete($author);
        $this->assertTrue($success);

        // was it *actually* deleted?
        $actual = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecordBy(
            ['name' => 'Anna']
        );
        $this->assertNull($actual);
    }

    public function testUpdateFailure()
    {
        // fetch a record
        $author = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecordBy(
            ['name' => 'Anna']
        );

        // set to null, should fail update
        $author->name = null;
        $this->expectException(\PDOException::CLASS);
        $this->expectExceptionMessage(
            'SQLSTATE[23000]: Integrity constraint violation'
        );
        $this->mapperLocator->get(AuthorMapper::CLASS)->update($author);
    }

    public function testCalcPrimary()
    {
        // plain old primary value
        $actual = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecord(1);
        $this->assertSame('1', $actual->author_id);

        // // primary embedded in array
        // $actual = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecord([
        //     'author_id' => 2,
        //     'foo' => 'bar',
        //     'baz' => 'dib'
        // ]);
        // $this->assertSame('2', $actual->author_id);

        // not a scalar
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Expected scalar value for primary key 'author_id', got array instead."
        );
        $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecord([1, 2, 3]);
    }

    public function testLeftJoinWith()
    {
        $select = $this->mapperLocator->get(ThreadMapper::CLASS)->select()
            ->distinct()
            ->joinWith('LEFT', 'replies')
            ->orderBy('replies.reply_id DESC');

        $actual = $select->getStatement();

        $expect = 'SELECT DISTINCT

FROM
    threads
LEFT JOIN replies AS replies ON threads.thread_id = replies.thread_id
ORDER BY
    replies.reply_id DESC';

        $this->assertSameSql($expect, $actual);
    }

    public function testInnerJoinWith()
    {
        $select = $this->mapperLocator->get(ThreadMapper::CLASS)->select()
            ->distinct()
            ->joinWith('INNER', 'replies')
            ->orderBy('replies.reply_id DESC');

        $actual = $select->getStatement();

        $expect = 'SELECT DISTINCT

FROM
    threads
INNER JOIN replies AS replies ON threads.thread_id = replies.thread_id
ORDER BY
    replies.reply_id DESC';

        $this->assertSameSql($expect, $actual);
    }

    public function testMissingWith()
    {
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            "Relationship 'no-such-relationship' does not exist."
        );

        $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecord(
            1,
            [
                'no-such-relationship', // manyToOne
            ]
        );
    }

    public function testPersist_allNew()
    {
        $author = $this->mapperLocator->get(AuthorMapper::CLASS)->newRecord([
            'name' => 'New Name',
        ]);

        $tag = $this->mapperLocator->get(TagMapper::CLASS)->newRecord([
            'name' => 'New Tag',
        ]);

        $summary = $this->mapperLocator->get(SummaryMapper::CLASS)->newRecord([
            'reply_count' => 0,
            'view_count' => 0,
        ]);

        $taggings = $this->mapperLocator->get(TaggingMapper::CLASS)->newRecordSet();

        $thread = $this->mapperLocator->get(ThreadMapper::CLASS)->newRecord([
            'subject' => 'New Subject',
            'body' => 'New Body',
            'author' => $author,
            'summary' => $summary,
            'taggings' => $taggings,
        ]);

        $tagging = $thread->taggings->appendNew([
            'thread' => $thread,
            'tag' => $tag,
        ]);

        // persist the thread and all its relateds
        $this->mapperLocator->get(ThreadMapper::CLASS)->persist($thread);

        $this->assertTrue($author->author_id > 0);
        $this->assertTrue($tag->tag_id > 0);
        $this->assertTrue($thread->thread_id > 0);
        $this->assertSame($thread->author_id, $thread->author->author_id);
        $this->assertSame($thread->thread_id, $thread->summary->thread_id);
        $this->assertSame($thread->taggings[0]->thread_id, $thread->thread_id);
    }

    public function testPersist_updateManyToOne()
    {
        $thread = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecord(1, ['author']);
        $this->assertEquals(1, $thread->author_id);

        $author = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecord(2);
        $thread->author = $author;

        $this->mapperLocator->get(ThreadMapper::CLASS)->persist($thread);
        $this->assertEquals(2, $thread->author_id);
    }

    public function testPersist_updateOneToMany()
    {
        $author = $this->mapperLocator->get(AuthorMapper::CLASS)->fetchRecord(1, ['threads']);
        foreach ($author->threads as $thread) {
            $this->assertEquals(1, $thread->author_id);
        }
        $count = count($author->threads);

        $thread = $this->mapperLocator->get(ThreadMapper::CLASS)
            ->select()
            ->where('author_id != 1')
            ->fetchRecord();

        $author->threads[] = $thread;

        $this->mapperLocator->get(AuthorMapper::CLASS)->persist($author);
        $this->assertEquals($count + 1, count($author->threads));
        foreach ($author->threads as $thread) {
            $this->assertEquals(1, $thread->author_id);
        }
    }

    public function testPersist_updateOneToOne()
    {
        $thread = $this->mapperLocator->get(ThreadMapper::CLASS)->fetchRecord(1, ['summary']);
        $this->assertEquals(1, $thread->summary->summary_id); // primary key
        $this->assertEquals(1, $thread->summary->thread_id); // foreign key

        $summary = $this->mapperLocator->get(SummaryMapper::CLASS)
            ->select()
            ->where('thread_id != 1')
            ->fetchRecord();

        $thread->summary = $summary;
        $this->mapperLocator->get(ThreadMapper::CLASS)->persist($thread);
        $this->assertEquals(1, $thread->summary->thread_id); // foreign key
    }

    protected $expectRecord = [
        'thread_id' => '1',
        'author_id' => '1',
        'subject' => 'Thread subject 1',
        'body' => 'Thread body 1',
        'author' => [
            'author_id' => '1',
            'name' => 'Anna',
            'replies' => NULL,
            'threads' => NULL,
        ],
        'summary' => [
            'summary_id' => '1',
            'thread_id' => '1',
            'reply_count' => '5',
            'view_count' => '0',
            'thread' => NULL,
        ],
        'replies' => [
            0 => [
                'reply_id' => '1',
                'thread_id' => '1',
                'author_id' => '2',
                'body' => 'Reply 1 on thread 1',
                'author' => [
                    'author_id' => '2',
                    'name' => 'Betty',
                    'replies' => NULL,
                    'threads' => NULL,
                ],
            ],
            1 => [
                'reply_id' => '2',
                'thread_id' => '1',
                'author_id' => '3',
                'body' => 'Reply 2 on thread 1',
                'author' => [
                    'author_id' => '3',
                    'name' => 'Clara',
                    'replies' => NULL,
                    'threads' => NULL,
                ],
            ],
            2 => [
                'reply_id' => '3',
                'thread_id' => '1',
                'author_id' => '4',
                'body' => 'Reply 3 on thread 1',
                'author' => [
                    'author_id' => '4',
                    'name' => 'Donna',
                    'replies' => NULL,
                    'threads' => NULL,
                ],
            ],
            3 => [
                'reply_id' => '4',
                'thread_id' => '1',
                'author_id' => '5',
                'body' => 'Reply 4 on thread 1',
                'author' => [
                    'author_id' => '5',
                    'name' => 'Edna',
                    'replies' => NULL,
                    'threads' => NULL,
                ],
            ],
            4 => [
                'reply_id' => '5',
                'thread_id' => '1',
                'author_id' => '6',
                'body' => 'Reply 5 on thread 1',
                'author' => [
                    'author_id' => '6',
                    'name' => 'Fiona',
                    'replies' => NULL,
                    'threads' => NULL,
                ],
            ],
        ],
        'taggings' => [
            0 => [
                'tagging_id' => '1',
                'thread_id' => '1',
                'tag_id' => '1',
                'thread' => NULL,
                'tag' => [
                    'tag_id' => '1',
                    'name' => 'foo',
                    'taggings' => NULL,
                ],
            ],
            1 => [
                'tagging_id' => '2',
                'thread_id' => '1',
                'tag_id' => '2',
                'thread' => NULL,
                'tag' => [
                    'tag_id' => '2',
                    'name' => 'bar',
                    'taggings' => NULL,
                ],
            ],
            2 => [
                'tagging_id' => '3',
                'thread_id' => '1',
                'tag_id' => '3',
                'thread' => NULL,
                'tag' => [
                    'tag_id' => '3',
                    'name' => 'baz',
                    'taggings' => NULL,
                ],
            ],
        ],
    ];

    protected $expectRecordSet = [
        0 => [
            'thread_id' => '1',
            'author_id' => '1',
            'subject' => 'Thread subject 1',
            'body' => 'Thread body 1',
            'author' => [
                'author_id' => '1',
                'name' => 'Anna',
                'replies' => NULL,
                'threads' => NULL,
            ],
            'summary' => [
                'summary_id' => '1',
                'thread_id' => '1',
                'reply_count' => '5',
                'view_count' => '0',
                'thread' => NULL,
            ],
            'replies' => [
                0 => [
                    'reply_id' => '1',
                    'thread_id' => '1',
                    'author_id' => '2',
                    'body' => 'Reply 1 on thread 1',
                    'author' =>
                    [
                        'author_id' => '2',
                        'name' => 'Betty',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                1 => [
                    'reply_id' => '2',
                    'thread_id' => '1',
                    'author_id' => '3',
                    'body' => 'Reply 2 on thread 1',
                    'author' =>
                    [
                        'author_id' => '3',
                        'name' => 'Clara',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                2 => [
                    'reply_id' => '3',
                    'thread_id' => '1',
                    'author_id' => '4',
                    'body' => 'Reply 3 on thread 1',
                    'author' =>
                    [
                        'author_id' => '4',
                        'name' => 'Donna',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                3 => [
                    'reply_id' => '4',
                    'thread_id' => '1',
                    'author_id' => '5',
                    'body' => 'Reply 4 on thread 1',
                    'author' =>
                    [
                        'author_id' => '5',
                        'name' => 'Edna',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                4 => [
                    'reply_id' => '5',
                    'thread_id' => '1',
                    'author_id' => '6',
                    'body' => 'Reply 5 on thread 1',
                    'author' =>
                    [
                        'author_id' => '6',
                        'name' => 'Fiona',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
            ],
            'taggings' => [
                0 => [
                    'tagging_id' => '1',
                    'thread_id' => '1',
                    'tag_id' => '1',
                    'thread' => NULL,
                    'tag' =>
                    [
                        'tag_id' => '1',
                        'name' => 'foo',
                        'taggings' => NULL,
                    ],
                ],
                1 => [
                    'tagging_id' => '2',
                    'thread_id' => '1',
                    'tag_id' => '2',
                    'thread' => NULL,
                    'tag' =>
                    [
                        'tag_id' => '2',
                        'name' => 'bar',
                        'taggings' => NULL,
                    ],
                ],
                2 => [
                    'tagging_id' => '3',
                    'thread_id' => '1',
                    'tag_id' => '3',
                    'thread' => NULL,
                    'tag' =>
                    [
                        'tag_id' => '3',
                        'name' => 'baz',
                        'taggings' => NULL,
                    ],
                ],
            ],
        ],
        1 => [
            'thread_id' => '2',
            'author_id' => '2',
            'subject' => 'Thread subject 2',
            'body' => 'Thread body 2',
            'author' => [
                'author_id' => '2',
                'name' => 'Betty',
                'replies' => NULL,
                'threads' => NULL,
            ],
            'summary' => [
                'summary_id' => '2',
                'thread_id' => '2',
                'reply_count' => '5',
                'view_count' => '0',
                'thread' => NULL,
            ],
            'replies' => [
                0 => [
                    'reply_id' => '6',
                    'thread_id' => '2',
                    'author_id' => '3',
                    'body' => 'Reply 1 on thread 2',
                    'author' =>
                    [
                        'author_id' => '3',
                        'name' => 'Clara',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                1 => [
                    'reply_id' => '7',
                    'thread_id' => '2',
                    'author_id' => '4',
                    'body' => 'Reply 2 on thread 2',
                    'author' =>
                    [
                        'author_id' => '4',
                        'name' => 'Donna',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                2 => [
                    'reply_id' => '8',
                    'thread_id' => '2',
                    'author_id' => '5',
                    'body' => 'Reply 3 on thread 2',
                    'author' =>
                    [
                        'author_id' => '5',
                        'name' => 'Edna',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                3 => [
                    'reply_id' => '9',
                    'thread_id' => '2',
                    'author_id' => '6',
                    'body' => 'Reply 4 on thread 2',
                    'author' =>
                    [
                        'author_id' => '6',
                        'name' => 'Fiona',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                4 => [
                    'reply_id' => '10',
                    'thread_id' => '2',
                    'author_id' => '7',
                    'body' => 'Reply 5 on thread 2',
                    'author' =>
                    [
                        'author_id' => '7',
                        'name' => 'Gina',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
            ],
            'taggings' => [
                0 => [
                    'tagging_id' => '4',
                    'thread_id' => '2',
                    'tag_id' => '2',
                    'thread' => NULL,
                    'tag' =>
                    [
                        'tag_id' => '2',
                        'name' => 'bar',
                        'taggings' => NULL,
                    ],
                ],
                1 => [
                    'tagging_id' => '5',
                    'thread_id' => '2',
                    'tag_id' => '3',
                    'thread' => NULL,
                    'tag' =>
                    [
                        'tag_id' => '3',
                        'name' => 'baz',
                        'taggings' => NULL,
                    ],
                ],
                2 => [
                    'tagging_id' => '6',
                    'thread_id' => '2',
                    'tag_id' => '4',
                    'thread' => NULL,
                    'tag' =>
                    [
                        'tag_id' => '4',
                        'name' => 'dib',
                        'taggings' => NULL,
                    ],
                ],
            ],
        ],
        2 => [
            'thread_id' => '3',
            'author_id' => '3',
            'subject' => 'Thread subject 3',
            'body' => 'Thread body 3',
            'author' => [
                'author_id' => '3',
                'name' => 'Clara',
                'replies' => NULL,
                'threads' => NULL,
            ],
            'summary' => [
                'summary_id' => '3',
                'thread_id' => '3',
                'reply_count' => '5',
                'view_count' => '0',
                'thread' => NULL,
            ],
            'replies' => [
                0 => [
                    'reply_id' => '11',
                    'thread_id' => '3',
                    'author_id' => '4',
                    'body' => 'Reply 1 on thread 3',
                    'author' =>
                    [
                        'author_id' => '4',
                        'name' => 'Donna',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                1 => [
                    'reply_id' => '12',
                    'thread_id' => '3',
                    'author_id' => '5',
                    'body' => 'Reply 2 on thread 3',
                    'author' =>
                    [
                        'author_id' => '5',
                        'name' => 'Edna',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                2 => [
                    'reply_id' => '13',
                    'thread_id' => '3',
                    'author_id' => '6',
                    'body' => 'Reply 3 on thread 3',
                    'author' =>
                    [
                        'author_id' => '6',
                        'name' => 'Fiona',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                3 => [
                    'reply_id' => '14',
                    'thread_id' => '3',
                    'author_id' => '7',
                    'body' => 'Reply 4 on thread 3',
                    'author' =>
                    [
                        'author_id' => '7',
                        'name' => 'Gina',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
                4 => [
                    'reply_id' => '15',
                    'thread_id' => '3',
                    'author_id' => '8',
                    'body' => 'Reply 5 on thread 3',
                    'author' =>
                    [
                        'author_id' => '8',
                        'name' => 'Hanna',
                        'replies' => NULL,
                        'threads' => NULL,
                    ],
                ],
            ],
            'taggings' => [
            ],
        ],
    ];

}
