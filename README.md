[![Latest Stable Version](https://poser.pugx.org/vendocrat/laravel-settings/v/stable)](https://packagist.org/packages/vendocrat/laravel-settings)
[![Total Downloads](https://poser.pugx.org/vendocrat/laravel-settings/downloads)](https://packagist.org/packages/vendocrat/laravel-settings)
[![License](https://poser.pugx.org/vendocrat/laravel-settings/license)](https://packagist.org/packages/vendocrat/laravel-settings)

# Laravel Settings

Persistent, application-wide settings for Laravel 5.

## Installation

1. Require the package from your `composer.json` file

```php
"require": {
	"vendocrat/laravel-settings": "dev-master"
}
```

2. Run update

```
composer update
```

3. Register service provider and facade to your `config/app.php` file

```php
'vendocrat\Settings\SettingsServiceProvider',

'Settings' => 'vendocrat\Settings\Facades\Setting',
```

### Storage

4. Publish config file and migrations

```
php artisan vendor:publish
```

5. Migrate

```
php artisan migrate
```

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

**Handcrafted with love by [Alexander Manfred Poellmann](http://twitter.com/AMPoellmann) for [vendocrat](https://vendocr.at) in Vienna &amp; Rome.**

Based on the awesome package [Laravel Settings](https://github.com/anlutro/laravel-settings) by [Andreas Lutro](http://www.lutro.me).