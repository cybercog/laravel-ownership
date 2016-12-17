# Changelog

All notable changes to `laravel-ownership` will be documented in this file.

## 1.1.0 - 2016-12-17

### Add

- `withDefaultOwner()` set default owner value on create.
- `withDefaultOwner($owner)` overwrite default owner value on create.
- `withoutDefaultOwner()` unset default owner value on create.
- `scopeWhereNotOwner($owner)` scope results to exclude unowned records by owner. 

### Fix

- Set default owner on model creation.

## 1.0.0 - 2016-12-15

- Initial release
