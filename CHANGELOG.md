# Changelog

All notable changes to `cybercog/laravel-ownership` will be documented in this file.

## [Unreleased]

## [5.6.0] - 2024-03-09

### Added

- ([#28]) Added Laravel 11 support

## [5.5.0] - 2023-02-24

### Added

- ([#28]) Added Laravel 10 support

## [5.4.0] - 2023-02-22

### Added

- ([#25]) Added Laravel 9 support

### Removed

- ([#27]) Dropped PHP 7.x support
- ([#27]) Dropped Laravel 5.2 support
- ([#27]) Dropped Laravel 5.3 support
- ([#27]) Dropped Laravel 5.4 support
- ([#27]) Dropped Laravel 5.5 support
- ([#27]) Dropped Laravel 5.6 support
- ([#27]) Dropped Laravel 5.7 support
- ([#27]) Dropped Laravel 5.8 support
- ([#27]) Dropped Laravel 6 support
- ([#27]) Dropped Laravel 7 support
- ([#27]) Dropped Laravel 8 support

## [5.3.0] - 2020-12-31

### Added

- ([#22]) Added Laravel 6.x support
- ([#22]) Added Laravel 7.x support
- ([#22]) Added Laravel 8.x support
- ([#23]) Added PHP 8.x support

### Removed

- ([#23]) Dropped PHP 5.6 support
- ([#23]) Dropped PHP 7.0 support

## [5.2.0] - 2018-09-09

### Added

- ([#19]) Laravel 5.7 support

## [5.1.0] - 2018-02-08

### Added

- Laravel 5.6 support

## [5.0.0] - 2017-09-13

### Changed

- Contracts namespace changed from `Cog\Contracts\Laravel\Ownership` to `Cog\Contracts\Ownership`

### Fixed

- Service Provider auto-discovery

## [4.0.0] - 2017-09-09

### Added

- Ownable models got new `isOwnedByDefaultOwner` method which automatically try to resolve current user.

### Changed

- Contracts namespace changed from `Cog\Ownership\Contracts` to `Cog\Contracts\Laravel\Ownership`
- Classes namespace changed from `Cog\Ownership` to `Cog\Laravel\Ownership`
- `ModelObserver` renamed to `OwnableObserver`
- `HasOwner` contract renamed to `Ownable`

## [3.1.0] - 2017-08-30

### Added

- Laravel 5.5 support
- Service Provider auto-discovery

## [3.0.0] - 2017-04-10

### Changed

- Default database column used by models with strict ownership was renamed from `owned_by` to `owned_by_id`.

[Upgrade instructions]

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

[Unreleased]: https://github.com/cybercog/laravel-ownership/compare/5.5.0...master
[5.5.0]: https://github.com/cybercog/laravel-ownership/compare/5.4.0...5.5.0
[5.4.0]: https://github.com/cybercog/laravel-ownership/compare/5.3.0...5.4.0
[5.3.0]: https://github.com/cybercog/laravel-ownership/compare/5.2.0...5.3.0
[5.2.0]: https://github.com/cybercog/laravel-ownership/compare/5.1.0...5.2.0
[5.1.0]: https://github.com/cybercog/laravel-ownership/compare/5.0.0...5.1.0
[5.0.0]: https://github.com/cybercog/laravel-ownership/compare/4.0.0...5.0.0
[4.0.0]: https://github.com/cybercog/laravel-ownership/compare/3.1.0...4.0.0
[3.1.0]: https://github.com/cybercog/laravel-ownership/compare/3.0.0...3.1.0
[3.0.0]: https://github.com/cybercog/laravel-ownership/compare/2.2.0...3.0.0
[2.2.0]: https://github.com/cybercog/laravel-ownership/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/cybercog/laravel-ownership/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/cybercog/laravel-ownership/compare/1.0.0...2.0.0
[Upgrade instructions]: UPGRADING.md

[#28]: https://github.com/cybercog/laravel-ownership/pull/28
[#27]: https://github.com/cybercog/laravel-ownership/pull/27
[#25]: https://github.com/cybercog/laravel-ownership/pull/25
[#23]: https://github.com/cybercog/laravel-ownership/pull/23
[#22]: https://github.com/cybercog/laravel-ownership/pull/22
[#19]: https://github.com/cybercog/laravel-ownership/pull/19
