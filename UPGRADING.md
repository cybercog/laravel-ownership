# Upgrade Guide

- [Upgrading From 4.0 To 5.0](#upgrade-5.0)
- [Upgrading From 3.0 To 4.0](#upgrade-4.0)
- [Upgrading From 2.0 To 3.0](#upgrade-3.0)

<a name="upgrade-5.0"></a>
## Upgrading From 4.0 To 5.0

- Find all `Cog\Contracts\Laravel\Ownership` and replace with `Cog\Contracts\Ownership`

<a name="upgrade-4.0"></a>
## Upgrading From 3.0 To 4.0

- Find all `Cog\Ownership\Contracts\HasOwner` and replace with `Cog\Contracts\Laravel\Ownership\Ownable`
- Find all `Cog\Ownership\Observers\ModelObserver` and replace with `Cog\Laravel\Ownership\Observers\OwnableObserver`
- Find all `Cog\Ownership\Contracts` and replace with `Cog\Contracts\Laravel\Ownership`
- Find all `Cog\Ownership` and replace with `Cog\Laravel\Ownership`

<a name="upgrade-3.0"></a>
## Upgrading From 2.0 To 3.0

You need to upgrade only if you have models with Strict Ownership and you are using default `owned_by` column names.

- Rename database columns `owned_by` to `owned_by_id` for all the ownable models with strict ownership.
- If you have raw DB queries - don't forget to modify them as well.

### What if I want to keep old naming?!

You are able to keep old naming without any database changes. Overwrite foreign keys in ownable models by adding `$ownerForeignKey` property:

```php
protected $ownerForeignKey = 'owned_by';
```

[See example of foreign key overwrite](https://github.com/cybercog/laravel-ownership#overwrite-strict-ownership-owners-foreign-key)
