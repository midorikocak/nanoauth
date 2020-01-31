![Nano Auth](nano.png)
# Nano Auth

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


Nano Auth is a small library that allows you to create authentication for your apps.

## Requirements

Strictly requires PHP 7.4.

## Install

Via Composer

``` bash
$ composer require midorikocak/nanoauth
```

## Usage

### Authentication

The Authentication class has 4 public methods that you can use:

```php
<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

interface AuthenticationInterface
{
    public function login(string $username, string $password): bool;

    public function logout(): void;

    public function isLogged(): bool;

    public function getLoggedUser();
}
```

To use it you should supply with a user repository.

```php
$db = new Database(new PDO('sqlite::memory:'));
$userRepository = new UserRepository($db);
$auth = new Authentication($userRepository);
```

### User implements UserInterface

A user object that we can authenticate should implemennt `UserInterface`.

```php
<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

interface UserInterface
{
    public function __construct(?string $id, string $username, string $email, string $password);

    public function getPassword(): string;

    public function getUsername(): string;

    public function getEmail(): string;

    public function getId(): ?string;

    public function setId(string $id): void;
}
```

### UserRepository

To interact with user data, we need to supply user repository to our authentication object. If you want to use your own 
implementation of user repository, you can implement your own `UserInnterface`. 
Here repository constructor expects a `NanoDB` object.

```php
$userRepository = new UserRepository($db);
$auth = new Authentication($userRepository);
```

### Authorization

To add authentication and authorization capabilities,
you can use `AuthorizationTrait` in your App classes. 

Let's say we created our app in this way:

```php
<?php

declare(strict_types=1);

use midorikocak\nanodb\Database;
use midorikocak\nanoauth\UserRepository;
use midorikocak\nanoauth\Authentication;

$db = new Database(new PDO('sqlite::memory:'));

$userRepository = new UserRepository($db);

$auth = new Authentication($userRepository);
$entryRepository = new Journal($db);
$app = new App($entryRepository);

$this->app->setAuthentication($this->auth);

```

To check login we should trigger `checkLogin()` method in our public methods.

```php
<?php 

declare(strict_types=1);

use midorikocak\nanodb\Database;
use midorikocak\nanoauth\UserRepository;
use midorikocak\nanoauth\Authentication;
use midorikocak\nanoauth\AuthorizationTrait;

 class App
{
    use AuthorizationTrait;

    private Journal $journal;

    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }

    public function addEntry(string $content)
    {
        $this->checkLogin();

        $entry = new Entry($content);
        $this->journal->save($entry);
    }
}
```

## Motivation

Mostly educational purposes.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mtkocak@gmail.com instead of using the issue tracker.

## Credits

- [Midori Kocak][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/midorikocak/nanoauth.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/midorikocak/nanoauth/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/midorikocak/nanoauth.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/midorikocak/nanoauth.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/midorikocak/nanoauth.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/midorikocak/nanoauth
[link-travis]: https://travis-ci.org/midorikocak/nanoauth
[link-scrutinizer]: https://scrutinizer-ci.com/g/midorikocak/nanoauth/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/midorikocak/nanoauth
[link-downloads]: https://packagist.org/packages/midorikocak/nanoauth
[link-author]: https://github.com/midorikocak
[link-contributors]: ../../contributors
