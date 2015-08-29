<?php namespace vendocrat\Settings;

use Illuminate\Support\ServiceProvider;
use vendocrat\Settings\Driver\Driver;

class SettingsServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ .'/../config/config.php' => config_path('settings.php')
		], 'config');

		$this->publishes([
			__DIR__ .'/../database/migrations/' => database_path('migrations')
		], 'migrations');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ .'/../config/config.php',
			'settings'
		);

		$this->app->singleton(SettingsManager::class, function ($app) {
			return new SettingsManager($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return string[]
	 */
	public function provides()
	{
		return [
			SettingsManager::class,
			Driver::class
		];
	}
}
