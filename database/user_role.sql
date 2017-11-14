/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100110
 Source Host           : localhost
 Source Database       : webchat

 Target Server Type    : MySQL
 Target Server Version : 100110
 File Encoding         : utf-8

 Date: 11/14/2017 17:33:21 PM
*/

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `user_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `roleName` varchar(255) NOT NULL COMMENT '角色名称',
  `describe` varchar(255) DEFAULT NULL COMMENT '角色描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '角色状态(默认 1:有效角色)',
  `createdTime` datetime NOT NULL COMMENT '创建时间(时间戳格式)',
  `updatedTime` datetime NOT NULL COMMENT '更新时间(时间戳格式)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
