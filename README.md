# Laravel Ownership

[![Build Status](https://img.shields.io/travis/cybercog/laravel-ownership/master.svg?style=flat-square)](https://travis-ci.org/cybercog/laravel-ownership)
[![StyleCI](https://styleci.io/repos/76651386/shield)](https://styleci.io/repos/76651386)
[![Releases](https://img.shields.io/github/release/cybercog/laravel-ownership.svg?style=flat-square)](https://github.com/cybercog/laravel-ownership/releases)
[![License](https://img.shields.io/github/license/cybercog/laravel-ownership.svg?style=flat-square)](https://github.com/cybercog/laravel-ownership/blob/master/LICENSE)

Laravel ownership simplify management of eloquent model's owner.

![cog-laravel-ownership](https://cloud.githubusercontent.com/assets/1849174/21267082/d2f45bb8-c3b9-11e6-8961-f55c7850aa06.png)

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
- Covered with unit tests

## Installation

First, pull in the package through Composer.

```shell
composer require cybercog/laravel-ownership
```

And then include the service provider within `app/config/app.php`.

```php
'providers' => [
    Cog\Ownership\Providers\OwnershipServiceProvider::class,
];
```

## Usage

Laravel Ownership allows model to have strict owner model type (`HasOwner` trait) or use polymorphic relation (`HasMorphOwner` trait).

Strict ownership is useful when model can belongs to only one model type. Attempt to set owner of not defined model type will throw an exception `InvalidOwnerType`.
*Example: Only users allowed to create posts.*
 
Polymorphic ownership is useful when model can belongs to owners of different types.
*Example: Users and Organizations can upload applications to marketplace.*

### Prepare ownable model with strict ownership

Use `HasOwner` contract in model which will get ownership behavior and implement it or just use `HasOwner` trait. 

```php
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;
use Cog\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements HasOwnerContract {
	use HasOwner;
}
```

Ownable model with strict ownership must have in database additional nullable column to store owner relation:

```php
Schema::table('articles', function (Blueprint $table) {
    $table->integer('owned_by')->unsigned()->nullable();
    
    $table->index('owned_by');
});
```

By default owner model will be the same as `config('auth.providers.users.model')` provides.

To override default owner model in strict ownership, it's primary key or foreign key extend your ownable model with additional attributes:

```php
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;
use Cog\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements HasOwnerContract {
    use HasOwner;

    protected $ownerModel = Group::class;
    protected $ownerPrimaryKey = 'gid';
    protected $ownerForeignKey = 'group_id';
}
```

### Prepare ownable model with polymorphic ownership

Use `HasOwner` contract in model which will get polymorphic ownership behavior and implement it or just use `HasMorphOwner` trait. 

```php
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;
use Cog\Ownership\Traits\HasMorphOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements HasOwnerContract {
	use HasMorphOwner;
}
```

Ownable model with polymorphic ownership must have in database additional nullable columns to store owner relation:

**Laravel 5.3.29 and newer**

Schema::table('articles', function (Blueprint $table) {
    $table->nullableMorphs('owned_by');
});

**Laravel 5.3.28 and older**

```php
Schema::table('articles', function (Blueprint $table) {
    $table->integer('owned_by_id')->unsigned()->nullable();
    $table->string('owned_by_type')->nullable();
    
    $table->index([
        'owned_by_id',
        'owned_by_type',
    ]);
});
```

### Available functions

#### Get owner relation

```php
$article->ownedBy();
```

#### Get model owner

```php
$article->getOwner();
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

#### Manually define default owner on model creation

```php
$article = new Article();
$article->withDefaultOwner()->save();
```

*Will use `resolveDefaultOwner()` method under the hood.*

Or provide concrete owner:

```php
$user = User::where('name', 'admin')->first();
$article = new Article();
$article->withDefaultOwner($user)->save();
```

#### Skip defining default owner on model creation

```php
$article = new Article();
$article->withoutDefaultOwner()->save();
```

#### Scope models by owner

```php
Article::whereOwnedBy($owner)->get();
```

#### Scope models by not owned by owner

```php
Article::whereNotOwnedBy($owner)->get();
```

### Set authenticated user as owner

To set currently authenticated user as owner for ownable model create - extend it with attribute `withDefaultOwnerOnCreate`. It works for both strict and polymorphic ownership behavior.

```php
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;
use Cog\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements HasOwnerContract {
    use HasOwner;

    protected $withDefaultOwnerOnCreate = true;
}
```

To override strategy of getting default owner extend ownable model with `resolveDefaultOwner` method:

```php
use Cog\Ownership\Contracts\HasOwner as HasOwnerContract;
use Cog\Ownership\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements HasOwnerContract {
    use HasOwner;

    public $withDefaultOwnerOnCreate = true;
    
    /**
     * Resolve entity default owner.
     * 
     * @return \Cog\Ownership\Contracts\CanBeOwner|null
     */
    public function resolveDefaultOwner()
    {
        return \App\User::where('name', 'admin')->first();
    }
}
```

## Testing

Run the tests with:

```shell
vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email support@cybercog.su instead of using the issue tracker.

## Credits

- [Anton Komarev](https://github.com/a-komarev)
- [All Contributors](../../contributors)

## Alternatives

- [kenarkose/Ownable](https://github.com/kenarkose/Ownable)

## License

Please see [License](LICENSE) file for more information.

## About CyberCog

[CyberCog](http://www.cybercog.ru) is a Social Unity of enthusiasts. Research best solutions in product & software development is our passion.

![cybercog-logo](https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png)
