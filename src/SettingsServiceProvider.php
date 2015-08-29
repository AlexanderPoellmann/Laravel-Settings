<?php namespace AlexanderPoellmann\LaravelSettings;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
	/**
	 * This provider is deferred and should be lazy loaded.
	 *
	 * @var boolean
	 */
	protected $defer = true;

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->setupConfig();
	}

	/**
	 * Setup the config.
	 *
	 * @return void
	 */
	protected function setupConfig()
	{
		$source = realpath(__DIR__.'/../config/config.php');

		if ( class_exists('Illuminate\Foundation\Application', false) ) {
			$this->publishes([
				$source => config_path('settings.php')
			]);
		}

		$this->mergeConfigFrom($source, 'settings');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('AlexanderPoellmann\LaravelSettings\SettingsManager', function($app) {
			// When the class has been resolved once, make sure that settings
			// are saved when the application shuts down.
			$app->shutdown(function($app) {
				$app->make('AlexanderPoellmann\LaravelSettings\Driver\SettingsDriver')->save();
			});

			return new SettingsManager($app);
		});

		// Provide a shortcut to the SettingStore for injecting into classes.
		$this->app->bind('AlexanderPoellmann\LaravelSettings\Driver\SettingsDriver', function($app) {
			return $app->make('AlexanderPoellmann\LaravelSettings\SettingsManager')->driver();
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
			'AlexanderPoellmann\LaravelSettings\SettingsManager',
			'AlexanderPoellmann\LaravelSettings\Driver\SettingsDriver',
		];
	}
}
