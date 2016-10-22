[![Latest Stable Version](https://poser.pugx.org/lecturize/laravel-meta/v/stable)](https://packagist.org/packages/lecturize/laravel-meta)
[![Total Downloads](https://poser.pugx.org/lecturize/laravel-meta/downloads)](https://packagist.org/packages/lecturize/laravel-meta)
[![License](https://poser.pugx.org/lecturize/laravel-meta/license)](https://packagist.org/packages/lecturize/laravel-meta)

# Meta

Simple and fluent meta data for Eloquent Models in Laravel 5.

## Important Notice

We've transferred this package to a new owner and therefor updated the namespaces to **Lecturize\Addresses**. The config file is now `config/lecturize.php`.

## Installation

Require the package from your `composer.json` file

**Attention:** This package is a work in progress, please use with care and be sure to report any issues!

```php
"require": {
	"lecturize/laravel-meta": "dev-master"
}
```

and run `$ composer update` or both in one with `$ composer require lecturize/laravel-meta`.

Next register the service provider and (optional) facade to your `config/app.php` file

```php
'providers' => [
    // Illuminate Providers ...
    // App Providers ...
    Lecturize\Meta\MetaServiceProvider::class
];
```

## Configuration & Migration

```bash
$ php artisan vendor:publish --provider="lecturize\Meta\MetaServiceProvider"
```

This will create a `config/lecturize.php` and a migration file. In the config file you can customize the table names, finally you'll have to run migration like so:

```bash
$ php artisan migrate
```

## License

Licensed under [MIT license](http://opensource.org/licenses/MIT).

## Author

**Handcrafted with love by [Alexander Manfred Poellmann](http://twitter.com/AMPoellmann) for [Lecturize](https://lecturize.com) in Vienna &amp; Rome.**