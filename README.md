![cog-laravel-ownership](https://cloud.githubusercontent.com/assets/1849174/21737911/ee344682-d48e-11e6-9ace-eea37026ae6d.png)

<p align="center">
<a href="https://github.com/cybercog/laravel-ownership/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/cybercog/laravel-ownership/tests.yml?style=flat-square" alt="Build"></a>
<a href="https://styleci.io/repos/76651386"><img src="https://styleci.io/repos/76651386/shield" alt="StyleCI"></a>
<a href="https://github.com/cybercog/laravel-ownership/releases"><img src="https://img.shields.io/github/release/cybercog/laravel-ownership.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/laravel-ownership/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/laravel-ownership.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Laravel Ownership simplify management of eloquent model's owner.
Group can be an owner of event, user can be an owner of chat room, organization can own licenses.
It can be used for many cases not limited by authorship.
Make any model as owner and create ownable models in a minutes!

## Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Prepare ownable model with strict ownership](#prepare-ownable-model-with-strict-ownership)
  - [Prepare ownable model with polymorphic ownership](#prepare-ownable-model-with-polymorphic-ownership)
  - [Available methods](#available-methods)
  - [Scopes](#scopes)
  - [Set authenticated user as owner automatically](#set-authenticated-user-as-owner-automatically)
- [Change log](#change-log)
- [Upgrading](#upgrading)
- [Contributing](#contributing)
- [Testing](#testing)
- [Security](#security)
- [Credits](#credits)
- [Alternatives](#alternatives)
- [License](#license)
- [About CyberCog](#about-cybercog)

## Features

- Designed to work with Laravel Eloquent models
- Using contracts to keep high customization capabilities
- Each model can has owners of one type or use polymorphism
- Option to auto-assigning current authenticated user on model creation as owner
- Configurable auto-owner resolve strategy on model creation
- Option to manually assign owner on model creation
- Option to manually skip auto-assigning current user
- Transfer ownership (change owner)
- Make model orphaned (abandon owner)
- Various ownership checks and query scopes
- Following PHP Standard Recommendations:
  - [PSR-2 (Coding Style Guide)](http://www.php-fig.org/psr/psr-2/).
  - [PSR-4 (Autoloading Standard)](http://www.php-fig.org/psr/psr-4/).
- Covered with unit tests

## Installation

First, pull in the package through Composer.

```shell
composer require cybercog/laravel-ownership
```

And then include the service provider within `app/config/app.php`.

```php
'providers' => [
    Cog\Laravel\Ownership\Providers\OwnershipServiceProvider::class,
];
```

## Usage

Laravel Ownership allows model to have strict owner model type (`HasOwner` trait) or use polymorphic relation (`HasMorphOwner` trait).

Strict ownership is useful when model can belong to only one model type. Attempt to set owner of not defined model type will throw an exception `InvalidOwnerType`.
*Example: Only users allowed to create posts.*

Polymorphic ownership is useful when model can belong to owners of different types.
*Example: Users and Organizations can upload applications to marketplace.*

### Prepare owner model

At the owner model use `CanBeOwner` contract and implement it:

```php
use Cog\Contracts\Ownership\CanBeOwner as CanBeOwnerInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements CanBeOwnerInterface
{
    // ...
}
```

### Prepare ownable model with strict ownership

Use `Ownable` contract in model which will get ownership behavior and implement it or just use `HasOwner` trait.

```php
use Cog\Contracts\Ownership\Ownable as OwnableInterface;
use Cog\Laravel\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements OwnableInterface
{
    use HasOwner;
}
```

Ownable model with strict ownership must have in database additional nullable column to store owner relation:

```php
Schema::table('articles', function (Blueprint $table) {
    $table->integer('owned_by_id')->unsigned()->nullable();

    $table->index('owned_by_id');
});
```

#### Overwrite strict ownership owner's foreign key

By default owner model will be the same as `config('auth.providers.users.model')` provides.

To override default owner model in strict ownership, it's primary key or foreign key extend your ownable model with additional attributes:

```php
use Cog\Contracts\Ownership\Ownable as OwnableInterface;
use Cog\Laravel\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements OwnableInterface
{
    use HasOwner;

    protected $ownerModel = Group::class;
    protected $ownerPrimaryKey = 'gid';
    protected $ownerForeignKey = 'group_id';
}
```

### Prepare ownable model with polymorphic ownership

Use `Ownable` contract in model which will get polymorphic ownership behavior and implement it or just use `HasMorphOwner` trait.

```php
use Cog\Contracts\Ownership\Ownable as OwnableInterface;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements OwnableInterface
{
    use HasMorphOwner;
}
```

Ownable model with polymorphic ownership must have in database additional nullable columns to store owner relation:

```php
Schema::table('articles', function (Blueprint $table) {
    $table->nullableMorphs('owned_by');
});
```

### Available methods

#### Get owner relation

```php
$article->ownedBy();
$article->owner();
```

#### Get model owner

```php
$article->getOwner();
$article->ownedBy;
$article->owner;
```

#### Change (set) owner

```php
$article->changeOwnerTo($owner);
```

#### Abandon (unset) owner

```php
$article->abandonOwner();
```

#### Check if has owner

```php
$article->hasOwner();
```

#### Check if owned by owner

```php
$article->isOwnedBy($owner);
```

#### Check not owned by owner

```php
$article->isNotOwnedBy($owner);
```

#### Manually define default owner on model creation

```php
$article = new Article;
$article->withDefaultOwner()->save();
```

*Will use `resolveDefaultOwner()` method under the hood.*

Or provide concrete owner:

```php
$user = User::where('name', 'admin')->first();
$article = new Article;
$article->withDefaultOwner($user)->save();
```

#### Skip defining default owner on model creation

```php
$article = new Article;
$article->withoutDefaultOwner()->save();
```

### Scopes

#### Scope models by owner

```php
Article::whereOwnedBy($owner)->get();
```

#### Scope models by not owned by owner

```php
Article::whereNotOwnedBy($owner)->get();
```

### Set authenticated user as owner automatically

To set currently authenticated user as owner for ownable model create - extend it with attribute `withDefaultOwnerOnCreate`. It works for both strict and polymorphic ownership behavior.

```php
use Cog\Contracts\Ownership\Ownable as OwnableInterface;
use Cog\Laravel\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements OwnableInterface
{
    use HasOwner;

    protected $withDefaultOwnerOnCreate = true;
}
```

To override strategy of getting default owner extend ownable model with `resolveDefaultOwner` method:

```php
use Cog\Contracts\Ownership\Ownable as OwnableInterface;
use Cog\Laravel\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements OwnableInterface
{
    use HasOwner;

    public $withDefaultOwnerOnCreate = true;

    /**
     * Resolve entity default owner.
     * 
     * @return \Cog\Contracts\Ownership\CanBeOwner|null
     */
    public function resolveDefaultOwner()
    {
        return \App\User::where('name', 'admin')->first();
    }
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrading

Please see [UPGRADING](UPGRADING.md) for detailed upgrade instructions.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Testing

Run the tests with:

```shell
vendor/bin/phpunit
```

## Security

If you discover any security related issues, please email open@cybercog.su instead of using the issue tracker.

## Credits

| <a href="https://github.com/antonkomarev">![@antonkomarev](https://avatars.githubusercontent.com/u/1849174?s=110)<br />Anton Komarev</a> | <a href="https://github.com/soap">![@soap](https://avatars.githubusercontent.com/u/1073690?s=110)<br />Prasit Gebsaap</a> |
| :---: |:---------------------------------------------------------------------------------------------------------------------------------:|

[Laravel Ownership contributors list](../../contributors)

## Alternatives

*Feel free to add more alternatives as Pull Request.* 

## License

- `Laravel Ownership` package is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].
- `Intellectual Property` image licensed under [Creative Commons 3.0](https://creativecommons.org/licenses/by/3.0/us/) by Arthur Shlain.
- `Fat Boss` image licensed under [Creative Commons 3.0](https://creativecommons.org/licenses/by/3.0/us/) by Gan Khoon Lay. 

## About CyberCog

[CyberCog](https://cybercog.su) is a Social Unity of enthusiasts. Research the best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)
- [Read our articles on Medium](https://medium.com/cybercog)

<a href="https://cybercog.su"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>

[Anton Komarev]: https://komarev.com
