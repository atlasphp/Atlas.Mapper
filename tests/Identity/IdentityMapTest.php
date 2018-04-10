<?php
namespace Atlas\Mapper\Identity;

use Atlas\Mapper\Exception;
use Atlas\Mapper\Fake\FakeRow;

class IdentityMapTest extends \PHPUnit\Framework\TestCase
{
    public function testSetRow_alreadySet()
    {
        $identityMap = new IdentitySimple('id');
        $row = new FakeRow(['id' => 1]);

        $identityMap->setRow($row);

        $this->expectException(Exception::CLASS);
        $this->expectExceptionMessage('Row already exists in IdentityMap.');
        $identityMap->setRow($row);
    }
}
