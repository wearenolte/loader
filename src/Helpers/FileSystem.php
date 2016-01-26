<?php namespace Leean\Helpers;

/**
 * Class that handle all the logic related with file system and search files
 * inside of the theme path.
 *
 * @since 0.1.0
 */
class FileSystem {

	private $alias = [];
	private $directories = [];
	private $file_name = '';
	private $type = '';

	public function __construct( $file_name = '', $type = '' ){
		$this->file_name = $file_name;
		$this->type = $type;
		$this->apply_filters();
		$this->format();
	}

	private function apply_filters(){
		$this->alias = apply_filters( 'loader_alias', [] );
		$this->directories = apply_filters( 'loader_directories', [ _THEME_PATH_ ] );
	}

	public function get_path() {
		$path = $this->search();
		if ( file_exists( $path ) ) {
			return $path;
		} else {
			Error::file_not_found( $path );
			return false;
		}
	}

	public function search() {
		$path = false;
		$alias = $this->get_alias();
		foreach( $this->directories as $directory ) {
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
		return $path;
	}

	private function get_alias(){
		return isset( $this->alias[ $this->type ] ) ? $this->alias[ $this->type ] : $this->type;
	}

	private function format() {
		if ( false !== strpos( $this->file_name, '.php' ) ) {
			$this->file_name = str_replace( '.php', '' );
		}
	}
}
