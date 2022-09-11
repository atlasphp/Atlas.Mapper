<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Define;
use Atlas\Testing\DataSource\Tagging\Tagging;
use Atlas\Testing\DataSource\Thread\Thread;

class ManyToOneTest extends RelationshipTest
{
    public function testStitchIntoRecords_noNativeRecords()
    {
        $rel = $this
            ->mapperLocator
            ->get(Tagging::CLASS)
            ->getRelationshipLocator()
            ->get('thread');

        $taggings = [];
        $rel->stitchIntoRecords($taggings);
        $this->assertEmpty($taggings);
    }
}
