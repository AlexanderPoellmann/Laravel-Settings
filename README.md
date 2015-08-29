[![Latest Stable Version](https://poser.pugx.org/AlexanderPoellmann/laravel-settings/v/stable.svg)](https://packagist.org/packages/AlexanderPoellmann/laravel-settings)
[![Total Downloads](https://poser.pugx.org/AlexanderPoellmann/laravel-settings/downloads.svg)](https://packagist.org/packages/AlexanderPoellmann/laravel-settings)
[![License](https://poser.pugx.org/AlexanderPoellmann/laravel-settings/license.svg)](https://packagist.org/packages/AlexanderPoellmann/laravel-settings)

# Laravel Settings

Persistant, application-wide settings for Laravel 5.

## Installation

1. Require the package from your `composer.json` file

	"require": {
		"AlexanderPoellmann/laravel-settings": "dev-master"
	}

2. Run update

    composer update

3. Register service provider and facade to your `config/app.php` file

```php
'AlexanderPoellmann\LaravelSettings\SettingsServiceProvider',

'Settings' => 'AlexanderPoellmann\LaravelSettings\Facades\SettingsFacade',
```

### Storage

4. Publish config file and migrations

	php artisan vendor:publish

5. Migrate

	php artisan migrate

## Usage

```php
<?php
$settings = Setting::all();		// get all settings

Setting::set('foo', 'bar');		// set setting
Setting::get('foo', 'default'); // get setting with default
Setting::get('nested.setting'); // get a nestet setting
Setting::forget('foo');			// forget a setting
?>
```

## License

Licensed under [MIT license](http://opensource.org/licenses/MIT).

## Author

**Handcrafted with love by [Alexander Manfred Poellmann](http://twitter.com/AMPoellmann) in Vienna &amp; Rome.**

Based on [Laravel Settings](https://github.com/anlutro/laravel-settings) by [Andreas Lutro](http://www.lutro.me).