<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Testing\DataSource\Summary\SummaryMapper;
use Atlas\Testing\DataSource\Thread\ThreadMapper;

class OneToOneTest extends RelationshipTest
{
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
}
