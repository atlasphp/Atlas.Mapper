# CHANGELOG

No releases yet.

## 2.x

- Absolute minimal upgrade for Atlas.Table 2.x et al.

    - Copy Atlas.Testing to local testing directory

    - Minimum PHP 8.0

    - rename `MapperSelect::with()` to `MapperSelect::loadRelated()` to allow for CTEs in queries

    - rename `MapperSelect::joinWith()` to `MapperSelect::joinRelated()` for symmetry

    - rename `SubJoinWith` to `SubJoinRelated`

