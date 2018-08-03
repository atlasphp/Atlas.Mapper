<?php
namespace Atlas\Mapper;

use Atlas\Mapper\Fake\FakeRecord;
use Atlas\Mapper\Fake\FakeRecordSet;
use Atlas\Mapper\Fake\FakeRow;
use stdClass;

class RecordSetTest extends \PHPUnit\Framework\TestCase
{
    protected $row;
    protected $related;
    protected $record;
    protected $recordSet;

    protected function setUp()
    {
        $this->row = new FakeRow([
            'id' => '1',
            'foo' => 'bar',
            'baz' => 'dib',
        ]);
        $this->row->init($this->row::SELECTED);

        $this->related = new Related([
            'zim' => $this->getMockBuilder(Record::CLASS)->disableOriginalConstructor()->getMock(),
            'irk' => $this->getMockBuilder(RecordSet::CLASS)->disableOriginalConstructor()->getMock(),
        ]);

        $this->record = new FakeRecord($this->row, $this->related);

        $newRecord = function ($cols = []) {
            $row = new FakeRow($cols);
            $related = new Related(['zim' => null, 'irk' => null]);
            return new FakeRecord($row, $related);
        };

        $this->recordSet = new FakeRecordSet([$this->record], $newRecord);
    }

    public function testOffsetExists()
    {
        $this->assertTrue(isset($this->recordSet[0]));
        $this->assertFalse(isset($this->recordSet[1]));
    }

    public function testOffsetSet_append()
    {
        $this->assertCount(1, $this->recordSet);
        $this->recordSet[] = clone($this->record);
        $this->assertCount(2, $this->recordSet);
    }

    public function testOffsetSet_nonObject()
    {
        $this->expectException(Exception::CLASS);
        $this->recordSet[] = 'Foo';
    }

    public function testOffsetSet_nonRecordObject()
    {
        $this->expectException(Exception::CLASS);
        $this->recordSet[] = new StdClass();
    }

    public function testOffsetUnset()
    {
        $this->assertFalse($this->recordSet->isEmpty());
        $this->assertTrue(isset($this->recordSet[0]));
        unset($this->recordSet[0]);
        $this->assertFalse(isset($this->recordSet[0]));
        $this->assertTrue($this->recordSet->isEmpty());
    }

    public function testAppendNew()
    {
        $record = $this->recordSet->appendNew([
            'id' => null,
            'foo' => 'newfoo'
        ]);

        $this->assertCount(2, $this->recordSet);
        $this->assertSame($record, $this->recordSet[1]);
    }

    public function testGetAndDetach()
    {
        $this->recordSet->appendNew(['id' => 2, 'foo' => 'bar1']);
        $this->recordSet->appendNew(['id' => 3, 'foo' => 'bar2']);
        $this->recordSet->appendNew(['id' => 4, 'foo' => 'bar3']);
        $this->recordSet->appendNew(['id' => 5, 'foo' => 'bar1']);
        $this->recordSet->appendNew(['id' => 6, 'foo' => 'bar2']);
        $this->recordSet->appendNew(['id' => 7, 'foo' => 'bar3']);
        $this->recordSet->appendNew(['id' => 8, 'foo' => 'bar1']);
        $this->recordSet->appendNew(['id' => 9, 'foo' => 'bar2']);
        $this->recordSet->appendNew(['id' => 10, 'foo' => 'bar3']);

        $actual = $this->recordSet->getOneBy(['foo' => 'no-such-value']);
        $this->assertNull($actual);

        $actual = $this->recordSet->getOneBy(['foo' => 'bar1']);
        $this->assertSame(2, $actual->id);

        $actual = $this->recordSet->getAllBy(['foo' => 'bar2']);
        $this->assertCount(3, $actual);
        $this->assertSame(3, $actual[2]->id);
        $this->assertSame(6, $actual[5]->id);
        $this->assertSame(9, $actual[8]->id);

        $this->assertCount(10, $this->recordSet);

        $actual = $this->recordSet->detachOneBy(['foo' => 'no-such-value']);
        $this->assertNull($actual);

        $actual = $this->recordSet->detachOneBy(['foo' => 'bar1']);
        $this->assertSame(2, $actual->id);
        $this->assertCount(9, $this->recordSet);
        $this->assertFalse(isset($this->recordSet[1]));

        $actual = $this->recordSet->detachAllBy(['foo' => 'bar2']);
        $this->assertCount(6, $this->recordSet);
        $this->assertSame(3, $actual[2]->id);
        $this->assertSame(6, $actual[5]->id);
        $this->assertSame(9, $actual[8]->id);

        $actual = $this->recordSet->detachAll();
        $this->assertCount(0, $this->recordSet);
        $this->assertCount(6, $actual);
    }

    public function testJsonSerialize()
    {
        $this->recordSet->appendNew(['id' => 2, 'foo' => 'bar1']);
        $this->recordSet->appendNew(['id' => 3, 'foo' => 'bar2']);
        $expect = '['
            . '{"id":"1","foo":"bar","baz":"dib","zim":[],"irk":[]},'
            . '{"id":2,"foo":"bar1","baz":null,"zim":null,"irk":null},'
            . '{"id":3,"foo":"bar2","baz":null,"zim":null,"irk":null}'
            . ']';
        $actual = json_encode($this->recordSet);
        $this->assertSame($expect, $actual);
    }

    public function testSetDelete()
    {
        foreach ($this->recordSet as $record) {
            $this->assertFalse($record->getAction() == FakeRow::DELETE);
        }

        $this->recordSet->setDelete();

        foreach ($this->recordSet as $record) {
            $this->assertTrue($record->getAction() == FakeRow::DELETE);
        }
    }

    public function testDetachDeleted()
    {
        $this->recordSet->appendNew(['id' => 2, 'foo' => 'bar1']);
        $this->recordSet->appendNew(['id' => 3, 'foo' => 'bar2']);
        $this->recordSet->appendNew(['id' => 4, 'foo' => 'bar3']);
        $this->recordSet->appendNew(['id' => 5, 'foo' => 'bar1']);
        $this->recordSet->appendNew(['id' => 6, 'foo' => 'bar2']);
        $this->recordSet->appendNew(['id' => 7, 'foo' => 'bar3']);
        $this->recordSet->appendNew(['id' => 8, 'foo' => 'bar1']);
        $this->recordSet->appendNew(['id' => 9, 'foo' => 'bar2']);
        $this->recordSet->appendNew(['id' => 10, 'foo' => 'bar3']);

        $this->assertCount(10, $this->recordSet);

        foreach ($this->recordSet as $i => $record) {
            $this->assertFalse($record->getAction() == FakeRow::DELETE);
            if ($i % 2) {
                $row = $record->getRow();
                $row->init($row::DELETED);
            }
        }

        $detached = $this->recordSet->detachDeleted();
        $this->assertCount(5, $this->recordSet);
        $this->assertCount(5, $detached);
        $this->assertInstanceOf(RecordSet::CLASS, $detached);
    }
}
