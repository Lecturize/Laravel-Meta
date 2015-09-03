[![Latest Stable Version](https://poser.pugx.org/vendocrat/laravel-meta/v/stable)](https://packagist.org/packages/vendocrat/laravel-meta)
[![Total Downloads](https://poser.pugx.org/vendocrat/laravel-meta/downloads)](https://packagist.org/packages/vendocrat/laravel-meta)
[![License](https://poser.pugx.org/vendocrat/laravel-meta/license)](https://packagist.org/packages/vendocrat/laravel-meta)

# Meta

Simple and fluent meta data for Eloquent Models in Laravel 5.

## Installation

Require the package from your `composer.json` file

```php
"require": {
	"vendocrat/laravel-meta": "dev-master"
}
```

and run `$ composer update` or both in one with `$ composer require vendocrat/laravel-meta`.

Next register the service provider and (optional) facade to your `config/app.php` file

```php
'providers' => [
    // Illuminate Providers ...
    // App Providers ...
    vendocrat\Meta\MetaServiceProvider::class
];
```

```php
'providers' => [
	// Illuminate Facades ...
    'Meta' => vendocrat\Meta\Facades\Meta::class
];
```

## Configuration & Migration

```bash
$ php artisan vendor:publish --provider="vendocrat\Meta\MetaServiceProvider"
```

This will create a `config/meta.php` and a migration file. In the config file you can customize the table names, finally you'll have to run migration like so:

```bash
$ php artisan migrate
```

## License

Licensed under [MIT license](http://opensource.org/licenses/MIT).

## Author

**Handcrafted with love by [Alexander Manfred Poellmann](http://twitter.com/AMPoellmann) for [vendocrat](https://vendocr.at) in Vienna &amp; Rome.**