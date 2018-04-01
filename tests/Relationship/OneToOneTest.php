<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Testing\DataSource\Summary\SummaryMapper;
use Atlas\Testing\DataSource\Thread\ThreadMapper;

class OneToOneTest extends RelationshipTest
{
    public function testCustomSettings()
    {
        $rel = new OneToOne(
            'summary',
            $this->mapperLocator,
            ThreadMapper::CLASS,
            SummaryMapper::CLASS,
            ['native' => 'foreign']
        );

        $expect = [
            'name' => 'summary',
            'nativeMapperClass' => 'Atlas\\Testing\\DataSource\\Thread\\ThreadMapper',
            'foreignMapperClass' => 'Atlas\\Testing\\DataSource\\Summary\\SummaryMapper',
            'foreignTableName' => 'summaries',
            'on' => ['native' => 'foreign'],
            'ignoreCase' => false,
            'where' => [],
        ];

        $actual = $rel->getSettings();
        $this->assertSame($expect, $actual);

        // get them again, make sure they stay fixed
        $actual = $rel->getSettings();
        $this->assertSame($expect, $actual);
    }

    public function testStitchIntoRecords_noNativeRecords()
    {
        $rel = new OneToOne(
            'summary',
            $this->mapperLocator,
            ThreadMapper::CLASS,
            SummaryMapper::CLASS
        );

        $threads = [];
        $rel->stitchIntoRecords($threads);
        $this->assertSame([], $threads);
    }

    public function testGetForeignMapper()
    {
        $rel = new OneToOne(
            'summary',
            $this->mapperLocator,
            ThreadMapper::CLASS,
            SummaryMapper::CLASS
        );

        $foreignMapper = $rel->getForeignMapper();
        $this->assertInstanceOf(SummaryMapper::CLASS, $foreignMapper);
    }
}
