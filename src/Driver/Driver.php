<?php namespace vendocrat\Settings\Driver;

use vendocrat\Settings\Contracts\DriverInterface;
use vendocrat\Settings\SettingsUtilities;

abstract class Driver implements DriverInterface
{
	/**
	 * The array of stored values.
	 *
	 * @var array
	 */
	protected $storage = [];

	/**
	 * Whether the store has changed since it was last loaded.
	 *
	 * @var bool
	 */
	protected $modified = false;

	/**
	 * Whether the settings data are loaded.
	 *
	 * @var bool
	 */
	protected $loaded = false;

	/**
	 * Get all settings.
	 *
	 * @return array
	 */
	public function all()
	{
		$this->checkLoaded();

		return $this->storage;
	}

	/**
	 * Determine if an item exists in the configuration.
	 *
	 * @param  string  $key
	 * @return boolean
	 */
	public function has($key)
	{
		$this->checkLoaded();

		return ! is_null( $this->get($key) );
	}

	/**
	 * Get a specific key from the settings data.
	 *
	 * @param array|string $key
	 * @param mixed        $default
	 * @param bool         $save
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null, $save = false )
	{
		$this->checkLoaded();

		return SettingsUtilities::get($this->storage, $key, $default);
	}

	/**
	 * Store an item in the configuration for a given number of minutes.
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param string $group
	 */
	public function set( $key, $value = null, $group = '' )
	{
		$this->checkLoaded();
		$this->modified = true;

		if ( is_array($key) ) {
			foreach ( $key as $k => $v ) {
				SettingsUtilities::set($this->storage, $k, $v);
			}
		} else {
			SettingsUtilities::set($this->storage, $key, $value);
		}
	}

	/**
	 * Remove an item from the configuration.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function forget($key)
	{
		$this->checkLoaded();
		$this->modified = true;

		if ( $this->has($key) ) {
			SettingsUtilities::forget($this->storage, $key);
		}

		return true;
	}

	/**
	 * Remove all items from the configuration.
	 */
	public function flush()
	{
		$this->storage = [];
		$this->modified = true;
	}

	/**
	 * Save any changes done to the settings data.
	 */
	public function save()
	{
		if ( ! $this->modified ) {
			return;
		}

		$this->write($this->storage);
		$this->modified = false;
	}

	/**
	 * Check if the settings data has been loaded.
	 */
	protected function checkLoaded()
	{
		if ( ! $this->modified ) {
		//	$this->storage = json_decode(json_encode($this->read()), true);
			$this->storage  = $this->read();
			$this->modified = true;
		}
	}

	/**
	 * Read the data from the store.
	 *
	 * @return array
	 */
	abstract protected function read();

	/**
	 * Write the data into the store.
	 *
	 * @param  array  $storage
	 * @return void
	 */
	abstract protected function write( array $storage );
}