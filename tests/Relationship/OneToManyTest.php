<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Define;
use Atlas\Testing\DataSource\Tagging\Tagging;
use Atlas\Testing\DataSource\Thread\Thread;

class OneToManyTest extends RelationshipTest
{
    public function testStitchIntoRecords_noNativeRecords()
    {
        $rel = $this
            ->mapperLocator
            ->get(Thread::CLASS)
            ->getRelationshipLocator()
            ->get('taggings');

        $threads = [];
        $rel->stitchIntoRecords($threads);
        $this->assertEmpty($threads);
    }
}
