<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\Define;
use Atlas\Mapper\Exception;
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

    public function testUnexpectedRelatedTypehint()
    {
        $this->expectException(Exception\UnexpectedRelatedTypehint::CLASS);
        $this->expectExceptionMessage("FakeNativeMapperRelated::\$fake expected a typehint of Record, ?Record, or RecordSet; got 'mixed' or a union of types instead.");
        $rel = new OneToOne(
            'fake',
            $this->mapperLocator,
            'FakeNativeMapper',
            'mixed',
            new Define\OneToOne(),
        );
    }
}
