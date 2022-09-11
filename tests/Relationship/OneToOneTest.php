<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Define;
use Atlas\Testing\DataSource\Summary\Summary;
use Atlas\Testing\DataSource\Thread\Thread;

class OneToOneTest extends RelationshipTest
{
    public function testStitchIntoRecords_noNativeRecords()
    {
        $rel = new OneToOne(
            'summary',
            $this->mapperLocator,
            Thread::CLASS,
            Summary::CLASS,
            new Define\OneToOne(),
            new RelationshipLocator(
                $this->mapperLocator,
                Thread::CLASS
            )
        );

        $threads = [];
        $rel->stitchIntoRecords($threads);
        $this->assertSame([], $threads);
    }
}
