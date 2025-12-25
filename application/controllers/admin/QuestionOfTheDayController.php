<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionOfTheDayController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// Load required libraries
		$this->load->library('form_validation');
		
		// Check if user is logged in
		if (!$this->session->userdata('admin_logged_in')) {
			// Store the current URL to redirect after login (if available)
			$current_url = uri_string();
			if (!empty($current_url)) {
				$this->session->set_userdata('redirect_url', $current_url);
			}
			redirect('admin/login');
		}
		
		// Load model
		$this->load->model('QuestionOfTheDay_model');
	}

	/**
	 * Question of the Day listing page
	 * Maps to: /admin/question-of-the-day
	 */
	public function index()
	{
		// Get filters from query string
		$filters = array();
		if ($this->input->get('status')) {
			$filters['status'] = $this->input->get('status');
		}
		if ($this->input->get('search')) {
			$filters['search'] = $this->input->get('search');
		}
		if ($this->input->get('date_from')) {
			$filters['date_from'] = $this->input->get('date_from');
		}
		if ($this->input->get('date_to')) {
			$filters['date_to'] = $this->input->get('date_to');
		}
		
		// Pagination
		$per_page = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 10;
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		$offset = ($page - 1) * $per_page;
		
		// Get total count
		$total = $this->QuestionOfTheDay_model->get_count($filters);
		
		// Get questions
		$questions = $this->QuestionOfTheDay_model->get_all($filters, $per_page, $offset);
		
		$data = array(
			'questions' => $questions,
			'total' => $total,
			'per_page' => $per_page,
			'current_page' => $page,
			'filters' => $filters,
			'offset' => $offset,
		);
		
		$this->load->view('admin/question_of_the_day_listing', $data);
	}

	/**
	 * Add Question of the Day page
	 * Maps to: /admin/question-of-the-day/add
	 */
	public function add()
	{
		$data = array(
			'question' => null,
			'is_edit' => false
		);
		
		$this->load->view('admin/question_of_the_day_add', $data);
	}

	/**
	 * Edit Question of the Day page
	 * Maps to: /admin/question-of-the-day/edit/{id}
	 */
	public function edit($id = null)
	{
		if (!$id) {
			$this->session->set_flashdata('error', 'Invalid question ID');
			redirect('admin/question-of-the-day');
		}
		
		$question = $this->QuestionOfTheDay_model->get_by_id($id);
		
		if (!$question) {
			$this->session->set_flashdata('error', 'Question not found');
			redirect('admin/question-of-the-day');
		}
		
		$data = array(
			'question' => $question,
			'is_edit' => true
		);
		
		$this->load->view('admin/question_of_the_day_add', $data);
	}

	/**
	 * Save question (create or update)
	 * Maps to: /admin/question-of-the-day/save
	 */
	public function save()
	{
		// Validate required fields
		$this->form_validation->set_rules('question_description', 'Question Description', 'required');
		$this->form_validation->set_rules('category', 'Category', 'trim');
		$this->form_validation->set_rules('question_date', 'Post Date', 'required|callback_not_past_date|callback_check_duplicate_date');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive,draft]');
		$this->form_validation->set_rules('option_1', 'Option 1', 'required');
		$this->form_validation->set_rules('option_2', 'Option 2', 'required');
		// Option 3 and 4 are optional
		$this->form_validation->set_rules('option_3', 'Option 3', 'trim');
		$this->form_validation->set_rules('option_4', 'Option 4', 'trim');
		$this->form_validation->set_rules('correct_answer', 'Correct Answer', 'required|in_list[1,2,3,4]');
		$this->form_validation->set_rules('explanation', 'Explanation', 'required');
		$this->form_validation->set_rules('video_type', 'Video Type', 'in_list[upload,youtube,none]');
		$this->form_validation->set_rules('youtube_link', 'YouTube Link', 'trim|callback_validate_youtube_link');
		
		if ($this->form_validation->run() == FALSE) {
			// Validation failed
			$is_edit = $this->input->post('id') ? true : false;
			
			// Preserve submitted data so the form is repopulated correctly
			$post_data = $this->input->post(NULL, TRUE);
			
			if ($is_edit) {
				// Start with existing DB data and override with submitted values
				$existing = $this->QuestionOfTheDay_model->get_by_id($this->input->post('id'));
				$question = is_array($existing) ? array_merge($existing, $post_data) : $post_data;
			} else {
				$question = $post_data;
			}
			
			$data = array(
				'question' => !empty($question) ? $question : null,
				'is_edit' => $is_edit,
				'validation_errors' => validation_errors()
			);
			$this->load->view('admin/question_of_the_day_add', $data);
			return;
		}
		
		// Handle video upload if video type is 'upload'
		$video_file = null;
		$video_type = $this->input->post('video_type') ? $this->input->post('video_type') : 'none';
		
		if ($video_type == 'upload') {
			// Check if a new video file is being uploaded
			if (isset($_FILES['video_file']) && !empty($_FILES['video_file']['name'])) {
				// Check for upload errors
				if ($_FILES['video_file']['error'] != 0) {
					$upload_error_msg = $this->get_upload_error_message($_FILES['video_file']['error']);
					$is_edit = $this->input->post('id') ? true : false;
					$post_data = $this->input->post(NULL, TRUE);
					
					if ($is_edit) {
						$existing = $this->QuestionOfTheDay_model->get_by_id($this->input->post('id'));
						$question = is_array($existing) ? array_merge($existing, $post_data) : $post_data;
					} else {
						$question = $post_data;
					}
					
					$error_msg = 'Video Upload Error: ' . $upload_error_msg;
					$error_msg = nl2br(htmlspecialchars($error_msg));
					$data = array(
						'question' => !empty($question) ? $question : null,
						'is_edit' => $is_edit,
						'validation_errors' => validation_errors() . '<br>' . $error_msg
					);
					$this->load->view('admin/question_of_the_day_add', $data);
					return;
				}
				
				// Try to upload the video
				$upload_result = $this->upload_video();
				if ($upload_result['success']) {
					$video_file = $upload_result['file_path'];
					
					// If editing, delete old video file if it exists
					$id = $this->input->post('id');
					if ($id) {
						$existing_question = $this->QuestionOfTheDay_model->get_by_id($id);
						if ($existing_question && !empty($existing_question['video_file'])) {
							// Handle both relative path and full URL formats
							$old_video_path = $existing_question['video_file'];
							if (strpos($old_video_path, 'assets/') !== false) {
								// Relative path format: assets/admin/uploads/videos/filename.mp4
								$old_file_path = './' . $old_video_path;
							} else {
								// Full URL or just filename - extract filename
								$old_file_path = './assets/admin/uploads/videos/' . basename($old_video_path);
							}
							if (file_exists($old_file_path)) {
								@unlink($old_file_path);
							}
						}
					}
				} else {
					// Upload failed, set error and return to form
					$is_edit = $this->input->post('id') ? true : false;
					$post_data = $this->input->post(NULL, TRUE);
					
					if ($is_edit) {
						$existing = $this->QuestionOfTheDay_model->get_by_id($this->input->post('id'));
						$question = is_array($existing) ? array_merge($existing, $post_data) : $post_data;
					} else {
						$question = $post_data;
					}
					
					$error_msg = 'Video Upload Error: ' . $upload_result['error'];
					$error_msg = nl2br(htmlspecialchars($error_msg));
					$data = array(
						'question' => !empty($question) ? $question : null,
						'is_edit' => $is_edit,
						'validation_errors' => validation_errors() . '<br>' . $error_msg
					);
					$this->load->view('admin/question_of_the_day_add', $data);
					return;
				}
			} else {
				// No new file uploaded, check if editing and keep existing file
				$id = $this->input->post('id');
				if ($id) {
					$existing_question = $this->QuestionOfTheDay_model->get_by_id($id);
					if ($existing_question && !empty($existing_question['video_file'])) {
						$video_file = $existing_question['video_file'];
					} else {
						// Editing but no existing file and no new file uploaded
						$is_edit = true;
						$post_data = $this->input->post(NULL, TRUE);
						$existing = $this->QuestionOfTheDay_model->get_by_id($id);
						$question = is_array($existing) ? array_merge($existing, $post_data) : $post_data;
						
						$data = array(
							'question' => !empty($question) ? $question : null,
							'is_edit' => $is_edit,
							'validation_errors' => validation_errors() . '<br>Please upload a video file.'
						);
						$this->load->view('admin/question_of_the_day_add', $data);
						return;
					}
				} else {
					// New question but no file uploaded
					$is_edit = false;
					$post_data = $this->input->post(NULL, TRUE);
					$question = $post_data;
					
					$data = array(
						'question' => !empty($question) ? $question : null,
						'is_edit' => $is_edit,
						'validation_errors' => validation_errors() . '<br>Please upload a video file.'
					);
					$this->load->view('admin/question_of_the_day_add', $data);
					return;
				}
			}
		}
		
		// Prepare data
		$data = array(
			'question_description' => $this->input->post('question_description'),
			'category' => $this->input->post('category'),
			'question_date' => $this->input->post('question_date'),
			'status' => $this->input->post('status'),
			'option_1' => $this->input->post('option_1'),
			'option_2' => $this->input->post('option_2'),
			'option_3' => $this->input->post('option_3'),
			'option_4' => $this->input->post('option_4'),
			'correct_answer' => $this->input->post('correct_answer'),
			'explanation' => $this->input->post('explanation'),
			'video_type' => $video_type,
			'video_file' => $video_file,
			'youtube_link' => ($video_type == 'youtube') ? $this->input->post('youtube_link') : null
		);
		
		// If video type is not upload, clear video_file
		if ($video_type != 'upload') {
			$data['video_file'] = null;
		}
		
		// If video type is not youtube, clear youtube_link
		if ($video_type != 'youtube') {
			$data['youtube_link'] = null;
		}
		
		// If editing and changing video type, delete old files
		$id = $this->input->post('id');
		if ($id) {
			$existing_question = $this->QuestionOfTheDay_model->get_by_id($id);
			if ($existing_question) {
			// If changing from upload to something else, delete old video file
			if ($existing_question['video_type'] == 'upload' && $video_type != 'upload' && !empty($existing_question['video_file'])) {
				// Handle both relative path and full URL formats
				$old_video_path = $existing_question['video_file'];
				if (strpos($old_video_path, 'assets/') !== false) {
					// Relative path format: assets/admin/uploads/videos/filename.mp4
					$old_file_path = './' . $old_video_path;
				} else {
					// Full URL or just filename - extract filename
					$old_file_path = './assets/admin/uploads/videos/' . basename($old_video_path);
				}
				if (file_exists($old_file_path)) {
					@unlink($old_file_path);
				}
			}
			}
		}
		
		$id = $this->input->post('id');
		
		if ($id) {
			// Update existing question
			if ($this->QuestionOfTheDay_model->update($id, $data)) {
				$this->session->set_flashdata('success', 'Question updated successfully');
				redirect('admin/question-of-the-day');
			} else {
				$this->session->set_flashdata('error', 'Failed to update question');
				redirect('admin/question-of-the-day/edit/' . $id);
			}
		} else {
			// Insert new question
			$insert_id = $this->QuestionOfTheDay_model->insert($data);
			if ($insert_id) {
				$this->session->set_flashdata('success', 'Question added successfully');
				redirect('admin/question-of-the-day');
			} else {
				$this->session->set_flashdata('error', 'Failed to add question');
				redirect('admin/question-of-the-day/add');
			}
		}
	}

	/**
	 * Validation callback: Post Date must not be in the past
	 */
	public function not_past_date($date)
	{
		if (empty($date)) {
			// Let the 'required' rule handle empty value
			return TRUE;
		}

		$today = date('Y-m-d');

		if ($date < $today) {
			$this->form_validation->set_message('not_past_date', 'The {field} cannot be in the past.');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Validation callback: Check if question already exists for the selected date
	 */
	public function check_duplicate_date($date)
	{
		if (empty($date)) {
			// Let the 'required' rule handle empty value
			return TRUE;
		}

		// Get the question ID if editing (exclude current question from check)
		$exclude_id = $this->input->post('id') ? (int)$this->input->post('id') : null;

		// Check if a question already exists for this date
		if ($this->QuestionOfTheDay_model->question_exists_for_date($date, $exclude_id)) {
			$this->form_validation->set_message('check_duplicate_date', 'Already question is there for selected date.');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Validation callback: Validate YouTube link
	 */
	public function validate_youtube_link($link)
	{
		$video_type = $this->input->post('video_type');
		
		// Only validate if video type is youtube
		if ($video_type == 'youtube') {
			if (empty($link)) {
				$this->form_validation->set_message('validate_youtube_link', 'YouTube link is required when video type is YouTube.');
				return FALSE;
			}
			
			// Validate YouTube URL format
			$youtube_pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/';
			if (!preg_match($youtube_pattern, $link)) {
				$this->form_validation->set_message('validate_youtube_link', 'Please enter a valid YouTube URL.');
				return FALSE;
			}
		}
		
		return TRUE;
	}

	/**
	 * Handle video file upload
	 * @return array
	 */
	private function upload_video()
	{
		$config['upload_path'] = './assets/admin/uploads/videos/';
		$config['allowed_types'] = 'mp4|avi|mov|wmv|flv|webm|mkv';
		$config['max_size'] = 51200; // 50MB
		$config['encrypt_name'] = TRUE;

		// Create upload directory if it doesn't exist
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0755, true);
		}

		// Load or initialize upload library
		if (!isset($this->upload)) {
			$this->load->library('upload', $config);
		} else {
			$this->upload->initialize($config);
		}

		if (!$this->upload->do_upload('video_file')) {
			$error = $this->upload->display_errors('', '');
			return array('success' => false, 'error' => $error);
		} else {
			$data = $this->upload->data();
			$video_path = 'assets/admin/uploads/videos/' . $data['file_name'];
			return array('success' => true, 'file_path' => $video_path);
		}
	}

	/**
	 * Get human-readable upload error message
	 * @param int $error_code
	 * @return string
	 */
	private function get_upload_error_message($error_code)
	{
		$php_upload_max = ini_get('upload_max_filesize');
		$php_post_max = ini_get('post_max_size');
		$php_max_size = $this->convert_to_bytes($php_upload_max);
		$php_post_max_bytes = $this->convert_to_bytes($php_post_max);
		
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				$message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
				$message .= "\n\nCurrent PHP Settings:";
				$message .= "\n- upload_max_filesize: " . $php_upload_max . " (" . $this->format_bytes($php_max_size) . ")";
				$message .= "\n- post_max_size: " . $php_post_max . " (" . $this->format_bytes($php_post_max_bytes) . ")";
				$message .= "\n\nSolution: Increase upload_max_filesize and post_max_size in php.ini (recommended: at least 50M for both)";
				$message .= "\n\nFor WAMP: Edit php.ini file (usually in C:\\wamp64\\bin\\php\\php[version]\\php.ini)";
				$message .= "\nFind and change: upload_max_filesize = 50M and post_max_size = 50M";
				$message .= "\nThen restart WAMP services.";
				return $message;
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
			case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded.';
			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded.';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder.';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk.';
			case UPLOAD_ERR_EXTENSION:
				return 'A PHP extension stopped the file upload.';
			default:
				return 'Unknown upload error (Error code: ' . $error_code . ').';
		}
	}

	/**
	 * Convert PHP size string to bytes
	 * @param string $size
	 * @return int
	 */
	private function convert_to_bytes($size)
	{
		$size = trim($size);
		$last = strtolower($size[strlen($size)-1]);
		$size = (int)$size;
		
		switch($last) {
			case 'g':
				$size *= 1024;
			case 'm':
				$size *= 1024;
			case 'k':
				$size *= 1024;
		}
		
		return $size;
	}

	/**
	 * Format bytes to human readable format
	 * @param int $bytes
	 * @return string
	 */
	private function format_bytes($bytes)
	{
		if ($bytes >= 1073741824) {
			return number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			return number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			return number_format($bytes / 1024, 2) . ' KB';
		} else {
			return $bytes . ' bytes';
		}
	}

	/**
	 * Delete question
	 * Maps to: /admin/question-of-the-day/delete/{id}
	 */
	public function delete($id = null)
	{
		if (!$id) {
			$this->session->set_flashdata('error', 'Invalid question ID');
			redirect('admin/question-of-the-day');
		}
		
		if ($this->QuestionOfTheDay_model->delete($id)) {
			$this->session->set_flashdata('success', 'Question deleted successfully');
		} else {
			$this->session->set_flashdata('error', 'Failed to delete question');
		}
		
		redirect('admin/question-of-the-day');
	}

	/**
	 * Handle image upload for Summernote editor
	 */
	public function upload_image()
	{
		$config['upload_path'] = './assets/admin/uploads/images/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
		$config['max_size'] = 2048; // 2MB
		$config['encrypt_name'] = TRUE;

		// Create upload directory if it doesn't exist
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0755, true);
		}

		$this->load->library('upload', $config);

		$this->output->set_content_type('application/json');

		if (!$this->upload->do_upload('file')) {
			$error = $this->upload->display_errors('', '');
			$this->output->set_output(json_encode(array('error' => $error)));
		} else {
			$data = $this->upload->data();
			$image_url = base_url('assets/admin/uploads/images/' . $data['file_name']);
			$this->output->set_output(json_encode(array('location' => $image_url)));
		}
	}
}

