<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    /**
     * Pseudo-identifier for this browser (stored in cookie).
     *
     * @var string
     */
    protected $browser_id;

    public function __construct()
    {
        parent::__construct();
        // Load helpers
        $this->load->helper(array('url', 'cookie'));
        // Load models
        $this->load->model('QuestionOfTheDay_model');
        $this->load->model('QuestionResponse_model');
        $this->load->model('CommentLike_model');

        // Initialize / ensure browser identifier
        $this->browser_id = $this->get_or_create_browser_id();
    }

    /**
     * Base route: shows Question of the Day page.
     */
    public function index()
    {
        // If a specific question_id is requested via query param, redirect to that question view
        $question_id = (int)$this->input->get('question_id');
        if ($question_id > 0) {
            redirect('question/' . $question_id);
            return;
        }

        // Pagination settings for previous questions
        $per_page = 4;
        $page = (int)$this->input->get('page');
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $per_page;

        // Get today's active question
        $today_question = $this->QuestionOfTheDay_model->get_today_question();
        $has_attempted_today = false;
        $user_response_today = null;
        $recent_comments = array();
        if (!empty($today_question)) {
            $has_attempted_today = $this->QuestionResponse_model->has_submitted(
                (int)$today_question['id'],
                $this->browser_id
            );

            if ($has_attempted_today) {
                $user_response_today = $this->QuestionResponse_model->get_response(
                    (int)$today_question['id'],
                    $this->browser_id
                );
            }

            // Latest comments for this question (for Recent Comments section)
            $recent_comments = $this->QuestionResponse_model->get_recent_for_question(
                (int)$today_question['id'],
                4
            );
            // Attach like/dislike counts and user votes
            $recent_comments = $this->attach_like_dislike_data($recent_comments);
        }
        // Get previous active questions (for sidebar) with pagination
        $total_previous = $this->QuestionOfTheDay_model->get_previous_questions_count();
        $previous_questions = $this->QuestionOfTheDay_model->get_previous_questions_paginated($per_page, $offset);
        $total_pages = $per_page > 0 ? (int)ceil($total_previous / $per_page) : 1;

        // Attach answers_count to previous questions
        if (!empty($previous_questions)) {
            $prev_ids = array_column($previous_questions, 'id');
            $counts_map = $this->QuestionResponse_model->get_counts_for_questions($prev_ids);

            foreach ($previous_questions as &$pq) {
                $qid = (int)$pq['id'];
                $pq['answers_count'] = isset($counts_map[$qid]) ? (int)$counts_map[$qid] : 0;
            }
            unset($pq);
        }

        $data = array(
            'today_question'     => $today_question,
            'previous_questions' => $previous_questions,
            'prev_current_page'  => $page,
            'prev_total_pages'   => $total_pages,
            'has_attempted'      => $has_attempted_today,
            'user_response'      => $user_response_today,
            'recent_comments'    => $recent_comments,
        );

        $this->load->view('home/index', $data);
    }

    /**
     * Show a specific question (used when clicking a previous question).
     *
     * @param int $id
     */
    public function question($id = 0)
    {
        $id = (int)$id;
        if ($id <= 0) {
            redirect('/');
            return;
        }

        // Get the requested question by ID
        $question = $this->QuestionOfTheDay_model->get_by_id($id);

        // If not found or inactive/draft, go back to home
        if (empty($question) || $question['status'] !== 'active') {
            redirect('/');
            return;
        }

        // Pagination settings for previous questions (reuse same sidebar)
        $per_page = 4;
        $page = (int)$this->input->get('page');
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $per_page;

        $total_previous = $this->QuestionOfTheDay_model->get_previous_questions_count();
        $previous_questions = $this->QuestionOfTheDay_model->get_previous_questions_paginated($per_page, $offset);
        $total_pages = $per_page > 0 ? (int)ceil($total_previous / $per_page) : 1;

        // Attach answers_count to previous questions
        if (!empty($previous_questions)) {
            $prev_ids = array_column($previous_questions, 'id');
            $counts_map = $this->QuestionResponse_model->get_counts_for_questions($prev_ids);

            foreach ($previous_questions as &$pq) {
                $qid = (int)$pq['id'];
                $pq['answers_count'] = isset($counts_map[$qid]) ? (int)$counts_map[$qid] : 0;
            }
            unset($pq);
        }

        $has_attempted = $this->QuestionResponse_model->has_submitted(
            (int)$question['id'],
            $this->browser_id
        );

        $user_response = null;
        if ($has_attempted) {
            $user_response = $this->QuestionResponse_model->get_response(
                (int)$question['id'],
                $this->browser_id
            );
        }

        // Latest comments for this question
        $recent_comments = $this->QuestionResponse_model->get_recent_for_question(
            (int)$question['id'],
            4
        );
        // Attach like/dislike counts and user votes
        $recent_comments = $this->attach_like_dislike_data($recent_comments);

        $data = array(
            // Reuse the same view variable so layout stays the same
            'today_question'     => $question,
            'previous_questions' => $previous_questions,
            'prev_current_page'  => $page,
            'prev_total_pages'   => $total_pages,
            'has_attempted'      => $has_attempted,
            'user_response'      => $user_response,
            'recent_comments'    => $recent_comments,
        );

        $this->load->view('home/index', $data);
    }

    /**
     * Handle submission of an answer for a question.
     */
    public function submit_answer()
    {
        $question_id = (int)$this->input->post('question_id');
        $selected_option = (int)$this->input->post('answer');
        $user_name = $this->input->post('user_name', true);
        $user_explanation = $this->input->post('user_explanation', true);
        $return_url = $this->input->post('return_url', true);

        if ($question_id <= 0 || $selected_option <= 0) {
            $this->session->set_flashdata('qod_error', 'Please select an answer before submitting.');
            redirect(!empty($return_url) ? $return_url : '/');
            return;
        }

        // Load the question and make sure it exists
        $question = $this->QuestionOfTheDay_model->get_by_id($question_id);
        if (empty($question) || $question['status'] !== 'active') {
            $this->session->set_flashdata('qod_error', 'Question not found or not available.');
            redirect(!empty($return_url) ? $return_url : '/');
            return;
        }

        // Check if this browser has already submitted for this question
        if ($this->QuestionResponse_model->has_submitted($question_id, $this->browser_id)) {
            $this->session->set_flashdata('qod_info', 'You have already attempted this question from this browser.');
            redirect(!empty($return_url) ? $return_url : 'question/' . $question_id);
            return;
        }

        $is_correct = ((int)$question['correct_answer'] === $selected_option) ? 1 : 0;

        $insert_data = array(
            'question_id'      => $question_id,
            'browser_id'       => $this->browser_id,
            'ip_address'       => $this->input->ip_address(),
            'user_agent'       => substr($this->input->user_agent(), 0, 255),
            'user_name'        => !empty($user_name) ? $user_name : null,
            'selected_option'  => $selected_option,
            'is_correct'       => $is_correct,
            'user_explanation' => $user_explanation,
        );

        $this->QuestionResponse_model->insert_response($insert_data);

        $message = $is_correct
            ? 'Correct! Well done.'
            : 'Incorrect. Check the explanation to learn more.';

        $this->session->set_flashdata('qod_success', $message);
        redirect(!empty($return_url) ? $return_url : 'question/' . $question_id);
    }

    /**
     * Get or create a pseudo-identifier for the browser using a cookie
     * combined with basic browser information.
     *
     * @return string
     */
    protected function get_or_create_browser_id()
    {
        $cookie_name = 'qod_browser_id';
        $browser_id = $this->input->cookie($cookie_name, true);

        if (!empty($browser_id)) {
            return $browser_id;
        }

        // Generate a reasonably unique token using browser info and time
        $raw = $this->input->ip_address() . '|' . $this->input->user_agent() . '|' . microtime(true) . '|' . mt_rand();
        $browser_id = sha1($raw);

        // Store for 1 year
        $cookie = array(
            'name'   => $cookie_name,
            'value'  => $browser_id,
            'expire' => 365 * 24 * 60 * 60,
            'secure' => false,
            'httponly' => true,
        );

        $this->input->set_cookie($cookie);

        return $browser_id;
    }

    /**
     * Attach like/dislike counts and user votes to comments.
     *
     * @param array $comments
     * @return array
     */
    protected function attach_like_dislike_data($comments)
    {
        if (empty($comments)) {
            return $comments;
        }

        $response_ids = array_column($comments, 'id');
        $counts_map = $this->CommentLike_model->get_counts_for_responses($response_ids);
        $user_votes = $this->CommentLike_model->get_user_votes_for_responses($response_ids, $this->browser_id);

        foreach ($comments as &$comment) {
            $rid = (int)$comment['id'];
            $comment['likes_count'] = isset($counts_map[$rid]) ? (int)$counts_map[$rid]['likes'] : 0;
            $comment['dislikes_count'] = isset($counts_map[$rid]) ? (int)$counts_map[$rid]['dislikes'] : 0;
            $comment['user_vote'] = isset($user_votes[$rid]) ? $user_votes[$rid] : null;
        }
        unset($comment);

        return $comments;
    }

    /**
     * AJAX endpoint to handle like/dislike actions.
     */
    public function toggle_comment_like()
    {
        // Set JSON header
        $this->output->set_content_type('application/json');

        $response_id = (int)$this->input->post('response_id');
        $action_type = $this->input->post('action_type'); // 'like' or 'dislike'

        if ($response_id <= 0 || !in_array($action_type, ['like', 'dislike'])) {
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'Invalid parameters'
            )));
            return;
        }

        // Verify response exists
        $response = $this->QuestionResponse_model->get_response_by_id($response_id);
        if (empty($response)) {
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'Comment not found'
            )));
            return;
        }

        // Toggle like/dislike
        $result = $this->CommentLike_model->toggle_like_dislike(
            $response_id,
            $this->browser_id,
            $action_type,
            $this->input->ip_address()
        );

        $this->output->set_output(json_encode($result));
    }
}

