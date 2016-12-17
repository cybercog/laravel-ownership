# Changelog

All notable changes to `laravel-ownership` will be documented in this file.

## 2.0.0 - 2016-12-17

### Add

- `withDefaultOwner()` set default owner value on create.
- `withDefaultOwner($owner)` overwrite default owner value on create.
- `withoutDefaultOwner()` unset default owner value on create.
- `scopeWhereNotOwner($owner)` scope results to exclude unowned records by owner.

### Change

- Renamed method `getDefaultOwner()` to `resolveDefaultOwner()`.
- Renamed flag attribute `$setDefaultOwnerOnCreate` to `$withDefaultOwnerOnCreate`.

### Fix

- Set default owner on model creation.

## 1.0.0 - 2016-12-15

- Initial release
