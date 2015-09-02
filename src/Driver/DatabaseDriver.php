<?php namespace vendocrat\Settings\Driver;

use vendocrat\Settings\Models\Setting;
use vendocrat\Settings\SettingsUtilities;
use Illuminate\Database\Connection;

class DatabaseDriver extends Driver
{
	/**
	 * @deprecated
	 * The database connection instance.
	 *
	 * @var \Illuminate\Database\Connection
	 */
	protected $connection;

	/**
	 * @deprecated
	 * The table to query from.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * @deprecated
	 * Any query constraints that should be applied.
	 *
	 * @var \Closure|null
	 */
	protected $queryConstraint;

	/**
	 * @deprecated
	 * Any extra columns that should be added to the rows.
	 *
	 * @var array
	 */
	protected $extraColumns = array();

	/**
	 * @param \Illuminate\Database\Connection $connection
	 * @param string                          $table
	 */
	public function __construct( Connection $connection, $table = null )
	{
		/*
		 * TODO all this seems to be unnecessary as we're using Eloquent models
		 */
		$this->connection = $connection;
		$this->table = $table ?: 'settings';
	}

	/**
	 * Set the table to query from.
	 *
	 * @param string $table
	 */
	public function setTable( $table )
	{
		$this->table = $table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function forget($key)
	{
		parent::forget($key);

		// because the database store cannot store empty arrays, remove empty
		// arrays to keep data consistent before and after saving
		$segments = explode('.', $key);
		array_pop($segments);

		while ($segments) {
			$segment = implode('.', $segments);

			// non-empty array - exit out of the loop
			if ($this->get($segment)) {
				break;
			}

			// remove the empty array and move on to the next segment
			$this->forget($segment);
			array_pop($segments);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function write( array $storage )
	{
		$storage = array_dot($storage);

		$update = array(); // key-value pairs to be updated later
		$delete = array(); // key-value pairs to be deleted later

		$keys = Setting::withTrashed()->lists('key'); // existing keys

		// check what keys we have to update/create and which to delete
		foreach ( $keys as $key ) {
			if ( isset($storage[$key]) ) {
				$update[$key] = $storage[$key];
			} else {
				$delete[] = $key;
			}

			unset($storage[$key]);
		}

		// $update now keeps only those key-value pairs which are to be updated
		foreach ( $update as $key => $value ) {
			Setting::withTrashed()
				->where( 'key', $key )
				->update(['value' => $value]);

			$setting = Setting::withTrashed()
				->where( 'key', $key )
				->first();

			if ( $setting->trashed() ) {
				$setting->restore();
			}
		}

		// $storage now keeps only those key-value pairs which are to be created
		foreach ( $storage as $key => $value ) {
			Setting::create([
				'key'   => $key,
				'value' => $value
			]);
		}

		// $delete now keeps only those key-value pairs which are to be deleted
		Setting::whereIn( 'key', $delete )->delete();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function read()
	{
		return $this->parseReadData(Setting::get());
	}

	/**
	 * Parse data coming from the database.
	 *
	 * @param  array $data
	 *
	 * @return array
	 */
	public function parseReadData($data)
	{
		$results = array();

		foreach ($data as $row) {
			if (is_array($row)) {
				$key = $row['key'];
				$value = $row['value'];
			} elseif (is_object($row)) {
				$key = $row->key;
				$value = $row->value;
			} else {
				$msg = 'Expected array or object, got '.gettype($row);
				throw new \UnexpectedValueException($msg);
			}

			SettingsUtilities::set($results, $key, $value);
		}

		return $results;
	}
}
