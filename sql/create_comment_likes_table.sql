-- SQL Query to create comment_likes table
-- This table stores likes and dislikes for each comment (question_response)

CREATE TABLE IF NOT EXISTS `comment_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `response_id` int(11) NOT NULL COMMENT 'References question_responses.id',
  `browser_id` varchar(64) NOT NULL COMMENT 'Browser identifier to prevent duplicate votes',
  `action_type` enum('like','dislike') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_response_browser` (`response_id`, `browser_id`),
  KEY `idx_response_id` (`response_id`),
  KEY `idx_action_type` (`action_type`),
  CONSTRAINT `fk_cl_response` FOREIGN KEY (`response_id`) REFERENCES `question_responses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
