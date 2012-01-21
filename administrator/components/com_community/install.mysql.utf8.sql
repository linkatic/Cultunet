CREATE TABLE IF NOT EXISTS `#__community_activities` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `actor` int(10) unsigned NOT NULL,
  `target` int(10) unsigned NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `app` varchar(200) NOT NULL,
  `cid` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `access` tinyint(3) unsigned NOT NULL,
  `params` text NOT NULL,
  `points` int(4) NOT NULL default '1',
  `archived` tinyint(3) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `actor` (`actor`),
  KEY `target` (`target`),
  KEY `app` (`app`),
  KEY `created` (`created`),
  KEY `archived` (`archived`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_activities_hide` (
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_apps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL,
  `apps` varchar(200) NOT NULL,
  `ordering` int(10) unsigned NOT NULL,  
  `position` varchar(50) NOT NULL DEFAULT 'content',
  `params` text NOT NULL,
  `privacy` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_userid` (`userid`),
  KEY `idx_user_apps` (`userid`, `apps`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_avatar` (
  `id` int(10) unsigned NOT NULL,
  `apptype` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `type` tinyint(3) unsigned NOT NULL COMMENT '0 = small, 1 = medium, 2=large',
  UNIQUE KEY `id` (`id`,`apptype`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_config` (
  `name` varchar(64) NOT NULL,
  `params` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_connection` (
  `connection_id` int(11) NOT NULL auto_increment,
  `connect_from` int(11) NOT NULL,
  `connect_to` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `group` int(11) NOT NULL,
  `msg` text NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY  (`connection_id`),
  KEY `connect_from` (`connect_from`,`connect_to`,`status`,`group`),
  KEY `idx_connect_to` (`connect_to`),
  KEY `idx_connect_from` (`connect_from`),
  KEY `idx_connect_tofrom` ( `connect_to`, `connect_from` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_fields` (
  `id` int(10) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `ordering` int(11) default '0',
  `published` tinyint(1) NOT NULL default '0',
  `min` int(5) NOT NULL,
  `max` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tips` text NOT NULL,
  `visible` tinyint(1) default '0',
  `required` tinyint(1) default '0',
  `searchable` tinyint(1) default '1',
  `registration` tinyint(1) default '1',  
  `options` text,
  `fieldcode` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fieldcode` (`fieldcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_fields_values` (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `field_id` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),    
  KEY `field_id` (`field_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_user_fieldid` (`user_id`, `field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_files` (
  `id` int(11) NOT NULL auto_increment,
  `creator` int(11) NOT NULL,
  `name` text NOT NULL,
  `caption` text NOT NULL,
  `created` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `permissions` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_friendgroup` (
  `group_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_friendlist` (
  `group_id` int(10) unsigned NOT NULL auto_increment,
  `group_name` varchar(45) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_groups` (
  `id` int(11) NOT NULL auto_increment,
  `published` tinyint(1) NOT NULL,
  `ownerid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `approvals` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `avatar` text NOT NULL,
  `thumb` text NOT NULL,
  `discusscount` int(11) NOT NULL default '0',
  `wallcount` int(11) NOT NULL default '0',
  `membercount` int(11) NOT NULL default '0',
  `params` TEXT NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_groups_bulletins` (
  `id` int(11) NOT NULL auto_increment,
  `groupid` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_groups_category` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_groups_discuss` (
  `id` int(11) NOT NULL auto_increment,
  `parentid` int(11) NOT NULL default '0',
  `groupid` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `lastreplied` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `groupid` (`groupid`),
  KEY `parentid` (`parentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_groups_members` (
  `groupid` int(11) NOT NULL,
  `memberid` int(11) NOT NULL,
  `approved` int(11) NOT NULL,
  `permissions` int(1) NOT NULL,
  KEY `groupid` (`groupid`),
  KEY `idx_memberid` (`memberid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_mailq` (
  `id` int(11) NOT NULL auto_increment,
  `recipient` text NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `template` varchar(64) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_msg` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `from` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `deleted` tinyint(3) unsigned default '0',
  `from_name` varchar(45) NOT NULL,
  `posted_on` datetime default NULL,
  `subject` tinytext NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_msg_recepient` (
  `msg_id` int(10) unsigned NOT NULL,
  `msg_parent` int(10) unsigned NOT NULL default '0',
  `msg_from` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL,
  `bcc` tinyint(3) unsigned default '0',
  `is_read` tinyint(3) unsigned default '0',
  `deleted` tinyint(3) unsigned default '0',
  UNIQUE KEY `un` (`msg_id`,`to`),
  KEY `msg_id` (`msg_id`),
  KEY `to` (`to`),
  KEY `idx_isread_to_deleted` (`is_read`, `to`, `deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_photos` (
  `id` int(11) NOT NULL auto_increment,
  `albumid` int(11) NOT NULL,
  `caption` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `creator` int(11) NOT NULL,
  `permissions` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `original` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL DEFAULT '0',
  `storage` varchar(64) NOT NULL default 'file',
  `created` datetime NOT NULL,
  `ordering` INT( 11 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `albumid` (`albumid`),
  KEY `idx_storage` ( `storage` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_photos_albums` (
  `id` int(11) NOT NULL auto_increment,
  `photoid` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `permissions` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `path` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `groupid` INT( 11 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `creator` (`creator`),
  KEY `idx_type` (`type`),
  KEY `idx_albumtype` (`id`, `type`),
  KEY `idx_creatortype` (`creator`, `type`),
  KEY `idx_groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_userpref` (
  `id` int(11) NOT NULL COMMENT 'user id',
  `params` text NOT NULL COMMENT 'params'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_users` (
  `userid` int(11) NOT NULL,
  `status` text NOT NULL,
  `points` int(11) NOT NULL,
  `posted_on` datetime NOT NULL,
  `avatar` text NOT NULL,
  `thumb` text NOT NULL,
  `invite` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `view` int(11) NOT NULL default '0',
  `friendcount` int(11) NOT NULL default '0',
  `alias` VARCHAR( 255 ) NOT NULL,
  `latitude` float NOT NULL DEFAULT '255',
  `longitude` float NOT NULL DEFAULT '255',
  PRIMARY KEY  (`userid`)  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_wall` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `contentid` int(10) unsigned NOT NULL default '0',
  `post_by` int(10) unsigned NOT NULL default '0',
  `ip` varchar(45) NOT NULL,
  `comment` text NOT NULL,
  `date` varchar(45) NOT NULL,
  `published` tinyint(1) unsigned NOT NULL,
  `type` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `contentid` (`contentid`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_register` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(200) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(150) NOT NULL,  
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created` datetime NULL,
  `ip` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_photos_tokens` (
  `userid` int(11) NOT NULL,
  `token` varchar(200) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_userpoints` (
  `id` int(11) NOT NULL auto_increment,
  `rule_name` varchar(255) NOT NULL default '',
  `rule_description` text NOT NULL default '',
  `rule_plugin` varchar(255) NOT NULL default '',
  `action_string` varchar(255) NOT NULL default '',
  `component` varchar(255) NOT NULL default '',
  `access` tinyint(1) NOT NULL default '1',  
  `points` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `system` tinyint(1) NOT NULL default '0',  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_reports` (
  `id` int(11) NOT NULL auto_increment,
  `uniquestring` varchar(200) NOT NULL,
  `link` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__community_reports_actions` (
  `id` int(11) NOT NULL auto_increment,
  `reportid` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `parameters` varchar(255) NOT NULL,
  `defaultaction` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__community_reports_reporter` (
  `reportid` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `ip` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_connect_users` (
  `connectid` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL,
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_videos` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL DEFAULT 'file',
  `video_id` varchar(200) DEFAULT NULL,
  `description` text NOT NULL,
  `creator` int(11) unsigned NOT NULL,
  `creator_type` varchar(200) NOT NULL DEFAULT 'user',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `permissions` varchar(255) NOT NULL DEFAULT '0',
  `category_id` int(11) unsigned NOT NULL,
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '1',
  `featured` tinyint(3) NOT NULL DEFAULT '0',
  `duration` float unsigned DEFAULT '0',
  `status` varchar(200) NOT NULL DEFAULT 'pending',
  `thumb` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `groupid` INT(11) unsigned NOT NULL DEFAULT '0',
  `filesize` INT(11) NOT NULL DEFAULT '0',
  `storage` varchar(64) NOT NULL default 'file',
  PRIMARY KEY (`id`),
  KEY `creator` (`creator`),
  KEY `idx_groupid` (`groupid`),
  KEY `idx_storage` ( `storage` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_videos_category` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `published` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__community_register_auth_token` (  
  `token` varchar(200) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `auth_key` varchar(200) NOT NULL,  
  `created` datetime NOT NULL,  
  PRIMARY KEY  (`token`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

ALTER TABLE `#__community_activities` MODIFY `title` text;

CREATE TABLE IF NOT EXISTS `#__community_featured` (
  `id` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `#__community_photos_tag` (
  `id` int(11) NOT NULL auto_increment,
  `photoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `position` varchar(50) NOT NULL,  
  `created_by` int(11) NOT NULL,  
  `created` datetime NOT NULL,  
  PRIMARY KEY  (`id`),
  KEY `idx_photoid` (`photoid`),
  KEY `idx_userid` (`userid`),  
  KEY `idx_created_by` (`created_by`),
  KEY `idx_photo_user` (`photoid`, `userid`)  
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `#__community_storage_s3` (
  `storageid` VARCHAR( 255 ) NOT NULL ,
  `resource_path` VARCHAR( 255 ) NOT NULL ,
  UNIQUE (`storageid`)
) ENGINE=MYISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_groups_invite` (
`groupid` INT( 11 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`creator` INT( 11 ) NOT NULL
) ENGINE=MYISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__community_events_category` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__community_events_members` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `eventid` int(11) unsigned NOT NULL,
  `memberid` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '[Join / Invite]: 0 - [pending approval/pending invite], 1 - [approved/confirmed], 2 - [rejected/declined], 3 - [maybe/maybe], 4 - [blocked/blocked]',
  `permission` tinyint(1) unsigned NOT NULL DEFAULT '3' COMMENT '1 - creator, 2 - admin, 3 - member',
  `invited_by` int(11) unsigned NULL DEFAULT '0',
  `approval` tinyint(1) unsigned NULL DEFAULT '0' COMMENT '0 - no approval required, 1 - required admin approval',
  `created` datetime NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_eventid` (`eventid`),
  KEY `idx_status` (`status`),
  KEY `idx_invitedby` (`invited_by`),
  KEY `idx_permission` (`eventid`, `permission`),
  KEY `idx_member_event` (`eventid`, `memberid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__community_events` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) unsigned NOT NULL,
  `contentid` int(11) unsigned NULL DEFAULT '0' COMMENT '0 - if type == profile, else it hold the group id',
  `type` varchar(255) NOT NULL DEFAULT 'profile' COMMENT 'profile, group',
  `title` varchar(255) NOT NULL,
  `location` text NOT NULL,  
  `description` text NULL,
  `creator` int(11) unsigned NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `permission` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0 - Open (Anyone can mark attendence), 1 - Private (Only invited can mark attendence)',
  `avatar` varchar(255) NULL,
  `thumb` varchar(255) NULL,
  `invitedcount` int(11) unsigned NULL DEFAULT '0',
  `confirmedcount` int(11) unsigned NULL DEFAULT '0' COMMENT 'treat this as member count as well',
  `declinedcount` int(11) unsigned NULL DEFAULT '0',
  `maybecount` int(11) unsigned NULL DEFAULT '0',
  `wallcount` int(11) unsigned NULL DEFAULT '0',
  `ticket` int(11) unsigned NULL DEFAULT '0' COMMENT 'Represent how many guest can be joined or invited.',
  `allowinvite` tinyint(1) unsigned NULL DEFAULT '1' COMMENT '0 - guest member cannot invite thier friends to join. 1 - yes, guest member can invite any of thier friends to join.',
  `created` datetime NULL,
  `hits` int(11) unsigned NULL DEFAULT '0',
  `published` int(11) unsigned NULL DEFAULT '1',
  `latitude` float NOT NULL DEFAULT '255',
  `longitude` float NOT NULL DEFAULT '255',
  PRIMARY KEY  (`id`),
  KEY `idx_creator` (`creator`),
  KEY `idx_period` (`startdate`, `enddate`),
  KEY `idx_type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_events_location` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `eventid` int(11) unsigned NOT NULL,
  `street` text NULL,
  `zipcode` varchar(15) NULL,
  `city` varchar(255) NULL,
  `state` varchar(255) NULL,
  `country` varchar(255) NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_eventid` (`eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__community_blocklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `blocked_userid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `blocked_userid` (`blocked_userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__community_apps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `apps` varchar(200) NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `params` text NOT NULL,
  `privacy` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`userid`),
  KEY `idx_user_apps` (`userid`,`apps`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__community_location_cache` (
  `address` varchar(255) NOT NULL,
  `latitude` float NOT NULL DEFAULT '255',
  `longitude` float NOT NULL DEFAULT '255',
  `data` text NOT NULL,
  `status` varchar(2) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__community_oauth` (
  `userid` int(11) NOT NULL,
  `requesttoken` text NOT NULL,
  `accesstoken` text NOT NULL,
  `app` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;