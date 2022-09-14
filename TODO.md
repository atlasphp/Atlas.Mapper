# TODO

## Next

- Make ForeignSimple/Composite "strategies" more like Table IdentityMap classes?

- Add new Bidi classes, with tests.

- Full test coverage.

- Full stan coverage.

Change assertions for PHP 8.1

getArrayCopy() should return ... what? ... for NotLoaded objects? just drop them?
or convert to false, like old times?

Improve resolveMapperClass() UNKNOWN behaviors. Must resolve to a known class,
or to mixed, or to a union of known mapper classes.

## Other

- On insert/update/delete, look at the PDOStatement and set any returned column
  values? Only good for PostgreSQL, but hey, nice touch.

- Add a "required" on one-to-one and many-to-one? Existing 1:1 and M:1 are
  really to-zero-or-one, and "required" is to-exactly-one. Maybe "always".

- ToOne relationships, if not null, should get a new Record if one was not found
  during eager load? (ToMany always get a NotLoaded/RecordSet anyway.) It also
  means that when you create a new Record, all the not-null Related fields for
  it should be populated. (That could snowball pretty quick though.)
