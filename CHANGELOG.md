# CHANGELOG

## 1.3.1

- Fixed #10 re: calling fetchRecords on a set where all records are
  identity-mapped

- Fixed #11 re: unreachable code

- Fixed #13 re: performance optimisations on stitchIntoRecord by removing
  foreignRecord entries in stitchIntoRecord once they've been matched, where
  possible

## 1.3.0

This release (re-)introduces many-to-many as a first-class relationship, along
with automatic management of the intercessory association mapping ("through")
related field. It includes `joinWith()` support for many-to-many as well.

## 1.2.0

The release introduces automatic quoting of foreign table, alias, and column
name identifiers in relationships. (N.b.: Atlas.Table handles the quoting of
native identifiers.)

## 1.1.0

Added method MapperLocator::getTableLocator().

Updated tests.

## 1.0.2

Another small performance improvement to MapperRelationships::getRelated().

## 1.0.1

This release significantly improves the performance of
MapperRelationships::newRelated(), and thereby indirectly the performance of
Mapper::newRecord(). It also incorporates a benchmarking script at
`tests/bench.php`.

## 1.0.0

Remove relationship defintion method `unsetDeleted()` as premature, and update
tests.

## 1.0.0-beta3

In various Mapper methods, use the new Table::selectRow() and selectRows()
methods combined with a MapperSelect instance. This fixes a bug where those
methods failed to invoke MapperEvents::modifySelect().

Automatic unsetting of related Records and RecordSets after deletion is now a
configurable relationship behavior via the `unsetDeleted()` method.

Added testing for DeletableRelationships (cascades).

## 1.0.0-beta2

This release incorporates a BC break to the signature for
MapperSelect::joinWith(), in support of additional functionality; to wit,
joining to sub-relateds.

Previously, to join to a defined relationship, you would specify the join type
and the relationship name:

    $select->joinWith('LEFT', 'foo');

This allowed only one "level" of join; that is, if you want to then join to the
"bar" relationship defined in "foo", you were out of luck. Further, you could
not specify an alias.

With the BC break, you now do this:

    $select->joinWith('LEFT foo');

(If you don't specify a join type, it defaults to "JOIN".)

You can specify an alias using AS:

    $select->joinWith('LEFT foo AS foo_alias');

Finally, you can pass a callable `function ($sub)` as an optional param to do a
sub-`joinWith()` on the related, allowing you to nest joins to foreign
relationships:

    $select->joinWith('foo', function ($sub) {
        $sub->joinWith('bar', function ($sub) {
            $sub->joinWith('baz');
        });
    });

This release also has these changes:

- Exception::rowAlreadyMapped() now receives the Row object as a param; this is
  in anticipation of giving more information about the mapped row.

- Some method parameter names have been changed for better IDE hinting.

- Added a PHPStorm metadata resource.


## 1.0.0-beta1

This release has a significant change to class names, most notably that
generated mapper classes are no longer suffixed with _Mapper_. This is in
support of easier/better automatic return typehint completion for IDEs. If you
generated classes with the previous alpha versions of Atlas.Cli, you will need
to re-generate them, and then update mapper class references from _<Type>Mapper_
to just _<Type>_.

This release also adds:

- Recursion control on `getArrayCopy()`

- A new relationship, _OneToOneBidi_

- Type-specific _MapperSelect_ classes, to support IDE completion of return
  typehints

- The missing `DeletableRelationship::CASCADE` constant.

## 1.0.0-alpha1

Initial release.

