<?php namespace Lean;

use Lean\Helpers\FileSystem;

/**
 * Class that is responsible for handle all the logic behind the loading
 * using __callStatic to generate dynamic getters.
 *
 * @since 0.1.0
 */
class Load {

	/**
	 * This method allow dynamic creation of custom getter so we can load anything
	 * like for example: Load::myDirectoryisFunny( 'sample', $args );
	 *
	 * @since 0.1.0
	 *
	 * @param string $type The type of file to be loaded.
	 * @param array  $arguments The arguments frmo the static call: file and args.
	 */
	public static function __callStatic( $type, $arguments ) {
		if ( empty( $arguments ) ) {
			return;
		}
		$file  = $arguments[0];
		$total = count( $arguments );
		$args  = [];
		// The start point is 1 as the 0 is the name of the file to be loadeed.
		// So we make sure every next set of arguments are merged with the original set of arguments.
		for ( $i = 1; $i < $total; $i++ ) {
			$set = $arguments[ $i ];
			if ( is_array( $set ) ) {
				$args = wp_parse_args( $set, $args );
			}
		}
		self::loader( $file, $type, $args );
	}

	/**
	 * Function that creates an object of the FileSystem in order to search the file
	 * and pass the arguments to the file.
	 *
	 * @since 0.1.0
	 *
	 * @param string $file_name The name of the file.
	 * @param string $type The type to be loaded.
	 * @param array  $args The group of arguments to pass to the template or partial.
	 */
	private static function loader( $file_name = '', $type = '', $args = [] ) {
		$files = new FileSystem( $file_name, $type );
		$path  = $files->get_path();
		if ( false !== $path ) {
			include $path;
		}
	}
}

