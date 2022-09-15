# TODO

## Next

- Full test coverage.

- Full stan coverage.

- Make ForeignSimple/Composite "strategies" more like Table IdentityMap classes?
  Or at least rename to `foreignSelectModifier` (or something like that).

- getArrayCopy() should return ... what? ... for NotLoaded objects? just drop them?
or convert to false, like old times?

## Other

- Add new Bidi classes, with tests.

- On insert/update/delete, look at the PDOStatement and set any returned column
  values? Only good for PostgreSQL, but hey, nice touch.

- Add a "required" on one-to-one and many-to-one? Existing 1:1 and M:1 are
  really to-zero-or-one, and "required" is to-exactly-one. Maybe "always".

- ToOne relationships, if not null, should get a new Record if one was not found
  during eager load? (ToMany always get a NotLoaded/RecordSet anyway.) It also
  means that when you create a new Record, all the not-null Related fields for
  it should be populated. (That could snowball pretty quick though.)
