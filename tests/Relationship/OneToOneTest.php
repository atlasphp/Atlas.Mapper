<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Define;
use Atlas\Testing\DataSource\Summary\Summary;
use Atlas\Testing\DataSource\Thread\Thread;

class OneToOneTest extends RelationshipTest
{
    public function testStitchIntoRecords_noNativeRecords()
    {
        $rel = $this
            ->mapperLocator
            ->get(Thread::class)
            ->getRelationshipLocator()
            ->get('summary');

        $threads = [];
        $rel->stitchIntoRecords($threads);
        $this->assertSame([], $threads);
    }
}
