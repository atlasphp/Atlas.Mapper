# CHANGELOG

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
