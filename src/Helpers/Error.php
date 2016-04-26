<?php namespace Lean\Helpers;

/**
 * Error class that will add warnings into the PHP but withoth breaking the page.
 *
 * @since 0.1.0
 */
class Error {
	/**
	 * This functions adds a warning message withoth braking the site or causing
	 * a PHP error on the site when a file does not exist.
	 *
	 * @since 0.1.0
	 *
	 * @param string $path The file path to be used on the warning message.
	 */
	public static function file_not_found( $path ) {
		$message = sprintf( '<code>%s</code> does not exist.', $path );
		$allowed_html = [
			'code' => [],
		];
		_doing_it_wrong( __FUNCTION__, wp_kses( $message, $allowed_html ), '4.3.1' );
	}
}
