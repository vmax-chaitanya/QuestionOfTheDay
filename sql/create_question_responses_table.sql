-- SQL Query to create question_responses table

CREATE TABLE IF NOT EXISTS `question_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `browser_id` varchar(64) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `selected_option` tinyint(1) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `user_explanation` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_question_browser` (`question_id`, `browser_id`),
  CONSTRAINT `fk_qr_question` FOREIGN KEY (`question_id`) REFERENCES `question_of_the_day` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- If the table already exists without user_name, run:
-- ALTER TABLE `question_responses` ADD `user_name` varchar(100) DEFAULT NULL AFTER `user_agent`;
