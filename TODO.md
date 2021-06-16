- On insert/update/delete, look at the PDOStatement and set any returned column
  values (only good for PostgreSQL, but hey, nice touch.)

- Add a "required" on one-to-one and many-to-one? Existing 1:1 and M:1 are
  really to-zero-or-one, and "required" is to-exactly-one. Maybe "always".
  Or make the typehint the key: if ?Record, 0-or-1:1; if Record, exactly-1:1.

- given SingleTableInheritance, should STI Records get their own Relateds?

* * *

'UNKNOWN' on Relationships getType()

OnDeleteInitDeleted nees to be renamed.

How to use constants (or something else) for OnDelete?

Reorganize all the Related/Relationship classes.

* * *
Many-To-One, Unidirectional

One-To-One, Unidirectional
One-To-One, Bidirectional (foo.foo_id => bar.foo_id, foo.bar_id => bar.bar_id)
One-To-One, Self-referencing

One-To-Many, Bidirectional (foo.foo_id => bar.foo_id, )
One-To-Many, Unidirectional with Join Table
One-To-Many, Self-referencing

Many-To-Many, Unidirectional
Many-To-Many, Bidirectional
Many-To-Many, Self-referencing
