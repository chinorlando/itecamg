<?php

/**
 * class authenticateusers
 */
class UnauthenticateUsers
{
	private $CI;
	private $allowed_controllers;
	private $allowed_methods;
	private $disallowed_methods;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->disallowed_controllers = [''];
		$this->allowed_methods = [''];
		$this->disallowed_methods = [''];

		$this->CI->load->helper('url');
	}

	public function checkAccess()
	{
		$class = $this->CI->router->class;
		$method = $this->CI->router->method;
		$session = $this->CI->session->userdata('logged_in'); // ver 
		// $session = $_SESSION['logged_in'];

		if ($session && in_array($class, $this->disallowed_controllers)) {
			if (!in_array($method, $this->allowed_methods)) {
				redirect('login','refresh');
			}
		}

		if ($session && !in_array($class, $this->disallowed_controllers)) {
			if (in_array($method, $this->disallowed_methods)) {
				redirect('login','refresh');
			}
		}
	}
}