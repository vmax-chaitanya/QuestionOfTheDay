<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommentLike_model extends CI_Model
{
    /**
     * Toggle like/dislike for a comment.
     * If user already liked, remove the like.
     * If user already disliked, remove the dislike.
     * If user clicks like while having a dislike, switch to like.
     * If user clicks dislike while having a like, switch to dislike.
     *
     * @param int $response_id
     * @param string $browser_id
     * @param string $action_type 'like' or 'dislike'
     * @param string $ip_address
     * @return array ['success' => bool, 'action' => string, 'likes' => int, 'dislikes' => int]
     */
    public function toggle_like_dislike($response_id, $browser_id, $action_type, $ip_address = null)
    {
        if (!in_array($action_type, ['like', 'dislike'])) {
            return array(
                'success' => false,
                'message' => 'Invalid action type'
            );
        }

        // Check if user already has a vote for this response
        $this->db->from('comment_likes');
        $this->db->where('response_id', (int)$response_id);
        $this->db->where('browser_id', $browser_id);
        $existing = $this->db->get()->row_array();

        if (!empty($existing)) {
            // User already voted
            if ($existing['action_type'] === $action_type) {
                // Same action - remove the vote
                $this->db->where('id', $existing['id']);
                $this->db->delete('comment_likes');
                $action = 'removed';
            } else {
                // Different action - update to new action
                $this->db->where('id', $existing['id']);
                $this->db->update('comment_likes', array(
                    'action_type' => $action_type,
                    'ip_address' => $ip_address,
                    'updated_at' => date('Y-m-d H:i:s')
                ));
                $action = 'updated';
            }
        } else {
            // New vote
            $this->db->insert('comment_likes', array(
                'response_id' => (int)$response_id,
                'browser_id' => $browser_id,
                'action_type' => $action_type,
                'ip_address' => $ip_address
            ));
            $action = 'added';
        }

        // Get updated counts
        $counts = $this->get_counts($response_id);

        return array(
            'success' => true,
            'action' => $action,
            'likes' => $counts['likes'],
            'dislikes' => $counts['dislikes'],
            'user_action' => $action === 'removed' ? null : $action_type
        );
    }

    /**
     * Get like and dislike counts for a response.
     *
     * @param int $response_id
     * @return array ['likes' => int, 'dislikes' => int]
     */
    public function get_counts($response_id)
    {
        $this->db->select('action_type, COUNT(*) as count');
        $this->db->from('comment_likes');
        $this->db->where('response_id', (int)$response_id);
        $this->db->group_by('action_type');
        $query = $this->db->get();

        $likes = 0;
        $dislikes = 0;

        foreach ($query->result_array() as $row) {
            if ($row['action_type'] === 'like') {
                $likes = (int)$row['count'];
            } elseif ($row['action_type'] === 'dislike') {
                $dislikes = (int)$row['count'];
            }
        }

        return array(
            'likes' => $likes,
            'dislikes' => $dislikes
        );
    }

    /**
     * Get like/dislike counts for multiple responses.
     *
     * @param array $response_ids
     * @return array [response_id => ['likes' => int, 'dislikes' => int]]
     */
    public function get_counts_for_responses($response_ids)
    {
        if (empty($response_ids) || !is_array($response_ids)) {
            return array();
        }

        $ids = array_map('intval', $response_ids);

        $this->db->select('response_id, action_type, COUNT(*) as count');
        $this->db->from('comment_likes');
        $this->db->where_in('response_id', $ids);
        $this->db->group_by(array('response_id', 'action_type'));
        $query = $this->db->get();

        $result = array();
        foreach ($ids as $id) {
            $result[$id] = array('likes' => 0, 'dislikes' => 0);
        }

        foreach ($query->result_array() as $row) {
            $rid = (int)$row['response_id'];
            if ($row['action_type'] === 'like') {
                $result[$rid]['likes'] = (int)$row['count'];
            } elseif ($row['action_type'] === 'dislike') {
                $result[$rid]['dislikes'] = (int)$row['count'];
            }
        }

        return $result;
    }

    /**
     * Get user's current vote for a response.
     *
     * @param int $response_id
     * @param string $browser_id
     * @return string|null 'like', 'dislike', or null
     */
    public function get_user_vote($response_id, $browser_id)
    {
        $this->db->from('comment_likes');
        $this->db->where('response_id', (int)$response_id);
        $this->db->where('browser_id', $browser_id);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['action_type'];
        }

        return null;
    }

    /**
     * Get user's votes for multiple responses.
     *
     * @param array $response_ids
     * @param string $browser_id
     * @return array [response_id => 'like'|'dislike'|null]
     */
    public function get_user_votes_for_responses($response_ids, $browser_id)
    {
        if (empty($response_ids) || !is_array($response_ids)) {
            return array();
        }

        $ids = array_map('intval', $response_ids);

        $this->db->select('response_id, action_type');
        $this->db->from('comment_likes');
        $this->db->where_in('response_id', $ids);
        $this->db->where('browser_id', $browser_id);
        $query = $this->db->get();

        $result = array();
        foreach ($ids as $id) {
            $result[$id] = null;
        }

        foreach ($query->result_array() as $row) {
            $result[(int)$row['response_id']] = $row['action_type'];
        }

        return $result;
    }
}
