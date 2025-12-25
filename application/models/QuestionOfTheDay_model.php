<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionOfTheDay_model extends CI_Model {

	/**
	 * Get all questions with optional filters
	 * @param array $filters Optional filters (status, date_from, date_to, search)
	 * @param int $limit Limit for pagination
	 * @param int $offset Offset for pagination
	 * @return array
	 */
	public function get_all($filters = array(), $limit = null, $offset = 0)
	{
		$this->db->select('*');
		$this->db->from('question_of_the_day');
		
		// Apply filters
		if (!empty($filters['status'])) {
			$this->db->where('status', $filters['status']);
		}
		
		if (!empty($filters['date_from'])) {
			$this->db->where('question_date >=', $filters['date_from']);
		}
		
		if (!empty($filters['date_to'])) {
			$this->db->where('question_date <=', $filters['date_to']);
		}
		
		if (!empty($filters['search'])) {
			$this->db->group_start();
			$this->db->like('question_description', $filters['search']);
			$this->db->or_like('category', $filters['search']);
			$this->db->group_end();
		}
		
		// Order by date descending (newest first)
		// $this->db->order_by('question_date', 'DESC');
		$this->db->order_by('id', 'DESC');
		
		// Apply pagination
		if ($limit !== null) {
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Get total count of questions with filters
	 * @param array $filters Optional filters
	 * @return int
	 */
	public function get_count($filters = array())
	{
		$this->db->from('question_of_the_day');
		
		// Apply filters
		if (!empty($filters['status'])) {
			$this->db->where('status', $filters['status']);
		}
		
		if (!empty($filters['date_from'])) {
			$this->db->where('question_date >=', $filters['date_from']);
		}
		
		if (!empty($filters['date_to'])) {
			$this->db->where('question_date <=', $filters['date_to']);
		}
		
		if (!empty($filters['search'])) {
			$this->db->group_start();
			$this->db->like('question_description', $filters['search']);
			$this->db->or_like('category', $filters['search']);
			$this->db->group_end();
		}
		
		return $this->db->count_all_results();
	}

	/**
	 * Get question by ID
	 * @param int $id
	 * @return array|bool
	 */
	public function get_by_id($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('question_of_the_day');
		
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
		
		return false;
	}

	/**
	 * Insert new question
	 * @param array $data
	 * @return int|bool Inserted ID or false on failure
	 */
	public function insert($data)
	{
		// Prepare data
		$insert_data = array(
			'question_description' => $data['question_description'],
			'category' => !empty($data['category']) ? $data['category'] : null,
			'question_date' => $data['question_date'],
			'status' => $data['status'],
			'option_1' => !empty($data['option_1']) ? $data['option_1'] : null,
			'option_2' => !empty($data['option_2']) ? $data['option_2'] : null,
			'option_3' => !empty($data['option_3']) ? $data['option_3'] : null,
			'option_4' => !empty($data['option_4']) ? $data['option_4'] : null,
			'correct_answer' => !empty($data['correct_answer']) ? (int)$data['correct_answer'] : null,
			'explanation' => !empty($data['explanation']) ? $data['explanation'] : null,
			'video_type' => !empty($data['video_type']) ? $data['video_type'] : 'none',
			'video_file' => !empty($data['video_file']) ? $data['video_file'] : null,
			'youtube_link' => !empty($data['youtube_link']) ? $data['youtube_link'] : null,
		);
		
		if ($this->db->insert('question_of_the_day', $insert_data)) {
			return $this->db->insert_id();
		}
		
		return false;
	}

	/**
	 * Update question
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
	public function update($id, $data)
	{
		// Prepare data
		$update_data = array(
			'question_description' => $data['question_description'],
			'category' => !empty($data['category']) ? $data['category'] : null,
			'question_date' => $data['question_date'],
			'status' => $data['status'],
			'option_1' => !empty($data['option_1']) ? $data['option_1'] : null,
			'option_2' => !empty($data['option_2']) ? $data['option_2'] : null,
			'option_3' => !empty($data['option_3']) ? $data['option_3'] : null,
			'option_4' => !empty($data['option_4']) ? $data['option_4'] : null,
			'correct_answer' => !empty($data['correct_answer']) ? (int)$data['correct_answer'] : null,
			'explanation' => !empty($data['explanation']) ? $data['explanation'] : null,
			'video_type' => !empty($data['video_type']) ? $data['video_type'] : 'none',
			'video_file' => !empty($data['video_file']) ? $data['video_file'] : null,
			'youtube_link' => !empty($data['youtube_link']) ? $data['youtube_link'] : null,
		);
		
		$this->db->where('id', $id);
		return $this->db->update('question_of_the_day', $update_data);
	}

	/**
	 * Delete question
	 * @param int $id
	 * @return bool
	 */
	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('question_of_the_day');
	}

	/**
	 * Get today's active question (for home page)
	 * @return array|bool
	 */
	public function get_today_question()
	{
		$today = date('Y-m-d');

		$this->db->from('question_of_the_day');
		$this->db->where('question_date', $today);
		$this->db->where('status', 'active');
		$this->db->order_by('question_date', 'DESC');
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		}

		return false;
	}

	/**
	 * Get previous active questions before today
	 * @param int $limit
	 * @return array
	 */
	public function get_previous_questions($limit = 5)
	{
		$today = date('Y-m-d');

		$this->db->from('question_of_the_day');
		$this->db->where('question_date <', $today);
		$this->db->where('status', 'active');
		$this->db->order_by('question_date', 'DESC');
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit);

		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Get total count of previous active questions before today
	 * @return int
	 */
	public function get_previous_questions_count()
	{
		$today = date('Y-m-d');

		$this->db->from('question_of_the_day');
		$this->db->where('question_date <', $today);
		$this->db->where('status', 'active');

		return $this->db->count_all_results();
	}

	/**
	 * Get previous active questions before today with pagination
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function get_previous_questions_paginated($limit, $offset = 0)
	{
		$today = date('Y-m-d');

		$this->db->from('question_of_the_day');
		$this->db->where('question_date <', $today);
		$this->db->where('status', 'active');
		$this->db->order_by('question_date', 'DESC');
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $offset);

		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Check if a question exists for a given date
	 * @param string $date Date in Y-m-d format
	 * @param int $exclude_id Optional ID to exclude from check (for edit operations)
	 * @return bool True if question exists, false otherwise
	 */
	public function question_exists_for_date($date, $exclude_id = null)
	{
		$this->db->from('question_of_the_day');
		$this->db->where('question_date', $date);
		
		// Exclude current question ID if editing
		if ($exclude_id !== null) {
			$this->db->where('id !=', $exclude_id);
		}
		
		$query = $this->db->get();
		return $query->num_rows() > 0;
	}
}
