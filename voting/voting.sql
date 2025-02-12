-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 07, 2020 at 09:42 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS voting;
USE voting;

-- Table structure for table `candidates`

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `can_id` int(11) NOT NULL,
  `can_name` varchar(100) NOT NULL,
  `can_party_name` varchar(100) NOT NULL,
  `can_image` varchar(100) NOT NULL,
  `can_party_symbol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `candidates` (`id`, `can_id`, `can_name`, `can_party_name`, `can_image`, `can_party_symbol`) VALUES
(6, 1, 'xyz','BJP' ,'15915075312020-06-07_1.png', '15915075312020-06-07_noimage.jpg'),
(7, 2, 'abc', 'Congress','15915155572020-06-07_3.png', '15915155572020-06-07_admin.jpg');

-- Table structure for table `superadmin`

CREATE TABLE `superadmin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `superadmin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Tejas', 'admin', 'admin');

-- Table structure for table `voters`

CREATE TABLE `voters` (
  `id` int(11) NOT NULL,
  `voters_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `age` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `voters` (`id`, `voters_id`, `name`, `password`, `age`) VALUES
(1, '101', 'Tejas', '101-2020', 25);

-- Table structure for table `votes`

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `voter_id` varchar(100) NOT NULL,
  `can_id` varchar(100) NOT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Indexes for tables

ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `voters`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for tables

ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `superadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `voters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Triggers

DELIMITER $$

-- Trigger to prevent multiple votes by the same voter
CREATE TRIGGER before_insert_one_vote
BEFORE INSERT ON votes
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM votes
        WHERE voter_id = NEW.voter_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: This voter has already voted.';
    END IF;
END$$

-- Trigger to prevent underage voters
CREATE TRIGGER prevent_underage_voters
BEFORE INSERT ON voters
FOR EACH ROW
BEGIN
    IF NEW.age < 18 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Voter age must be 18 or above.';
    END IF;
END$$

DELIMITER ;

COMMIT;


CREATE TABLE `positions` (
  `can_id` int(11) NOT NULL ,
  `position_name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `positions` ADD CONSTRAINT `fk_can_id` FOREIGN KEY (`can_id`) REFERENCES `candidates`(`id`) ON DELETE CASCADE; 

ALTER TABLE `positions`
ADD UNIQUE KEY `unique_candidate_position` (`can_id`, `position_name`);