<?php

if ( ! function_exists( 'get_template_directory' ) ) {
	return;
}

if ( ! defined( '_THEME_PATH_' ) ) {
	define(
		'_THEME_PATH_',
		function_exists( 'get_template_directory' ) ? get_template_directory() : ''
	);
}
