<?php namespace vendocrat\Settings\Driver;

class MemoryDriver extends Driver
{
	/**
	 * Constructor
	 *
	 * @param array $storage
	 */
	public function __construct( array $storage = array() )
	{
		if ( $storage ) {
			$this->storage = $storage;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function read()
	{
		return $this->storage;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function write( array $storage )
	{
		//
	}
}
