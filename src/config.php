<?php

if ( ! function_exists( 'get_stylesheet_directory' ) ) {
	return;
}

if ( ! defined( '_THEME_PATH_' ) ) {
	define(
		'_THEME_PATH_',
		function_exists( 'get_stylesheet_directory' ) ? get_stylesheet_directory() : ''
	);
}
