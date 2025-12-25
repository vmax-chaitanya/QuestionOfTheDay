<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Admin Controller
 * All admin controllers should extend this to ensure authentication
 */
class MY_Admin_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// Check if user is logged in
		if (!$this->session->userdata('admin_logged_in')) {
			// Store the current URL to redirect after login (if available)
			$current_url = uri_string();
			if (!empty($current_url)) {
				$this->session->set_userdata('redirect_url', $current_url);
			}
			redirect('admin/login');
		}
	}
}

