<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Testing\DataSource\Author\AuthorMapper;
use Atlas\Testing\DataSource\Thread\ThreadMapper;

class ManyToOneTest extends RelationshipTest
{
    public function testCustomSettings()
    {
        $rel = new ManyToOne(
            'author',
            $this->mapperLocator,
            ThreadMapper::CLASS,
            AuthorMapper::CLASS,
            ['native' => 'foreign']
        );

        $expect = [
            'name' => 'author',
            'nativeMapperClass' => 'Atlas\\Testing\\DataSource\\Thread\\ThreadMapper',
            'foreignMapperClass' => 'Atlas\\Testing\\DataSource\\Author\\AuthorMapper',
            'foreignTableName' => 'authors',
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
}
