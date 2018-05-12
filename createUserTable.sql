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

#create the user table
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(65) NOT NULL,
  `lastName` varchar(65) NOT NULL,
  `userName` varchar(65) NOT NULL,
  `password` varchar(65) NOT NULL,
  `game` int(11) DEFAULT NULL COMMENT 'this is the column to show what game the user is in',
  `characterNumber` int(11) DEFAULT NULL COMMENT 'this is the character they are playing in the game instance',
  `isTurn` varchar(65) DEFAULT NULL COMMENT 'this flag will tell if it is currently that users turn or not. (true = their turn, false = not their turn)',
  `isEliminated` varchar(65) DEFAULT NULL COMMENT 'this flag will tell if user is eliminated or not. (true = user is eliminated, false = not eliminated)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8




#create the gameboard table
CREATE TABLE `game_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Game ID',
  `gameName` varchar(45) NOT NULL,
  `secretEnvelope` varchar(45) NOT NULL COMMENT 'holds the FK to the secret envelope table to show which secret envelop goes with with game\n',
  `isOver` varchar(45) NOT NULL COMMENT 'this flag will determine if certain game is over\n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `secretEnvelop_UNIQUE` (`secretEnvelope`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;


#create the weapon table
CREATE TABLE `weapon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weapon` varchar(45) NOT NULL COMMENT 'text of the crime',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#create the suspect table
CREATE TABLE `clueless`.`suspect` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `suspect` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));

CREATE TABLE `clueless`.`room` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `room` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));

CREATE TABLE `clueless`.`envelope` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `suspect` VARCHAR(45) NOT NULL,
  `room` VARCHAR(45) NOT NULL,
  `weapon` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));

CREATE TABLE `game_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'this will be the pointer, and how we reference the row ',
  `rowNumber` varchar(45) NOT NULL COMMENT 'row fo the game board',
  `columnNumber` varchar(45) NOT NULL COMMENT 'column of the game board',
  `roomName` varchar(45) NOT NULL COMMENT 'name of the room (or hallway) of the position on the game board\n',
  `game_board` int(11) NOT NULL COMMENT 'this is the ID that hold the association with the actual game instance going on',
  `occupant` varchar(45) DEFAULT NULL COMMENT 'this will be updated each turn when a user moves in or out of the location',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8



INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`,`isEliminated`) VALUES (0,'Jacob','Merook','jmerook2','password',40,false );
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`,`isEliminated`) VALUES (1,'Jacob','Merook','jacobmerook@gmail.com','password',36,false);
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`,`isEliminated`) VALUES (2,'Jacob','Merook','jmerook1','password',36,false);
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`,`isEliminated`) VALUES (12,'Jacob','Merook','merook','password',36,false);
INSERT INTO `user` (`id`,`firstName`,`lastName`,`userName`,`password`,`game`,`isEliminated`) VALUES (13,'Jacob','Merook','merook1','password',NULL,false);

INSERT INTO `game_board` (`id`,`gameName`,`secretEnvelope`,isOver) VALUES (36,'test','50',FALSE );
INSERT INTO `game_board` (`id`,`gameName`,`secretEnvelope`,isOver) VALUES (40,'test1','2',FALSE );

INSERT INTO `clueless`.`weapon` (`weapon`) VALUES ('Rope');
INSERT INTO `clueless`.`weapon` (`weapon`) VALUES ('Lead Pipe');
INSERT INTO `clueless`.`weapon` (`weapon`) VALUES ('Knife');
INSERT INTO `clueless`.`weapon` (`weapon`) VALUES ('Wrench');
INSERT INTO `clueless`.`weapon` (`weapon`) VALUES ('Candlestick');
INSERT INTO `clueless`.`weapon` (`weapon`) VALUES ('Revolver');


INSERT INTO `clueless`.`suspect` (`suspect`) VALUES ('Colonel Mustard');
INSERT INTO `clueless`.`suspect` (`suspect`) VALUES ('Miss Scarlet');
INSERT INTO `clueless`.`suspect` (`suspect`) VALUES ('Professor Plum');
INSERT INTO `clueless`.`suspect` (`suspect`) VALUES ('Mr. Green');
INSERT INTO `clueless`.`suspect` (`suspect`) VALUES ('Mrs. White');
INSERT INTO `clueless`.`suspect` (`suspect`) VALUES ('Mrs. Peacock');


INSERT INTO `clueless`.`room` (`room`) VALUES ('Study');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Hall');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Lounge');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Dining Room');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Kitchen');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Ballroom');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Conservatory');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Library');
INSERT INTO `clueless`.`room` (`room`) VALUES ('Billard Room');


INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('1', '1', 'Study');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('1', '2', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('1', '3', 'Hall');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('1', '4', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('1', '5', 'Lounge');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('2', '1', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('2', '3', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('2', '5', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('3', '1', 'Library');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('3', '2', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('3', '3', 'Billard Room');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('3', '4', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('3', '5', 'Dining Room');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('4', '1', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('4', '3', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('4', '5', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('5', '1', 'Conservatory');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('5', '2', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('5', '3', 'Ballroom');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('5', '4', 'hallway');
INSERT INTO `clueless`.`game_map` (`rowNumber`, `columnNumber`, `roomName`) VALUES ('5', '5', 'Kitchen');




