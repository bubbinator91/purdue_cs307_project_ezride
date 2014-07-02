SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `events` (
  `eventid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `id` varchar(256) DEFAULT NULL,
  `google_id` int(16) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `details` varchar(535) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  PRIMARY KEY (`eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2293 ;

CREATE TABLE IF NOT EXISTS `gpuserinfo` (
  `userid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `phonenumber` varchar(16) DEFAULT NULL,
  `address` text,
  `profile` text,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Google+ user info' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `groupcalendar` (
  `eventid` int(16) DEFAULT NULL,
  `groupid` int(16) DEFAULT NULL,
  KEY `eventid` (`eventid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groups` (
  `groupid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `description` text,
  `datecreated` datetime DEFAULT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=ucs2 AUTO_INCREMENT=76 ;

CREATE TABLE IF NOT EXISTS `iosgroups` (
  `groupid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `description` text,
  `datecreated` datetime DEFAULT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=ucs2 AUTO_INCREMENT=22 ;

CREATE TABLE IF NOT EXISTS `iosusergroups` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `userid` int(16) DEFAULT NULL,
  `groupid` int(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

CREATE TABLE IF NOT EXISTS `locations` (
  `userid` int(16) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `messages` (
  `messageid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(535) DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `userid` int(16) unsigned DEFAULT NULL,
  `groupid` int(16) DEFAULT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=ucs2 AUTO_INCREMENT=180 ;

CREATE TABLE IF NOT EXISTS `usercalendar` (
  `userid` int(16) DEFAULT NULL,
  `eventid` varchar(256) DEFAULT NULL,
  KEY `userid` (`userid`),
  KEY `eventid` (`eventid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `usergroups` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `userid` int(16) DEFAULT NULL,
  `groupid` int(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=179 ;

CREATE TABLE IF NOT EXISTS `userinfo` (
  `userid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `google_id` varchar(256) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL COMMENT 'save user name',
  `email` varchar(256) DEFAULT NULL,
  `phonenumber` varchar(16) DEFAULT NULL,
  `address` text,
  `profile` text,
  `avatarUrl` varchar(2083) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=121 ;
