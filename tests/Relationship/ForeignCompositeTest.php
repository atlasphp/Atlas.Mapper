<?php declare(strict_types=1);

namespace Atlas\Mapper\Relationship;

use Atlas\Mapper\MapperSelect;
use Atlas\Mapper\Record;
use Atlas\Mapper\Related;
use Atlas\Pdo\Connection;
use Atlas\Query\Bind;
use Atlas\Table\Row;
use PHPUnit\Framework\TestCase;

final class ForeignCompositeTest extends TestCase
{
	public function test()
	{
		$foreignStrategy = new ForeignComposite('test', [
			'native1' => 'foreign1',
			'native2' => 'foreign2',
		]);

		$select = new class extends MapperSelect {
			public function __construct()
			{
				parent::__construct(Connection::new('sqlite::memory:'), new Bind());
			}
		};
		$foreignStrategy->modifySelect($select, [
			new class extends Record {
				public function __construct()
				{
					parent::__construct(
						new class([
							'id' => 1,
							'native1' => 'n1',
							'native2' => 'n2',
						]) extends Row {
							protected $cols = [
								'id' => null,
								'native1' => null,
								'native2' => null,
							];
						}, new Related([]));
				}
			}
		]);

		$this->assertSame('SELECT
    
WHERE
    ( -- composite keys
    ("test"."foreign1" = :__1__ AND "test"."foreign2" = :__2__)
)', $select->getStatement());
		$this->assertSame([
			'__1__' => [
				0 => 'n1',
				1 => 2,
			],
			'__2__' => [
				0 => 'n2',
				1 => 2,
			],
		], $select->getBindValues());
	}
}