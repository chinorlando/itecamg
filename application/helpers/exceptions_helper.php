<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Defines common exception functions that are globally available
 */
 
if ( ! function_exists('show_403'))
{
	/**
	 * 404 Page Handler
	 *
	 * This function is similar to the show_error() function above
	 * However, instead of the standard error template it displays
	 * 404 errors.
	 *
	 * @param	string
	 * @param	bool
	 * @return	void
	 */
	function show_403($page = '', $log_error = TRUE)
	{
		$_error =& load_class('Exceptions', 'core');
		$_error->show_403($page, $log_error);
		exit(4); // EXIT_UNKNOWN_FILE
	}
}