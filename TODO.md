- Test that fetch/yield actually work through MapperSelect

- Add @methods to MapperSelect docblock

- Figure how to add relationships to {FOO}Fields

    - Parse MapperRelationships ?

- Figure how to type-check relateds at setting time

    - Look at MapperRelationships statics?

- Extract relationship simple/composite foreign key strategies

    - Would need to do this after initialize(), since it depends on $on array

    - Force defining of $on, instead of auto-determining it at initialize() time?

- Go back and put quoting of tables/cols into the Table and Mapper? Only when
  cols & tables are known for a fact, not by regexing.
