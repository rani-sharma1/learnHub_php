CREATE DATABASE IF NOT EXISTS `learnhub` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `learnhub`;
CREATE TABLE `learner` (
  `learner_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `contact_no` varchar(20),
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`learner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;