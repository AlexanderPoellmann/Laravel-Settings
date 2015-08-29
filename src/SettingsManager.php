<?php namespace vendocrat\Settings;

use vendocrat\Settings\Driver\DatabaseDriver;
use vendocrat\Settings\Driver\JsonDriver;
use vendocrat\Settings\Driver\MemoryDriver;

use Illuminate\Support\Manager;

class SettingsManager extends Manager
{

	public function createJsonDriver()
	{
		$path = $this->getConfig('path');

		return new JsonDriver($this->app['files'], $path);
	}

	public function createDatabaseDriver()
	{
		$connection = $this->app['db']->connection();
		$table = $this->getConfig('table');

		return new DatabaseDriver($connection, $table);
	}

	public function createMemoryDriver()
	{
		return new MemoryDriver();
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
