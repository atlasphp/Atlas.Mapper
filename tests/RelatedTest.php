<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRelated;
use Atlas\Mapper\Relationship\NotLoaded;

class RelatedTest extends \PHPUnit\Framework\TestCase
{
    protected $zim;
    protected $irk;
    protected $related;

    protected function setUp() : void
    {
        $this->zim = $this->getMockBuilder(Record::CLASS)->disableOriginalConstructor()->getMock();
        $this->irk = $this->getMockBuilder(RecordSet::CLASS)->disableOriginalConstructor()->getMock();
        $this->related = new FakeRelated([
            'zim' => $this->zim,
            'irk' => $this->irk,
        ]);
    }

    public function test__get()
    {
        // related
        $this->assertSame($this->zim, $this->related->zim);

        // missing
        $this->expectException(
            Exception\PropertyDoesNotExist::CLASS,
            'Atlas\Mapper\Related::$noSuchForeign does not exist'
        );
        $this->related->noSuchForeign;
    }

    public function test__set()
    {
        $newZim = $this->getMockBuilder(Record::CLASS)->disableOriginalConstructor()->getMock();

        // related
        $this->related->zim = $newZim;
        $this->assertSame($newZim, $this->related->zim);

        // missing
        $this->expectException(
            Exception\PropertyDoesNotExist::CLASS,
            'Atlas\Mapper\Related::$noSuchForeign does not exist'
        );
        $this->related->noSuchForeign = 'missing';
    }

    public function test__isset()
    {
        // related
        $this->assertTrue(isset($this->related->zim));

        // missing
        $this->expectException(
            Exception\PropertyDoesNotExist::CLASS,
            'Atlas\Mapper\Related::$noSuchForeign does not exist'
        );
        isset($this->related->noSuchForeign);
    }

    public function test__unset()
    {
        // related
        unset($this->related->zim);
        $this->assertInstanceOf(NotLoaded::CLASS, $this->related->zim);

        // missing
        $this->expectException(
            Exception\PropertyDoesNotExist::CLASS,
            'Atlas\Mapper\Related::$noSuchForeign does not exist'
        );
        unset($this->related->noSuchForeign);
    }

    public function testHas()
    {
        // related
        $this->assertTrue($this->related->has('zim'));

        // missing
        $this->assertFalse($this->related->has('noSuchForeign'));
    }

    public function testGetFields()
    {
        $expect = ['zim' => $this->zim, 'irk' => $this->irk];
        $actual = $this->related->getFields();
        $this->assertSame($expect, $actual);
    }

    public function testGetArrayCopy()
    {
        $expect = [
            'zim' => [],
            'irk' => [],
        ];
        $actual = $this->related->getArrayCopy();
        $this->assertSame($expect, $actual);
    }
}
