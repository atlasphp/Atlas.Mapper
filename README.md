# Atlas.Mapper

A data mapper implementation for Atlas. Though it is primarily intended as the
heart of [Atlas.Orm][], it may be used independently of that package.

## Getting Started

First, you will need to create the prerequsite data-source classes using
[Atlas.Cli 2.x][].

Once you have done so, create a _MapperLocator_ using the static `new()` method
and pass your PDO connection parameters:

```php
use Atlas\Table\MapperLocator;

$mapperLocator = MapperLocator::new('sqlite::memory:'')
```

You can then use the locator to retrieve a _Mapper_ by its class name.

```php
use Atlas\Testing\DataSource\Thread\ThreadMapper;

$threadMapper = $mapperLocator->get(ThreadMapper::CLASS)
```

From there you can fetch, insert, update, delete, and persist _Record_ objects.

In the absence of full documentation, please review these _Mapper_ methods
instead:

- fetchRecord()
- fetchRecordBy()
- fetchRecords()
- fetchRecordsBy()
- fetchRecordSet()
- fetchRecordSetBy()
- select()
- insert()
- update()
- delete()
- persist()

[Atlas.Cli 2.x]: https://github.com/atlasphp/Atlas.Cli
[Atlas.Orm]: https://github.com/atlasphp/Atlas.Orm
