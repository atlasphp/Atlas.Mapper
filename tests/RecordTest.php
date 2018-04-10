<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRecord;
use Atlas\Mapper\Fake\FakeRow;

class RecordTest extends \PHPUnit\Framework\TestCase
{
    protected $row;
    protected $related;
    protected $record;
    protected $zim;
    protected $irk;

    protected function setUp()
    {
        $this->zim = $this->getMockBuilder(Record::CLASS)->disableOriginalConstructor()->getMock();
        $this->irk = $this->getMockBuilder(RecordSet::CLASS)->disableOriginalConstructor()->getMock();

        $this->row = new FakeRow([
            'id' => '1',
            'foo' => 'bar',
            'baz' => 'dib',
        ]);

        $this->related = new Related([
            'zim' => $this->zim,
            'irk' => $this->irk,
        ]);

        $this->record = new FakeRecord($this->row, $this->related);
    }

    public function testGetRow()
    {
        $this->assertSame($this->row, $this->record->getRow());
    }

    public function testGetRelated()
    {
        $this->assertSame($this->related, $this->record->getRelated());
    }

    public function test__get()
    {
        // row
        $this->assertSame('bar', $this->record->foo);

        // related
        $this->assertSame($this->zim, $this->record->zim);

        // missing
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            'Atlas\Mapper\Fake\FakeRecord::$noSuchField does not exist'
        );
        $this->record->noSuchField;
    }

    public function test__set()
    {
        // row
        $this->record->foo = 'barbar';
        $this->assertSame('barbar', $this->record->foo);
        $this->assertSame('barbar', $this->row->foo);

        // related
        $newZim = $this->getMockBuilder(Record::CLASS)->disableOriginalConstructor()->getMock();
        $this->record->zim = $newZim;
        $this->assertSame($newZim, $this->record->zim);

        // missing
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            'Atlas\Mapper\Fake\FakeRecord::$noSuchField does not exist'
        );
        $this->record->noSuchField = 'missing';
    }

    public function test__isset()
    {
        // row
        $this->assertTrue(isset($this->record->foo));

        // related
        $this->assertTrue(isset($this->record->zim));

        // missing
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            'Atlas\Mapper\Fake\FakeRecord::$noSuchField does not exist'
        );
        isset($this->record->noSuchField);
    }

    public function test__unset()
    {
        // row
        unset($this->record->foo);
        $this->assertNull($this->record->foo);
        $this->assertNull($this->row->foo);

        // related
        unset($this->record->zim);
        $this->assertNull($this->record->zim);

        // missing
        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage(
            'Atlas\Mapper\Fake\FakeRecord::$noSuchField does not exist'
        );
        unset($this->record->noSuchField);
    }

    public function testHas()
    {
        // row
        $this->assertTrue($this->record->has('foo'));

        // related
        $this->assertTrue($this->record->has('zim'));

        // missing
        $this->assertFalse($this->record->has('noSuchField'));
    }

    public function testSet()
    {
        $newZim = $this->getMockBuilder(Record::CLASS)->disableOriginalConstructor()->getMock();
        $newZim->method('getArrayCopy')->willReturn(['zimkey' => 'zimval']);

        $newIrk = $this->getMockBuilder(RecordSet::CLASS)->disableOriginalConstructor()->getMock();
        $newIrk->method('getArrayCopy')->willReturn([['doomkey' => 'doomval']]);

        $this->record->set([
            'foo' => 'hello',
            'zim' => $newZim,
            'irk' => $newIrk,
        ]);

        $actual = $this->record->getArrayCopy();
        $expected = [
            'id' => '1',
            'foo' => 'hello',
            'baz' => 'dib',
            'zim' => ['zimkey' => 'zimval'],
            'irk' => [
                ['doomkey' => 'doomval']
            ],
        ];
        $this->assertSame($expected, $actual);
    }

    public function testJsonSerialize()
    {
        $actual = json_encode($this->record);
        $expect = '{"id":"1","foo":"bar","baz":"dib","zim":[],"irk":[]}';
        $this->assertSame($expect, $actual);
    }
}
