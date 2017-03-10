# Changelog

All notable changes to `laravel-ownership` will be documented in this file.

## [2.2.0] - 2017-02-07

### Added

- `owner()` alias for method `ownedBy`
- Laravel 5.4 support

## [2.1.0] - 2016-12-21

### Added

- `isNotOwnedBy($owner)` to check if model not owned by concrete owner.

## [2.0.0] - 2016-12-17

### Added

- `withDefaultOwner()` set default owner value on create.
- `withDefaultOwner($owner)` overwrite default owner value on create with concrete owner.
- `withoutDefaultOwner()` don't set default owner on model create.
- `scopeWhereNotOwnedBy($owner)` scope results to exclude unowned records by owner.

### Changed

- Renamed method `getDefaultOwner()` to `resolveDefaultOwner()`.
- Renamed flag attribute `$setDefaultOwnerOnCreate` to `$withDefaultOwnerOnCreate`.

### Fixed

- Set default owner on model creation.

## [1.0.0] - 2016-12-15

- Initial release

[2.2.0]: https://github.com/cybercog/laravel-ownership/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/cybercog/laravel-ownership/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/cybercog/laravel-ownership/compare/1.0.0...2.0.0
