CREATE DATABASE `upload`;

USE `upload`;

CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_name` VARCHAR(50) NOT NULL,
  `user_username` VARCHAR(25) NOT NULL,
  `user_email` VARCHAR(128) NOT NULL,
  `user_password` VARCHAR(128) NOT NULL,
  `user_joined` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `user_profile_picture` VARCHAR(255) DEFAULT 'default.png'
) ENGINE=INNODB;

CREATE TABLE `users_session` (
  `session_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `session_hash` VARCHAR(256) NOT NULL
) ENGINE=INNODB;

CREATE TABLE `follows` (
  `follow_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `follow_user` INT NOT NULL,
  `follow_following` INT NOT NULL
) ENGINE=INNODB;

CREATE TABLE `uploads` (
  `upload_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `upload_file` VARCHAR(255) NOT NULL,
  `upload_title` VARCHAR(150) NOT NULL,
  `upload_description` VARCHAR(500),
  `upload_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `upload_by` INT NOT NULL,
  `upload_views` INT DEFAULT 0
) ENGINE=INNODB;

CREATE TABLE `uploads_ratings` (
  `rating_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `rating_user` INT NOT NULL,
  `rating_upload` INT NOT NULL,
  `rating_number` INT NOT NULL
) ENGINE=INNODB;

CREATE TABLE `favorites` (
  `favorite_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `favorite_user` INT NOT NULL,
  `favorite_upload` INT NOT NULL
) ENGINE=INNODB;

CREATE TABLE `comments` (
  `comment_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `comment_text` VARCHAR(255) NOT NULL,
  `comment_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `comment_upload` INT NOT NULL,
  `comment_by` INT NOT NULL
) ENGINE=INNODB;