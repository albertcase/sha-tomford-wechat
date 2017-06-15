# ************************************************************
# Sequel Pro SQL dump
# Version 4529
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.18-0ubuntu0.16.04.1)
# Database: tomford_wechat
# Generation Time: 2017-06-15 04:12:55 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table adp_article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `adp_article`;

CREATE TABLE `adp_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageid` varchar(50) NOT NULL,
  `pagename` varchar(50) NOT NULL,
  `pagetitle` varchar(50) NOT NULL,
  `content` longtext NOT NULL,
  `submiter` varchar(50) NOT NULL,
  `edittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pageid` (`pageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table analyse_shortvideo
# ------------------------------------------------------------

DROP TABLE IF EXISTS `analyse_shortvideo`;

CREATE TABLE `analyse_shortvideo` (
  `analyseid` varchar(50) NOT NULL,
  `MediaId` varchar(255) NOT NULL,
  `ThumbMediaId` varchar(255) NOT NULL,
  KEY `shortvideo_analyseid` (`analyseid`),
  CONSTRAINT `shortvideo_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table file_path
# ------------------------------------------------------------

DROP TABLE IF EXISTS `file_path`;

CREATE TABLE `file_path` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(500) NOT NULL,
  `path` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_analyse
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_analyse`;

CREATE TABLE `request_analyse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ToUserName` varchar(50) NOT NULL,
  `FromUserName` varchar(50) NOT NULL,
  `MsgType` varchar(50) NOT NULL,
  `analyseid` varchar(50) NOT NULL,
  `CreateTime` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_analyse` (`analyseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_event`;

CREATE TABLE `request_event` (
  `analyseid` varchar(50) NOT NULL,
  `Event` varchar(255) NOT NULL,
  `EventKey` varchar(255) NOT NULL,
  `Ticket` varchar(255) NOT NULL,
  KEY `event_analyseid` (`analyseid`),
  CONSTRAINT `event_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_image
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_image`;

CREATE TABLE `request_image` (
  `analyseid` varchar(50) NOT NULL,
  `PicUrl` varchar(255) NOT NULL,
  `MediaId` varchar(255) NOT NULL,
  KEY `image_analyseid` (`analyseid`),
  CONSTRAINT `image_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_link
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_link`;

CREATE TABLE `request_link` (
  `analyseid` varchar(50) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Url` varchar(255) NOT NULL,
  KEY `link_analyseid` (`analyseid`),
  CONSTRAINT `link_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_location
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_location`;

CREATE TABLE `request_location` (
  `analyseid` varchar(50) NOT NULL,
  `Location_X` varchar(255) NOT NULL,
  `Location_Y` varchar(255) NOT NULL,
  `Scale` varchar(255) NOT NULL,
  `Label` varchar(255) NOT NULL,
  KEY `location_analyseid` (`analyseid`),
  CONSTRAINT `location_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_text
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_text`;

CREATE TABLE `request_text` (
  `analyseid` varchar(50) NOT NULL,
  `Content` varchar(255) NOT NULL,
  KEY `text_analyseid` (`analyseid`),
  CONSTRAINT `text_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_video
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_video`;

CREATE TABLE `request_video` (
  `analyseid` varchar(50) NOT NULL,
  `MediaId` varchar(255) NOT NULL,
  `ThumbMediaId` varchar(255) NOT NULL,
  KEY `video_analyseid` (`analyseid`),
  CONSTRAINT `video_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table request_voice
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_voice`;

CREATE TABLE `request_voice` (
  `analyseid` varchar(50) NOT NULL,
  `MediaId` varchar(255) NOT NULL,
  `Format` varchar(255) NOT NULL,
  KEY `voice_analyseid` (`analyseid`),
  CONSTRAINT `voice_analyseid` FOREIGN KEY (`analyseid`) REFERENCES `request_analyse` (`analyseid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table stores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stores`;

CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storename` varchar(100) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `lat` varchar(30) DEFAULT NULL,
  `lng` varchar(30) DEFAULT NULL,
  `openhours` varchar(50) NOT NULL,
  `brandtype` varchar(50) NOT NULL,
  `storemap` varchar(250) NOT NULL,
  `storelog` varchar(250) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table temp_event_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `temp_event_log`;

CREATE TABLE `temp_event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(30) NOT NULL,
  `texts` varchar(100) NOT NULL,
  `event` varchar(800) NOT NULL,
  `templog` varchar(800) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_premission
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_premission`;

CREATE TABLE `user_premission` (
  `uid` int(11) NOT NULL,
  `premission` varchar(50) NOT NULL,
  KEY `wechat_admin_id` (`uid`),
  CONSTRAINT `wechat_admin_id` FOREIGN KEY (`uid`) REFERENCES `wechat_admin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_premission` WRITE;
/*!40000 ALTER TABLE `user_premission` DISABLE KEYS */;

INSERT INTO `user_premission` (`uid`, `premission`)
VALUES
	(1,'user_usercontrol');

/*!40000 ALTER TABLE `user_premission` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wechat_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_admin`;

CREATE TABLE `wechat_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `latestTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `wechat_admin` WRITE;
/*!40000 ALTER TABLE `wechat_admin` DISABLE KEYS */;

INSERT INTO `wechat_admin` (`id`, `username`, `password`, `latestTime`, `createTime`)
VALUES
	(1,'admin','0b272f93f759d44bd8f9a364c8949130','2017-06-15 04:00:28','2016-05-13 09:25:01');

/*!40000 ALTER TABLE `wechat_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wechat_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_events`;

CREATE TABLE `wechat_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuId` varchar(50) DEFAULT '',
  `getMsgType` varchar(50) DEFAULT '',
  `getContent` varchar(250) DEFAULT '',
  `getEvent` varchar(100) DEFAULT '',
  `getEventKey` varchar(255) DEFAULT '',
  `getTicket` varchar(255) DEFAULT '',
  `MsgType` varchar(50) DEFAULT '',
  `createTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_feedbacks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_feedbacks`;

CREATE TABLE `wechat_feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuId` varchar(50) DEFAULT '',
  `MsgType` varchar(50) DEFAULT '',
  `MsgData` longtext,
  `createTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_getmsglog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_getmsglog`;

CREATE TABLE `wechat_getmsglog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `msgType` varchar(50) NOT NULL,
  `msgXml` longtext NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_jssdk
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_jssdk`;

CREATE TABLE `wechat_jssdk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `domain` varchar(50) NOT NULL,
  `editorid` int(11) NOT NULL,
  `jsfilename` varchar(50) NOT NULL,
  `jscontent` longtext NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `wechat_jssdk_eid` (`editorid`),
  CONSTRAINT `wechat_jssdk_id` FOREIGN KEY (`editorid`) REFERENCES `wechat_admin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_keyword_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_keyword_tag`;

CREATE TABLE `wechat_keyword_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuId` varchar(50) NOT NULL,
  `Tagname` varchar(50) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_menu`;

CREATE TABLE `wechat_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuName` varchar(80) DEFAULT '',
  `eventtype` varchar(50) DEFAULT '',
  `eventKey` varchar(50) DEFAULT NULL,
  `eventUrl` varchar(255) DEFAULT NULL,
  `eventmedia_id` varchar(255) DEFAULT NULL,
  `width` enum('1','2','3','4','5') DEFAULT '1',
  `createTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_menu_hierarchy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_menu_hierarchy`;

CREATE TABLE `wechat_menu_hierarchy` (
  `tid` int(11) NOT NULL,
  `parent` int(11) DEFAULT '0',
  KEY `wechat_menu_id` (`tid`),
  CONSTRAINT `wechat_menu_id` FOREIGN KEY (`tid`) REFERENCES `wechat_menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_oauth
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_oauth`;

CREATE TABLE `wechat_oauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `redirect_url` varchar(250) NOT NULL,
  `callback_url` varchar(250) NOT NULL,
  `scope` varchar(50) NOT NULL,
  `oauthfile` varchar(50) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_qrcode
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_qrcode`;

CREATE TABLE `wechat_qrcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qrName` varchar(200) NOT NULL,
  `qrSceneid` int(11) DEFAULT NULL,
  `qrScenestr` varchar(200) DEFAULT NULL,
  `qrTicket` varchar(255) NOT NULL,
  `qrExpire` int(11) DEFAULT NULL,
  `qrSubscribe` int(11) DEFAULT '0',
  `qrScan` int(11) DEFAULT '0',
  `qrUrl` varchar(255) DEFAULT NULL,
  `feedbackid` varchar(200) DEFAULT NULL,
  `qrtype` enum('1','2') DEFAULT '1' COMMENT '1.永久,2.临时',
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qrTicket` (`qrTicket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table wechat_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wechat_users`;

CREATE TABLE `wechat_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `headimgurl` varchar(255) NOT NULL,
  `sex` enum('0','1','2') DEFAULT '0' COMMENT '0 null,1 male,2 female',
  `country` varchar(60) DEFAULT NULL,
  `province` varchar(60) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `status` enum('1','2') DEFAULT '1' COMMENT '1 subscript,2 unsubscript',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
