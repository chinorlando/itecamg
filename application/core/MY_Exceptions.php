<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class MY_Exceptions extends CI_Exceptions
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function show_403($page = '', $log_error = TRUE)
	{
		if (is_cli())
		{
			$heading = 'Not Found';
			$message = 'The controller/method pair you requested was not found.';
		}
		else
		{
			// $heading = '403 Page Forbidden';
			$heading = '403 Access Forbidden';
			// $message = 'The page you requested is orbidden.';
			$message = 'The page you requested is forbidden.';
			
		}

		// By default we log this, but allow a dev to skip it
		if ($log_error)
		{
			log_message('error', $heading.': '.$page);
		}

		echo $this->show_error($heading, $message, 'error_403', 403);
		exit(4); // EXIT_UNKNOWN_FILE
	}
}