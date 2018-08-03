<?php
namespace Atlas\Mapper\Fake;

use Atlas\Table\Row;

class FakeRow extends Row
{
    protected $cols = [
        'id' => null,
        'foo' => null,
        'baz' => null,
    ];
}
