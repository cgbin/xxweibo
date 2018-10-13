/*
Navicat MySQL Data Transfer

Source Server         : user
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : weibo

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-11-09 13:45:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for hd_admin
-- ----------------------------
DROP TABLE IF EXISTS `hd_admin`;
CREATE TABLE `hd_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` char(20) NOT NULL DEFAULT '',
  `lock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hd_admin
-- ----------------------------
INSERT INTO `hd_admin` VALUES ('1', 'admin', 'd89f33b18ba2a74cd38499e587cb9dcd', '1507898381', '0.0.0.0', '0', '0');

-- ----------------------------
-- Table structure for hd_atme
-- ----------------------------
DROP TABLE IF EXISTS `hd_atme`;
CREATE TABLE `hd_atme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL COMMENT '提到我的微博ID',
  `uid` int(11) NOT NULL COMMENT '所属用户ID',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `wid` (`wid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='@提到我的微博';

-- ----------------------------
-- Records of hd_atme
-- ----------------------------

-- ----------------------------
-- Table structure for hd_comment
-- ----------------------------
DROP TABLE IF EXISTS `hd_comment`;
CREATE TABLE `hd_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '评论内容',
  `time` int(10) unsigned NOT NULL COMMENT '评论时间',
  `uid` int(11) NOT NULL COMMENT '评论用户的ID',
  `wid` int(11) NOT NULL COMMENT '所属微博ID',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `wid` (`wid`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='微博评论表';

-- ----------------------------
-- Records of hd_comment
-- ----------------------------

-- ----------------------------
-- Table structure for hd_follow
-- ----------------------------
DROP TABLE IF EXISTS `hd_follow`;
CREATE TABLE `hd_follow` (
  `follow` int(10) unsigned NOT NULL COMMENT '关注用户的ID',
  `fans` int(10) unsigned NOT NULL COMMENT '粉丝用户ID',
  `gid` int(11) NOT NULL COMMENT '所属关注分组ID',
  KEY `follow` (`follow`),
  KEY `fans` (`fans`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关注与粉丝表';

-- ----------------------------
-- Records of hd_follow
-- ----------------------------

-- ----------------------------
-- Table structure for hd_group
-- ----------------------------
DROP TABLE IF EXISTS `hd_group`;
CREATE TABLE `hd_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '' COMMENT '分组名称',
  `uid` int(11) NOT NULL COMMENT '所属用户的ID',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='关注分组表';

-- ----------------------------
-- Records of hd_group
-- ----------------------------

-- ----------------------------
-- Table structure for hd_keep
-- ----------------------------
DROP TABLE IF EXISTS `hd_keep`;
CREATE TABLE `hd_keep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '收藏用户的ID',
  `time` int(10) unsigned NOT NULL COMMENT '收藏时间',
  `wid` int(11) NOT NULL COMMENT '收藏微博的ID',
  PRIMARY KEY (`id`),
  KEY `wid` (`wid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='收藏表';

-- ----------------------------
-- Records of hd_keep
-- ----------------------------

-- ----------------------------
-- Table structure for hd_letter
-- ----------------------------
DROP TABLE IF EXISTS `hd_letter`;
CREATE TABLE `hd_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL COMMENT '发私用户ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '私信内容',
  `time` int(10) unsigned NOT NULL COMMENT '私信发送时间',
  `uid` int(11) NOT NULL COMMENT '所属用户ID（收信人）',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='私信表';

-- ----------------------------
-- Records of hd_letter
-- ----------------------------

-- ----------------------------
-- Table structure for hd_picture
-- ----------------------------
DROP TABLE IF EXISTS `hd_picture`;
CREATE TABLE `hd_picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mini` varchar(60) NOT NULL DEFAULT '' COMMENT '小图',
  `medium` varchar(60) NOT NULL DEFAULT '' COMMENT '中图',
  `max` varchar(60) NOT NULL DEFAULT '' COMMENT '大图',
  `wid` int(11) NOT NULL COMMENT '所属微博ID',
  PRIMARY KEY (`id`),
  KEY `wid` (`wid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='微博配图表';

-- ----------------------------
-- Records of hd_picture
-- ----------------------------
INSERT INTO `hd_picture` VALUES ('5', '20171016/mini_59e4535eeeaef.png', '20171016/medium_59e4535eeeaef.png', '20171016/max_59e4535eeeaef.png', '41');

-- ----------------------------
-- Table structure for hd_user
-- ----------------------------
DROP TABLE IF EXISTS `hd_user`;
CREATE TABLE `hd_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` char(20) NOT NULL DEFAULT '' COMMENT '账号',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `registime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `lock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否锁定（0：否，1：是）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of hd_user
-- ----------------------------
INSERT INTO `hd_user` VALUES ('10', 'admin11', '21232f297a57a5a743894a0e4a801fc3', '1507909348', '0');
INSERT INTO `hd_user` VALUES ('11', 'admin2', '21232f297a57a5a743894a0e4a801fc3', '1507913117', '0');
INSERT INTO `hd_user` VALUES ('12', 'admin111', '21232f297a57a5a743894a0e4a801fc3', '1510206186', '0');

-- ----------------------------
-- Table structure for hd_userinfo
-- ----------------------------
DROP TABLE IF EXISTS `hd_userinfo`;
CREATE TABLE `hd_userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `truename` varchar(45) DEFAULT NULL COMMENT '真实名称',
  `sex` enum('男','女') NOT NULL DEFAULT '男' COMMENT '性别',
  `location` varchar(45) NOT NULL DEFAULT '' COMMENT '所在地',
  `constellation` char(10) NOT NULL DEFAULT '' COMMENT '星座',
  `intro` varchar(100) NOT NULL DEFAULT '' COMMENT '一句话介绍自己',
  `face50` varchar(60) NOT NULL DEFAULT '' COMMENT '50*50头像',
  `face80` varchar(60) NOT NULL DEFAULT '' COMMENT '80*80头像',
  `face180` varchar(60) NOT NULL DEFAULT '' COMMENT '180*180头像',
  `style` varchar(45) NOT NULL DEFAULT 'default' COMMENT '个性模版',
  `follow` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `fans` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `weibo` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '微博数',
  `uid` int(11) NOT NULL COMMENT '所属用户ID',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of hd_userinfo
-- ----------------------------
INSERT INTO `hd_userinfo` VALUES ('10', 'aaa', null, '男', '', '', '', '20171014/mini_59e0ed677aeb3.gif', '20171014/medium_59e0ed677aeb3.gif', '20171014/max_59e0ed677aeb3.gif', 'default', '0', '0', '1', '10');
INSERT INTO `hd_userinfo` VALUES ('11', '222', null, '男', '', '', '', '20171016/mini_59e44f220024e.png', '20171016/medium_59e44f220024e.png', '20171016/max_59e44f220024e.png', 'style2', '0', '0', '8', '11');
INSERT INTO `hd_userinfo` VALUES ('12', 'qweasd', null, '男', '', '', '', '', '', '', 'default', '0', '0', '0', '12');

-- ----------------------------
-- Table structure for hd_weibo
-- ----------------------------
DROP TABLE IF EXISTS `hd_weibo`;
CREATE TABLE `hd_weibo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '微博内容',
  `isturn` int(11) NOT NULL DEFAULT '0' COMMENT '是否转发（0：原创， 如果是转发的则保存该转发微博的ID）',
  `time` int(10) unsigned NOT NULL COMMENT '发布时间',
  `turn` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '转发次数',
  `keep` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `uid` int(11) NOT NULL COMMENT '所属用户的ID',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='微博表';

-- ----------------------------
-- Records of hd_weibo
-- ----------------------------
INSERT INTO `hd_weibo` VALUES ('33', '233', '0', '1507913333', '0', '0', '0', '10');
INSERT INTO `hd_weibo` VALUES ('34', '233', '0', '1507913919', '0', '0', '0', '11');
INSERT INTO `hd_weibo` VALUES ('35', '233', '0', '1508039434', '0', '0', '0', '11');
INSERT INTO `hd_weibo` VALUES ('36', '233', '0', '1508039611', '0', '0', '0', '11');
INSERT INTO `hd_weibo` VALUES ('37', '233', '0', '1508134873', '0', '0', '0', '11');
INSERT INTO `hd_weibo` VALUES ('38', '233', '0', '1508135127', '0', '0', '0', '11');
INSERT INTO `hd_weibo` VALUES ('39', '233', '0', '1508135257', '0', '0', '0', '11');
INSERT INTO `hd_weibo` VALUES ('40', '233', '0', '1508135622', '0', '0', '0', '11');
INSERT INTO `hd_weibo` VALUES ('41', '233', '0', '1508135775', '0', '0', '0', '11');
