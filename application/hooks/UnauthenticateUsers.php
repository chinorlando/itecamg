<?php

/**
 * class unauthenticateusers
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
		$this->allowed_controllers = ['login'];
		$this->allowed_methods = ['index'];
		$this->disallowed_methods = ['logout'];

		$this->CI->load->helper('url');
	}

	public function checkAccess()
	{
		$class = $this->CI->router->class;
		$method = $this->CI->router->method;
		$session = $this->CI->session->userdata('login_in'); // ver 
		// $session = $_SESSION['logged_in'];

		if (empty($session) && !in_array($class, $this->allowed_controllers)) {
			if (!in_array($method, $this->allowed_methods)) {
				redirect('login','refresh');
			}
		}

		if (empty($session) && in_array($class, $this->allowed_controllers)) {
			if (in_array($method, $this->disallowed_methods)) {
				redirect('login','refresh');
			}
		}
	}
}