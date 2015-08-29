<?php namespace vendocrat\Settings;

class SettingsUtilities
{
	/**
	 * Get an element from an array.
	 *
	 * @param  array  $storage
	 * @param  string $key     Specify a nested element by separating keys with full stops.
	 * @param  mixed  $default If the element is not found, return this.
	 *
	 * @return mixed
	 */
	public static function get( array $storage, $key, $default = null )
	{
		if ($key === null) {
			return $storage;
		}

		if (is_array($key)) {
			return static::getArray($storage, $key, $default);
		}

		foreach (explode('.', $key) as $segment) {
			if (!is_array($storage)) {
				return $default;
			}

			if (!array_key_exists($segment, $storage)) {
				return $default;
			}

			$storage = $storage[$segment];
		}

		return $storage;
	}

	protected static function getArray(array $input, $keys, $default = null)
	{
		$output = array();

		foreach ($keys as $key) {
			static::set($output, $key, static::get($input, $key, $default));
		}

		return $output;
	}

	/**
	 * Determine if an array has a given key.
	 *
	 * @param  array   $storage
	 * @param  string  $key
	 *
	 * @return boolean
	 */
	public static function has(array $storage, $key)
	{
		foreach (explode('.', $key) as $segment) {
			if (!is_array($storage)) {
				return false;
			}

			if (!array_key_exists($segment, $storage)) {
				return false;
			}

			$storage = $storage[$segment];
		}

		return true;
	}

	/**
	 * Set an element of an array.
	 *
	 * @param array  $storage
	 * @param string $key   Specify a nested element by separating keys with full stops.
	 * @param mixed  $value
	 */
	public static function set( array &$storage, $key, $value )
	{
		$segments = explode('.', $key);

		$key = array_pop($segments);

		// iterate through all of $segments except the last one
		foreach ($segments as $segment) {
			if (!array_key_exists($segment, $storage)) {
				$storage[$segment] = array();
			} else if (!is_array($storage[$segment])) {
				throw new \UnexpectedValueException('Non-array segment encountered');
			}

			$storage =& $storage[$segment];
		}

		$storage[$key] = $value;
	}

	/**
	 * Unset an element from an array.
	 *
	 * @param  array  &$storage
	 * @param  string $key   Specify a nested element by separating keys with full stops.
	 */
	public static function forget( array &$storage, $key )
	{
		$segments = explode('.', $key);

		$key = array_pop($segments);

		// iterate through all of $segments except the last one
		foreach ($segments as $segment) {
			if (!array_key_exists($segment, $storage)) {
				return;
			} else if (!is_array($storage[$segment])) {
				throw new \UnexpectedValueException('Non-array segment encountered');
			}

			$storage =& $storage[$segment];
		}

		unset($storage[$key]);
	}
}
