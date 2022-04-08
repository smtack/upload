CREATE DATABASE `upload`;

USE `upload`;

CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(50) NOT NULL,
  `user_username` VARCHAR(25) NOT NULL,
  `user_email` VARCHAR(128) NOT NULL,
  `user_password` VARCHAR(128) NOT NULL,
  `user_joined` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `user_profile_picture` VARCHAR(255) DEFAULT 'default.png',
  PRIMARY KEY (user_id)
) ENGINE=INNODB;

CREATE TABLE `users_session` (
  `session_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `session_hash` VARCHAR(256) NOT NULL,
  PRIMARY KEY (session_id)
) ENGINE=INNODB;

CREATE TABLE `follows` (
  `follow_id` INT(11) NOT NULL AUTO_INCREMENT,
  `follow_user` INT(11) NOT NULL,
  `follow_following` INT(11) NOT NULL,
  PRIMARY KEY (follow_id)
) ENGINE=INNODB;

CREATE TABLE `uploads` (
  `upload_id` INT(11) NOT NULL AUTO_INCREMENT,
  `upload_file` VARCHAR(255) NOT NULL,
  `upload_title` VARCHAR(150) NOT NULL,
  `upload_description` VARCHAR(500),
  `upload_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `upload_by` INT(11) NOT NULL,
  `upload_views` INT(11) DEFAULT 0,
  PRIMARY KEY (upload_id)
) ENGINE=INNODB;

CREATE TABLE `favorites` (
  `favorite_id` INT(11) NOT NULL AUTO_INCREMENT,
  `favorite_user` INT(11) NOT NULL,
  `favorite_upload` INT(11) NOT NULL,
  PRIMARY KEY (favorite_id)
) ENGINE=INNODB;

CREATE TABLE `comments` (
  `comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_text` VARCHAR(255) NOT NULL,
  `comment_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `comment_upload` INT(11) NOT NULL,
  `comment_by` INT(11) NOT NULL,
  PRIMARY KEY (comment_id)
) ENGINE=INNODB;