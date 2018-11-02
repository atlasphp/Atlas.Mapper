# Getting Started

## Installation

This package is installable and autoloadable via [Composer](https://getcomposer.org/)
as [atlas/mapper](https://packagist.org/packages/atlas/orm). Add the following lines
to your `composer.json` file, then call `composer update`.

```json
{
    "require": {
        "atlas/mapper": "~1.0"
    },
    "require-dev": {
        "atlas/cli": "~2.0"
    }
}
```

(The `atlas/cli` package provides the `atlas-skeleton` command-line tool to
help create data-source classes for the mapper system.)

> **Note:**
>
> If you are using PHPStorm, you may wish to copy the IDE meta file to your
> project to get full autocompletion on Atlas classes:
>
> ```
> cp ./vendor/atlas/orm/resources/phpstorm.meta.php ./.phpstorm.meta.php
> ```

## Skeleton Generation

Next, you will need to create the prerequsite data-source classes using
[Atlas.Cli 2.x](/cassini/skeleton/usage.html).

## Instantiating Atlas

Now you can create an _MapperLocator_ instance by using its static `new()`
method and passing your PDO connection parameters:

```php
use Atlas\Mapper\MapperLocator;

$atlas = MapperLocator::new(
    'mysql:host=localhost;dbname=testdb',
    'username',
    'password'
);
```

## Next Steps

Now you can use the _MapperLocator_ to work with your database to fetch and
persist _Record_ objects, as well as perform other interactions.

- [Define relationships between mappers](./relationships.md)

- [Fetch Records and RecordSets](./reading.md)

- Work with [Records](./records.md) and [RecordSets](./record-sets.md)

- [Add Record and RecordSet behaviors](./behavior.md)

- [Handle events](./events.md)

- [Perform direct lower-level queries](./direct.md)

- [Other topics](./other.md) such as custom mapper methods, single table
  inheritance, and automated validation
