<?php namespace vendocrat\Settings\Contracts;

interface DriverInterface
{
	/**
	 * Get all stored settings
	 *
	 * @return mixed
	 */
	public function all();

	/**
	 * Check if setting key exists
	 *
	 * @param $key
	 * @return mixed
	 */
	public function has($key);

	/**
	 * Get setting by key
	 *
	 * @param string|array $key
	 * @param mixed        $default Optional default value.
	 * @param bool         $save
	 * @return mixed
	 */
	public function get($key, $default = null, $save = false);

	/**
	 * Store an item in the configuration.
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param string $group
	 */
	public function set($key, $value, $group = '');

	/**
	 * Forget setting
	 *
	 * @param $key
	 */
	public function forget($key);

	/**
	 * Remove all items from the configuration.
	 */
	public function flush();

	/**
	 * Save dirty data into the data source
	 *
	 * @return mixed
	 */
	public function save();
}