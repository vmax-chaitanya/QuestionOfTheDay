-- SQL Query to add video fields to question_of_the_day table
ALTER TABLE `question_of_the_day` 
ADD COLUMN `video_type` enum('upload','youtube','none') NOT NULL DEFAULT 'none' AFTER `explanation`,
ADD COLUMN `video_file` varchar(255) DEFAULT NULL AFTER `video_type`,
ADD COLUMN `youtube_link` varchar(500) DEFAULT NULL AFTER `video_file`;
