<?php
namespace Atlas\Mapper\Relationship;

use Atlas\Testing\DataSource\Bidibar\Bidibar;
use Atlas\Testing\DataSource\Bidifoo\Bidifoo;

class OneToOneBidiTest extends RelationshipTest
{
    public function testBidi()
    {
        $bidifooMapper = $this->mapperLocator->get(Bidifoo::CLASS);
        $bidibarMapper = $this->mapperLocator->get(Bidibar::CLASS);

        // create each side of the one-to-one
        $bidifoo = $bidifooMapper->newRecord(['name' => 'foo']);
        $bidibar = $bidibarMapper->newRecord(['name' => 'bar']);

        // set each on the other
        $bidifoo->bidibar = $bidibar;
        $bidibar->bidifoo = $bidifoo;

        // persist the graph
        $bidifooMapper->persist($bidifoo);

        // bidibar will have been inserted
        $row = $bidibar->getRow();
        $this->assertSame($row::INSERTED, $row->getStatus());
        $this->assertEquals(2, $bidibar->bidibar_id);
        $this->assertEquals(1, $bidibar->bidifoo_id);

        // bidifoo will have been updated after insert
        $row = $bidifoo->getRow();
        $this->assertSame($row::UPDATED, $row->getStatus());
        $this->assertEquals(1, $bidifoo->bidifoo_id);
        $this->assertEquals(2, $bidifoo->bidibar_id);

        // test against recursion
        $expect = array (
  'bidifoo_id' => '1',
  'bidibar_id' => '2',
  'name' => 'foo',
  'bidibar' =>
  array (
    'bidibar_id' => '2',
    'bidifoo_id' => '1',
    'name' => 'bar',
    'bidifoo' =>
    array (
      'bidifoo_id' => '1',
      'bidibar_id' => '2',
      'name' => 'foo',
    ),
  ),
);

        $actual = $bidifoo->getArrayCopy();
        $this->assertSame($expect, $actual);
    }
}
