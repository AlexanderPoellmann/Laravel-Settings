[![Latest Stable Version](https://poser.pugx.org/vendocrat/laravel-settings/v/stable)](https://packagist.org/packages/vendocrat/laravel-settings)
[![Total Downloads](https://poser.pugx.org/vendocrat/laravel-settings/downloads)](https://packagist.org/packages/vendocrat/laravel-settings)
[![License](https://poser.pugx.org/vendocrat/laravel-settings/license)](https://packagist.org/packages/vendocrat/laravel-settings)

# Laravel Settings

Persistent, application-wide settings for Laravel 5.

**Attention:** This package is a work in progress, please use with care and be sure to report any issues!

## Installation

Require the package from your `composer.json` file

```php
"require": {
	"vendocrat/laravel-settings": "dev-master"
}
```

and run `$ composer update` or both in one with `$ composer require vendocrat/laravel-settings`.

Next register the service provider and (optional) facade to your `config/app.php` file

```php
'providers' => [
    // Illuminate Providers ...
    // App Providers ...
    vendocrat\Settings\SettingsServiceProvider::class
];
```

```php
'providers' => [
	// Illuminate Facades ...
    'Setting' => vendocrat\Settings\Facades\Setting::class
];
```

## Configuration

Laravel Settings includes an optional config file. Get started buy publishing it:

```bash
$ php artisan vendor:publish --provider="vendocrat\Settings\SettingsServiceProvider" --tag="config"
```

This will create a `config/settings.php` file where you can set for example which driver you want to use (JSON file, database, ...).

## Migration

Note: You'll only need to complete this step when using the database driver.

If you want to store your settings in your database, you'll have to set `'driver'` in your `config/settings.php` file to `'database'` and publish the migration like so:

```bash
$ php artisan vendor:publish --provider="vendocrat\Settings\SettingsServiceProvider" --tag="migrations"
```

Afterwards you'll have to run the artisan migrate command:

```bash
$ php artisan migrate
```

## Usage

##### Get all settings
```php
$settings = \Setting::all();
```

##### Check if a setting exists
```php
\Setting::has($key);
```

##### Get a setting
```php
$setting = \Setting::get($key);
```

##### Add/update a setting
```php
\Setting::set($key, $value);
```

##### Delete a setting
```php
\Setting::forget($key);
```

##### Delete all settings
```php
\Setting::flush();
```

##### Save your updates (set, forget, flush)
```php
\Setting::save();
```

## Example

The following example would store the setting `'bar'` with the key `'foo'`, then update it to `'bars'`, save it and then die & dump all current settings, which is only one unique `'foo'` key-value pair.

```php
\Setting::set('foo', 'bar');
\Setting::set('foo', 'bars');

\Setting::save();

dd(\Setting::all());
```

Results in

```html
array [
  "foo" => "bars"
]
```

## My Edits

* restructured folders & files
* uses Eloquent models for database driver with soft deleting
* add interface contract
* removed middleware

#### To-Dos

* add Redis support
* add option to group settings (e.g. config/user/...)
* add logic to automatically render input forms in the frontend for settings (like a simple App Settings view or so)

## License

Licensed under [MIT license](http://opensource.org/licenses/MIT).

## Author

**Handcrafted with love by [Alexander Manfred Poellmann](http://twitter.com/AMPoellmann) for [vendocrat](https://vendocr.at) in Vienna &amp; Rome.**

Based on [Laravel Settings](https://github.com/anlutro/laravel-settings) by [Andreas Lutro](http://www.lutro.me).