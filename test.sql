/*
Navicat MySQL Data Transfer

Source Server         : l-localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2014-09-11 18:15:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tm_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `tm_auth_group`;
CREATE TABLE `tm_auth_group` (
  `group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tm_auth_group
-- ----------------------------
INSERT INTO `tm_auth_group` VALUES ('1', '财务部', '1', '1,2,4');
INSERT INTO `tm_auth_group` VALUES ('2', '人事部', '1', '1');
INSERT INTO `tm_auth_group` VALUES ('3', '技术部', '1', '1,2,3,4');

-- ----------------------------
-- Table structure for tm_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `tm_auth_group_access`;
CREATE TABLE `tm_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tm_auth_group_access
-- ----------------------------
INSERT INTO `tm_auth_group_access` VALUES ('1', '2');
INSERT INTO `tm_auth_group_access` VALUES ('2', '1');
INSERT INTO `tm_auth_group_access` VALUES ('3', '2');

-- ----------------------------
-- Table structure for tm_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `tm_auth_rule`;
CREATE TABLE `tm_auth_rule` (
  `rule_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`rule_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tm_auth_rule
-- ----------------------------
INSERT INTO `tm_auth_rule` VALUES ('1', 'do1', 'somethink1', '1', '1', '');
INSERT INTO `tm_auth_rule` VALUES ('2', 'do2', 'somethink2', '1', '1', '');
INSERT INTO `tm_auth_rule` VALUES ('3', 'do3', 'somethink3', '1', '1', '');
INSERT INTO `tm_auth_rule` VALUES ('4', 'show_button1', 'show', '1', '1', '');
INSERT INTO `tm_auth_rule` VALUES ('5', 'show_button122', 'show', '1', '1', '');

-- ----------------------------
-- Table structure for tm_menu
-- ----------------------------
DROP TABLE IF EXISTS `tm_menu`;
CREATE TABLE `tm_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET latin1 NOT NULL,
  `url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `group` varchar(80) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `tip` varchar(255) NOT NULL,
  `hide` int(2) NOT NULL DEFAULT '1',
  `is_dev` int(2) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tm_menu
-- ----------------------------
INSERT INTO `tm_menu` VALUES ('1', 'system', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('2', 'a', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('3', 'b', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('4', 'c', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('5', 'd', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('6', 'e', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('7', 'f', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('8', 's', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('9', 'w', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('10', 'v', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('11', 'c', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('12', 'x', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('13', 'a', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('14', 'd', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('15', 'h', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('16', 'y', '/system/index', '', '1', '', '1', '0');
INSERT INTO `tm_menu` VALUES ('17', 'z', '/system/index', '', '1', '', '1', '0');

-- ----------------------------
-- Table structure for tm_user
-- ----------------------------
DROP TABLE IF EXISTS `tm_user`;
CREATE TABLE `tm_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `password` varchar(32) CHARACTER SET latin1 DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` varchar(4) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tm_user
-- ----------------------------
INSERT INTO `tm_user` VALUES ('1', 'andy', '81dc9bdb52d04dc20036dbd8313ed055', '21', '2');
INSERT INTO `tm_user` VALUES ('2', 'job', '81dc9bdb52d04dc20036dbd8313ed055', '32', '1');
INSERT INTO `tm_user` VALUES ('3', 'kate', '81dc9bdb52d04dc20036dbd8313ed055', '23', '2');
INSERT INTO `tm_user` VALUES ('4', 'andrew', '81dc9bdb52d04dc20036dbd8313ed055', '44', '1');
INSERT INTO `tm_user` VALUES ('6', 'makie', '81dc9bdb52d04dc20036dbd8313ed055', '56', '1');
INSERT INTO `tm_user` VALUES ('7', 'bob', '81dc9bdb52d04dc20036dbd8313ed055', '23', '1');
INSERT INTO `tm_user` VALUES ('8', 'lucy', '81dc9bdb52d04dc20036dbd8313ed055', '45', '1');
INSERT INTO `tm_user` VALUES ('9', 'may', '81dc9bdb52d04dc20036dbd8313ed055', '67', '2');
INSERT INTO `tm_user` VALUES ('13', 'sans', '81dc9bdb52d04dc20036dbd8313ed055', '34', '1');
INSERT INTO `tm_user` VALUES ('14', 'luckly', '81dc9bdb52d04dc20036dbd8313ed055', '23', '1');
INSERT INTO `tm_user` VALUES ('15', 'coco', '81dc9bdb52d04dc20036dbd8313ed055', '76', '2');
INSERT INTO `tm_user` VALUES ('16', 'marous', '81dc9bdb52d04dc20036dbd8313ed055', '45', '1');
INSERT INTO `tm_user` VALUES ('17', 'tank', '81dc9bdb52d04dc20036dbd8313ed055', '21', '2');
INSERT INTO `tm_user` VALUES ('18', 'nance', '81dc9bdb52d04dc20036dbd8313ed055', '43', '2');
INSERT INTO `tm_user` VALUES ('19', 'steve', '81dc9bdb52d04dc20036dbd8313ed055', '45', '1');
INSERT INTO `tm_user` VALUES ('20', 'nora', '81dc9bdb52d04dc20036dbd8313ed055', '32', '2');
INSERT INTO `tm_user` VALUES ('28', 'elev', '81dc9bdb52d04dc20036dbd8313ed055', '43', '2');
INSERT INTO `tm_user` VALUES ('29', 'youwen', '81dc9bdb52d04dc20036dbd8313ed055', '26', '1');
INSERT INTO `tm_user` VALUES ('30', 'test', '81dc9bdb52d04dc20036dbd8313ed055', '74', '1');
INSERT INTO `tm_user` VALUES ('31', 'rayo', '81dc9bdb52d04dc20036dbd8313ed055', '21', '2');
INSERT INTO `tm_user` VALUES ('32', 'yeild', '81dc9bdb52d04dc20036dbd8313ed055', '32', '1');
INSERT INTO `tm_user` VALUES ('33', 'tera', '81dc9bdb52d04dc20036dbd8313ed055', '38', '1');
INSERT INTO `tm_user` VALUES ('34', 'botlle', '81dc9bdb52d04dc20036dbd8313ed055', '23', '2');
INSERT INTO `tm_user` VALUES ('35', 'smole', '81dc9bdb52d04dc20036dbd8313ed055', '45', '1');
INSERT INTO `tm_user` VALUES ('36', 'goldren', '81dc9bdb52d04dc20036dbd8313ed055', '28', '1');
INSERT INTO `tm_user` VALUES ('37', 'sliver', '81dc9bdb52d04dc20036dbd8313ed055', '18', '1');
INSERT INTO `tm_user` VALUES ('38', 'alut', '81dc9bdb52d04dc20036dbd8313ed055', '29', '2');
INSERT INTO `tm_user` VALUES ('39', 'lee', '81dc9bdb52d04dc20036dbd8313ed055', '43', '1');
INSERT INTO `tm_user` VALUES ('40', 'dig', '81dc9bdb52d04dc20036dbd8313ed055', '77', '2');
