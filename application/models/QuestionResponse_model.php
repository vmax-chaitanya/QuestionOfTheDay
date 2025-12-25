<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionResponse_model extends CI_Model
{
    /**
     * Check if this browser has already submitted an answer for a question.
     *
     * @param int $question_id
     * @param string $browser_id
     * @return bool
     */
    public function has_submitted($question_id, $browser_id)
    {
        if (empty($question_id) || empty($browser_id)) {
            return false;
        }

        $this->db->from('question_responses');
        $this->db->where('question_id', (int)$question_id);
        $this->db->where('browser_id', $browser_id);

        return $this->db->count_all_results() > 0;
    }

    /**
     * Get a single response for this question + browser.
     *
     * @param int $question_id
     * @param string $browser_id
     * @return array|null
     */
    public function get_response($question_id, $browser_id)
    {
        if (empty($question_id) || empty($browser_id)) {
            return null;
        }

        $this->db->from('question_responses');
        $this->db->where('question_id', (int)$question_id);
        $this->db->where('browser_id', $browser_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return null;
    }

    /**
     * Insert a new response row.
     *
     * @param array $data
     * @return bool
     */
    public function insert_response($data)
    {
        return $this->db->insert('question_responses', $data);
    }

    /**
     * Get latest responses for a given question (for comments).
     *
     * @param int $question_id
     * @param int $limit
     * @return array
     */
    public function get_recent_for_question($question_id, $limit = 4)
    {
        if (empty($question_id)) {
            return array();
        }

        $this->db->from('question_responses');
        $this->db->where('question_id', (int)$question_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit((int)$limit);

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get a response by ID.
     *
     * @param int $response_id
     * @return array|null
     */
    public function get_response_by_id($response_id)
    {
        if (empty($response_id)) {
            return null;
        }

        $this->db->from('question_responses');
        $this->db->where('id', (int)$response_id);
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return null;
    }

    /**
     * Get answer counts for multiple questions.
     *
     * @param array $question_ids
     * @return array [question_id => count]
     */
    public function get_counts_for_questions($question_ids)
    {
        if (empty($question_ids) || !is_array($question_ids)) {
            return array();
        }

        $ids = array_map('intval', $question_ids);

        $this->db->select('question_id, COUNT(*) as total');
        $this->db->from('question_responses');
        $this->db->where_in('question_id', $ids);
        $this->db->group_by('question_id');

        $query = $this->db->get();

        $result = array();
        foreach ($query->result_array() as $row) {
            $result[(int)$row['question_id']] = (int)$row['total'];
        }

        return $result;
    }
}
