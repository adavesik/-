/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.1.148
 Source Server Type    : MySQL
 Source Server Version : 50550
 Source Host           : 192.168.1.148:3306
 Source Schema         : clerk

 Target Server Type    : MySQL
 Target Server Version : 50550
 File Encoding         : 65001

 Date: 18/11/2019 16:48:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for assigners
-- ----------------------------
DROP TABLE IF EXISTS `assigners`;
CREATE TABLE `assigners`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `assigner_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `catId` int(9) NOT NULL AUTO_INCREMENT,
  `userId` int(5) NOT NULL DEFAULT 0,
  `catName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `catDesc` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `catDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isActive` int(1) NOT NULL DEFAULT 0,
  `lastUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`catId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for events
-- ----------------------------
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events`  (
  `eventId` int(5) NOT NULL AUTO_INCREMENT,
  `userId` int(5) NOT NULL DEFAULT 0,
  `startDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `endDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `eventTitle` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `eventDesc` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `eventColor` varchar(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '#87b633',
  `isTask` int(1) NOT NULL DEFAULT 0,
  `lastUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`eventId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for priority
-- ----------------------------
DROP TABLE IF EXISTS `priority`;
CREATE TABLE `priority`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sitesettings
-- ----------------------------
DROP TABLE IF EXISTS `sitesettings`;
CREATE TABLE `sitesettings`  (
  `installUrl` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `localization` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'en',
  `siteName` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `siteEmail` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `allowRegistrations` int(1) NOT NULL DEFAULT 1,
  `enableWeather` int(1) NOT NULL DEFAULT 1,
  `enableCalendar` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`installUrl`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tasknotes
-- ----------------------------
DROP TABLE IF EXISTS `tasknotes`;
CREATE TABLE `tasknotes`  (
  `noteId` int(5) NOT NULL AUTO_INCREMENT,
  `taskId` int(5) NOT NULL DEFAULT 0,
  `catId` int(5) NOT NULL DEFAULT 0,
  `userId` int(5) NOT NULL,
  `taskNote` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `noteDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`noteId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tasks
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks`  (
  `taskId` int(5) NOT NULL AUTO_INCREMENT,
  `catId` int(5) NOT NULL DEFAULT 0,
  `userId` int(5) NOT NULL,
  `assignerId` int(5) NOT NULL,
  `taskTitle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `taskDesc` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `taskPriority` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'Normal',
  `taskStatus` int(10) NOT NULL,
  `taskPercent` int(3) NOT NULL DEFAULT 0,
  `taskStart` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `taskDue` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isClosed` int(1) NOT NULL DEFAULT 0,
  `dateClosed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `taskDeadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`taskId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `userId` int(9) NOT NULL AUTO_INCREMENT,
  `isAdmin` int(1) NOT NULL DEFAULT 0,
  `userEmail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `userFirst` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `userLast` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `joinDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `weatherLoc` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'Washington, DC',
  `recEmails` int(1) NOT NULL DEFAULT 0,
  `userNotes` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `isActive` int(1) NOT NULL DEFAULT 0,
  `hash` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lastVisited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`userId`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for whattodo
-- ----------------------------
DROP TABLE IF EXISTS `whattodo`;
CREATE TABLE `whattodo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `todo_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
