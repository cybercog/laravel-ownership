# Upgrade Guide

- [Upgrading To 3.0 From 2.0](#upgrade-3.0)

<a name="upgrade-3.0"></a>
## Upgrading To 3.0 From 2.0

You need to upgrade only if you have models with Strict Ownership and you are using default `owned_by` column names.

- Rename database columns `owned_by` to `owned_by_id` for all the ownable models with strict ownership.
- If you have raw DB queries - don't forget to modify them as well.

### What if I want to keep old naming?!

You are able to keep old naming without any database changes. Overwrite foreign keys in ownable models by adding `$ownerForeignKey` property:

```php
protected $ownerForeignKey = 'owned_by';
```

[See example of foreign key overwrite](https://github.com/cybercog/laravel-ownership#overwrite-strict-ownership-owners-foreign-key)
