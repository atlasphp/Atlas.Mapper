# TODO

## Next

- Generate table classes under new skeleton, update Mapper 2.x to handle it.
  Something to run *after* you regenerate the classes in 2.x.

- Converter for 3.x to 4.x, esp. Relationship => Related.

- Convert from `throw Exception::method()` to `throw new Exception\Name(...)`

- Better Exception messages. Include e.g. the class it came from.

- ForeignSimple/Composite more like Table IdentityMap classes.

## Other

- On insert/update/delete, look at the PDOStatement and set any returned column
  values? Only good for PostgreSQL, but hey, nice touch.

- Add a "required" on one-to-one and many-to-one? Existing 1:1 and M:1 are
  really to-zero-or-one, and "required" is to-exactly-one. Maybe "always".
