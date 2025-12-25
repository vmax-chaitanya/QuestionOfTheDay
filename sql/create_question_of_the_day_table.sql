-- SQL Query to create question_of_the_day table
CREATE TABLE IF NOT EXISTS `question_of_the_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_description` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `question_date` date NOT NULL,
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'draft',
  `option_1` text DEFAULT NULL,
  `option_2` text DEFAULT NULL,
  `option_3` text DEFAULT NULL,
  `option_4` text DEFAULT NULL,
  `correct_answer` int(11) DEFAULT NULL COMMENT '1, 2, 3, or 4',
  `explanation` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_question_date` (`question_date`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
