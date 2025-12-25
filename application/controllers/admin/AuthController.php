<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->helper('url');
	}

	/**
	 * Login page
	 * Maps to: /admin/AuthController or /admin/AuthController/index
	 */
	public function index()
	{
		// If already logged in, redirect to dashboard
		if ($this->session->userdata('admin_logged_in')) {
			redirect('admin/question-of-the-day');
		}
		
		$this->load->view('admin/index');
	}

	/**
	 * Handle login form submission
	 */
	public function login()
	{
		// Check if already logged in
		if ($this->session->userdata('admin_logged_in')) {
			redirect('admin/question-of-the-day');
		}

		// Get form data
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// Validate input
		if (empty($username) || empty($password)) {
			$this->session->set_flashdata('error', 'Username and password are required');
			redirect('admin/login');
		}

		// Check credentials
		$admin = $this->Admin_model->check_login($username, $password);

		if ($admin) {
			// Set session data
			$session_data = array(
				'admin_id' => $admin['admin_id'],
				'admin_uname' => $admin['admin_uname'],
				'admin_name' => $admin['admin_name'],
				'admin_email' => $admin['admin_email'],
				'admin_role' => $admin['admin_role'],
				'college_id' => $admin['college_id'],
				'admin_logged_in' => TRUE
			);

			$this->session->set_userdata($session_data);
			
			// Check if there's a redirect URL stored
			$redirect_url = $this->session->userdata('redirect_url');
			if ($redirect_url) {
				$this->session->unset_userdata('redirect_url');
				redirect($redirect_url);
			} else {
				// Redirect to dashboard
				redirect('admin/question-of-the-day');
			}
		} else {
			// Invalid credentials
			$this->session->set_flashdata('error', 'Invalid username or password');
			redirect('admin/login');
		}
	}

	/**
	 * Logout function
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('admin/login');
	}
}

