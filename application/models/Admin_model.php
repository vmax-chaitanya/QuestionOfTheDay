<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	/**
	 * Check admin login credentials
	 * @param string $username
	 * @param string $password
	 * @return array|bool
	 */
	public function check_login($username, $password)
	{
		$this->db->where('admin_uname', $username);
		$this->db->where('admin_status', 'y'); // Only active admins
		$query = $this->db->get('gk_adminusers');
		
		if ($query->num_rows() > 0) {
			$admin = $query->row_array();
			// Verify password (assuming passwords are hashed)
			// If passwords are stored in plain text, use: if($admin['admin_password'] == $password)
			if (password_verify($password, $admin['admin_password']) || $admin['admin_password'] == $password) {
				return $admin;
			}
		}
		
		return false;
	}

	/**
	 * Get admin by username
	 * @param string $username
	 * @return array|bool
	 */
	public function get_admin_by_username($username)
	{
		$this->db->where('admin_uname', $username);
		$this->db->where('admin_status', 'y');
		$query = $this->db->get('gk_adminusers');
		
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
		
		return false;
	}
}

