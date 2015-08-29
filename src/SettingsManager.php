<?php namespace AlexanderPoellmann\LaravelSettings;

use AlexanderPoellmann\LaravelSettings\Driver\SettingsDriverDatabase;
use AlexanderPoellmann\LaravelSettings\Driver\SettingsDriverJson;
use AlexanderPoellmann\LaravelSettings\Driver\SettingsDriverMemory;

use Illuminate\Support\Manager;

class SettingsManager extends Manager
{

	public function createJsonDriver()
	{
		$path = $this->getConfig('path');

		return new SettingsDriverJson($this->app['files'], $path);
	}

	public function createDatabaseDriver()
	{
		$connection = $this->app['db']->connection();
		$table = $this->getConfig('table');

		return new SettingsDriverDatabase($connection, $table);
	}

	public function createMemoryDriver()
	{
		return new SettingsDriverMemory();
	}

	public function createArrayDriver()
	{
		return $this->createMemoryDriver();
	}

	public function getDefaultDriver()
	{
		return $this->getConfig('driver');
	}

	protected function getConfig( $key )
	{
		return $this->app['config']->get('settings.'. $key);
	}
}
