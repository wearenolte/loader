<?php namespace Leean;

use Leean\Helpers\FileSystem;

class Load {

	public static function __callStatic( $type, $arguments ) {
		if ( empty ( $arguments ) ) {
			return;
		}
		$file = $arguments[0];
		$args = count( $arguments ) >= 2 ? $arguments[1] : [];
		self::loader( $file, $type, $args );
	}

	private static function loader( $file_name = '', $type = '', $args = [] ){
		$files = new FileSystem( $file_name, $type );
		$path = $files->get_path();
		$files = null;
		if ( false === $path ) {
			return;
		}
		include $path;
		// Clear arguments.
		unset( $args );
	}
}

