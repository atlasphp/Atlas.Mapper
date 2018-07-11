<?php
require dirname(__DIR__) . '/vendor/atlas/table/tests/bench.php';

use Atlas\Mapper\MapperLocator;
use Atlas\Testing\DataSource\Thread\Thread;

$mapperLocator = MapperLocator::new($connection);
$threadMapper = $mapperLocator->get(Thread::CLASS);

bench('ThreadMapper::newRecord()', function () use ($threadMapper) {
    $threadMapper->newRecord();
});
