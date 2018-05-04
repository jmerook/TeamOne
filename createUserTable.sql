-- You just need to "import" this into your phpMyAdmin
--
--
--
-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 23, 2018 at 12:46 AM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `clueless`
--

-- --------------------------------------------------------

--create the user table
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(65) NOT NULL,
  `lastName` varchar(65) NOT NULL,
  `userName` varchar(65) NOT NULL,
  `password` varchar(65) NOT NULL,
  `game` int(11) DEFAULT NULL COMMENT 'this is the column to show what game the user is in',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8




--create the gameboard table
CREATE TABLE `game_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Game ID',
  `gameName` varchar(45) NOT NULL,
  `secretEnvelope` varchar(45) NOT NULL COMMENT 'holds the FK to the secret envelope table to show which secret envelop goes with with game\n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `secretEnvelop_UNIQUE` (`secretEnvelope`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8




INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`) VALUES (0,'Jacob','Merook','jmerook2','password',40);
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`) VALUES (1,'Jacob','Merook','jacobmerook@gmail.com','password',36);
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`) VALUES (2,'Jacob','Merook','jmerook1','password',36);
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`) VALUES (12,'Jacob','Merook','merook','password',36);
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`) VALUES (13,'Jacob','Merook','merook1','password',NULL);

INSERT INTO `game_board` (`id`,`gameName`,`secretEnvelope`) VALUES (36,'test','50');
INSERT INTO `game_board` (`id`,`gameName`,`secretEnvelope`) VALUES (40,'test1','2');


