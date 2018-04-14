- Figure how to add relationships to {FOO}Fields

    - Parse MapperRelationships ?

- Figure how to type-check relateds at setting time

    - Look at MapperRelationships statics?

- Go back and put quoting of tables/cols into the Table and Mapper? Only when
  cols & tables are known for a fact, not by regexing.

- On insert/update/delete, look at the PDOStatement and set any returned column
  values (only good for PostgreSQL, but hey, nice touch.)

- On insert/update/delete, look to see if original record changed as a result of
  the events, and update it again if needed. (E.g. bidirectional one-to-one.)
  Do not trigger MapperEvents, though I don't see how to avoid triggering
  TableEvents. (Perhaps a new method, Table::updateRowWithoutEvents().)

- Add a "required" on one-to-one  and many-to-one? Existing 1:1 and M:1 are
  really to-zero-or-one, and "required" is to-exactly-one. Maybe "always".
