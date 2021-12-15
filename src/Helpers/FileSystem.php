<?php namespace Lean\Helpers;

/**
 * Class that handle all the logic related with file system and search files
 * inside of the theme path.
 *
 * @since 0.1.0
 */
class FileSystem {

	/**
	 * Group of alias used to search inside of the directories.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	private $alias = [];
	/**
	 * Group of directories where to search for the file.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private $directories = [];
	/**
	 * Name of the file filled during the object creation
	 *
	 * @since 0.1.0
	 * @var stringing
	 */
	private $file_name = '';
	/**
	 * Type or alias used as subdirectory once the static class is called
	 * for instance Load::partial(... where partial is the type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	private $type = '';

	/**
	 * Constructor that triggers the filters and apply formating.
	 *
	 * @since 0.1.0
	 *
	 * @param string $file_name The name of the file.
	 * @param string $type The type to be used as subdir or alias.
	 */
	public function __construct( $file_name = '', $type = '' ) {
		$this->file_name = $file_name;
		$this->type      = $type;
		$this->apply_filters();
		$this->format();
	}

	/**
	 * Apply the filters to the alias and directories so we can hook up on any
	 * part of the WP site or even other plugins or external components.
	 *
	 * @since 0.1.0
	 */
	private function apply_filters() {
		$this->alias       = apply_filters( 'loader_alias', [] );
		$this->directories = apply_filters( 'loader_directories', [ get_stylesheet_directory() ] );
	}

	/**
	 * This function is the one in charge to trigger the search on the file system
	 * as well the one in charge to fire the silent error if the file does not
	 * exist on the system without creating a PHP error on the site.
	 *
	 * @since 0.1.0
	 *
	 * @throws \Exception IF file does not exist and is on Debug mode.
	 * @return bool | string File path if found false otherwhise.
	 */
	public function get_path() {
		$path = $this->search();
		if ( false === $path ) {
			$message = sprintf(
				'File %s does not exist of type %s',
				$this->file_name,
				$this->type
			);
			error_log( $message ); // phpcs:ignore -- Fire the silent error if the file does not exists.
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				throw new \Exception( $message );
			}
		}
		return $path;
	}

	/**
	 * This function search the file inside of all the directories registered
	 * and using the alias for the subdirectories:
	 *
	 * @since 0.1.0
	 *
	 * @return bool | string FIle path if is found false otherwse
	 */
	public function search() {
		$path  = false;
		$alias = $this->get_alias();
		foreach ( $this->directories as $directory ) {
			$path = sprintf(
				'%s/%s/%s.php',
				untrailingslashit( $directory ),
				$alias,
				$this->file_name
			);
			if ( file_exists( $path ) ) {
				break;
			}
		}
		return file_exists( $path ) ? $path : false;
	}

	/**
	 * You can create alias to folders, this alias are used as subidirectories
	 * where to search the file, for example if we have the directory called theme_path/atoms
	 * and we want to use something like Load::atom we need to define atom as an
	 * alias for tha atoms directory.
	 *
	 * @return string The alias or the type
	 */
	private function get_alias() {
		return isset( $this->alias[ $this->type ] ) ? $this->alias[ $this->type ] : $this->type;
	}

	/**
	 * Function that formats the file name to allow files with a .php extension
	 * and remove the extension, because it's added automatically when the file
	 * is tested or added.
	 *
	 * @since 0.1.0
	 */
	private function format() {
		if ( false !== strpos( $this->file_name, '.php' ) ) {
			$this->file_name = str_replace( '.php', '' );
		}
	}
}
