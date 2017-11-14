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

 Date: 11/14/2017 17:33:59 PM
*/

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `user_admin`
-- ----------------------------
DROP TABLE IF EXISTS `user_admin`;
CREATE TABLE `user_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL COMMENT '管理员名称',
  `realName` varchar(255) NOT NULL COMMENT '真实名称',
  `headImage` varchar(255) DEFAULT NULL COMMENT '用户头像',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `phoneNumber` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `password` varchar(20) NOT NULL COMMENT '密码(md5加密)',
  `roleId` int(11) NOT NULL COMMENT '角色Id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '管理员状态(默认 1：有效用户)',
  `createdTime` datetime NOT NULL COMMENT '创建时间(时间戳格式)',
  `updatedTime` datetime NOT NULL COMMENT '更新时间(时间戳格式)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user_admin`
-- ----------------------------
BEGIN;
INSERT INTO `user_admin` VALUES ('1', 'root', '超级管理员', null, null, null, '123456', '0', '1', '2017-11-03 18:10:03', '2017-11-03 18:10:05');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
