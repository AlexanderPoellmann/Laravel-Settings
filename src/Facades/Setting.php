<?php namespace vendocrat\Settings\Facades;

use vendocrat\Settings\SettingsManager;
use Illuminate\Support\Facades\Facade;

class Setting extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return SettingsManager::class;
	}
}
