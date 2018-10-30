- What else needs to be done on ManyToMany?

- Figure how to type-check relateds at setting time

    - Look at MapperRelationships statics?

- On insert/update/delete, look at the PDOStatement and set any returned column
  values (only good for PostgreSQL, but hey, nice touch.)

- Add a "required" on one-to-one and many-to-one? Existing 1:1 and M:1 are
  really to-zero-or-one, and "required" is to-exactly-one. Maybe "always".
