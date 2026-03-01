# AGENTS.md

This file provides guidance to LLM Agents when working with code in this repository.

## Project Overview

Laravel Ownership is a PHP package (`cybercog/laravel-ownership`) that simplifies management of Eloquent model ownership. It supports both strict (single-type) and polymorphic (multi-type) ownership via traits. Supports Laravel 9-13 and PHP 8.0-8.5.

## Commands

All commands run through Docker. Services: `php81`, `php82`, `php83`, `php84`, `php85`.

```bash
# Build and start containers
docker compose up -d --build

# Install dependencies
docker compose exec php85 composer install

# Run all tests (uses in-memory SQLite)
docker compose exec php85 composer test

# Run a single test file
docker compose exec php85 vendor/bin/phpunit tests/Unit/Traits/HasOwnerTest.php

# Run a single test method
docker compose exec php85 vendor/bin/phpunit --filter test_method_name
```

## Namespaces & Autoloading

- `Cog\Contracts\Ownership\` → `contracts/` (interfaces)
- `Cog\Laravel\Ownership\` → `src/` (implementations)
- `Cog\Tests\Laravel\Ownership\` → `tests/` (tests)

## Architecture

### Two Ownership Modes

**HasOwner** (strict) — `src/Traits/HasOwner.php`
- `belongsTo()` relationship with a single configurable owner model type
- Validates owner type on assignment; throws `InvalidOwnerType` for mismatches
- Default foreign key: `owned_by_id`

**HasMorphOwner** (polymorphic) — `src/Traits/HasMorphOwner.php`
- `morphTo()` relationship allowing multiple owner types
- Uses columns: `owned_by_id` + `owned_by_type`
- No type validation; accepts any `CanBeOwner` implementor

Both traits implement the `Ownable` contract and share the same API: `changeOwnerTo()`, `abandonOwner()`, `hasOwner()`, `isOwnedBy()`, `withDefaultOwner()`, query scopes, etc.

### Key Design Patterns

- **Traits as entry points**: Models use `HasOwner` or `HasMorphOwner` traits and implement the `Ownable` contract.
- **Contracts separate from implementation**: All interfaces live in `contracts/`, implementations in `src/`.
- **Observer-driven auto-assignment**: `OwnableObserver` hooks into model `creating` events. When `$withDefaultOwnerOnCreate = true`, it assigns `Auth::user()` as owner. Override `resolveDefaultOwner()` to customize.
- **Deferred service provider**: `OwnershipServiceProvider` binds the `CanBeOwner` contract to the configured auth user model.

## Testing

Tests use Orchestra Testbench with in-memory SQLite. The base `TestCase` class (`tests/TestCase.php`) handles migrations, factory registration, and morph map setup. Test stubs live in `tests/Stubs/Models/` and factories in `tests/database/factories/`.

## Code Conventions

- All files include the copyright header block.
- PSR-12 coding style (StyleCI with "laravel" preset).
