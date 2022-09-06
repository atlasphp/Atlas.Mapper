<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use Atlas\Mapper\CompositeDataSourceFixture;
use Atlas\Mapper\DataSourceFixture;
use Atlas\Pdo\Connection;
use Atlas\Skeleton\Config;
use Atlas\Skeleton\Fsio;
use Atlas\Skeleton\Logger;
use Atlas\Skeleton\Upgrade;

$connection = Connection::new('sqlite::memory:');
$dir = __DIR__ . '/DataSource';
$fixture = new DataSourceFixture($connection);
$fixture->exec();
@mkdir($dir, 0777, true);
$upgrade = new Upgrade(
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
$upgrade();

$connection = Connection::new('sqlite::memory:');
$dir = __DIR__ . '/CompositeDataSource';
$fixture = new CompositeDataSourceFixture($connection);
$fixture->exec();
@mkdir($dir, 0777, true);
$upgrade = new Upgrade(
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
$upgrade();

echo "Remember to replace Atlas\Testing with Atlas\Mapper" . PHP_EOL;
