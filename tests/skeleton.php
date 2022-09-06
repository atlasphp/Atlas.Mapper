<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use Atlas\Mapper\CompositeDataSourceFixture;
use Atlas\Mapper\DataSourceFixture;
use Atlas\Pdo\Connection;
use Atlas\Skeleton\Config;
use Atlas\Skeleton\Fsio;
use Atlas\Skeleton\Logger;
use Atlas\Skeleton\Skeleton;

$connection = Connection::new('sqlite::memory:');
$dir = __DIR__ . '/DataSource';
$fixture = new DataSourceFixture($connection);
$fixture->exec();
@mkdir($dir, 0777, true);
$skeleton = new Skeleton(
    new Config([
        'pdo' => [$connection->getPdo()],
        'directory' => $dir,
        'namespace' => 'Atlas\\Mapper\\DataSource',
        'transform' => null,
        'templates' => null,
    ]),
    new Fsio(),
    new Logger(),
);
$skeleton();

$connection = Connection::new('sqlite::memory:');
$dir = __DIR__ . '/CompositeDataSource';
$fixture = new CompositeDataSourceFixture($connection);
$fixture->exec();
@mkdir($dir, 0777, true);
$skeleton = new Skeleton(
    new Config([
        'pdo' => [$connection->getPdo()],
        'directory' => $dir,
        'namespace' => 'Atlas\\Mapper\\CompositeDataSource',
        'transform' => null,
        'templates' => null,
    ]),
    new Fsio(),
    new Logger(),
);
$skeleton();
