<?php namespace vendocrat\Settings\Driver;

use vendocrat\Settings\Exceptions\NotWriteableException;
use Illuminate\Filesystem\Filesystem;

abstract class FileDriver extends Driver
{
	/**
	 * Filesystem instance.
	 *
	 * @var Filesystem
	 */
	protected $files;

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * Constructor.
	 *
	 * @param Filesystem   $files
	 * @param string       $path
	 */
	public function __construct( Filesystem $files, $path = '' )
	{
		$this->files = $files;
		$this->setPath($path ?: storage_path() .'/settings.json');
	}

	/**
	 * Set the path for the JSON file.
	 *
	 * @param string $path
	 * @throws NotWriteableException
	 */
	public function setPath($path)
	{
		if ( ! $this->files->exists($path) ) {
			$result = $this->files->put($path, '{}');
			if ( $result === false ) {
				throw new NotWriteableException("Could not write to $path.");
			}
		}

		if ( ! $this->files->isWritable($path) ) {
			throw new NotWriteableException("$path is not writable.");
		}

		$this->path = $path;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function read()
	{
		$contents = $this->files->get($this->path);

		$storage = json_decode(json_encode($contents, true));

		if ( $storage === null ) {
			throw new \RuntimeException("Invalid JSON in {$this->path}");
		}

		return $storage;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function write( array $storage )
	{
		if ( $storage ) {
			$contents = json_encode($storage);
		} else {
			$contents = '{}';
		}

		$this->files->put($this->path, $contents);
	}
}