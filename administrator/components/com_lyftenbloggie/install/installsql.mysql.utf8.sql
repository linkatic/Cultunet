--
-- Structure for the `#__bloggies_authors`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `about` text,
  `avatar` varchar(225) DEFAULT NULL,
  `attribs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_bookmarks`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_bookmarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website` varchar(255) NOT NULL DEFAULT '',
  `html` text NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_categories`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `parent` int(11) NOT NULL DEFAULT '0',
  `default` tinyint(4) NOT NULL DEFAULT '0',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `text` mediumtext NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_comments`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL DEFAULT '0',
  `author` tinytext,
  `author_email` varchar(100) NOT NULL DEFAULT '',
  `author_url` text,
  `author_ip` varchar(100) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text NOT NULL,
  `karma` int(11) NOT NULL DEFAULT '0',
  `agent` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `parent` varchar(20) NOT NULL DEFAULT '0',
  `user_id` varchar(20) NOT NULL DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `entry_id` (`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_entries`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_entries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `title_alias` varchar(255) NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `sectionid` int(11) unsigned NOT NULL DEFAULT '0',
  `image` text NOT NULL,
  `catid` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` text NOT NULL,
  `pinged` text NOT NULL,
  `version` int(11) unsigned NOT NULL DEFAULT '1',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(11) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_section` (`sectionid`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_groups` (since v1.1.0)
--

CREATE TABLE IF NOT EXISTS `#__bloggies_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) NOT NULL DEFAULT '',
  `permissions` text NOT NULL,
  `email_all` tinyint(4) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_plugins` (modded in v1.1.0)
--

CREATE TABLE IF NOT EXISTS `#__bloggies_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(56) NOT NULL DEFAULT '',
  `email` varchar(124) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(24) NOT NULL DEFAULT '',
  `license` varchar(255) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `create_date` varchar(56) NOT NULL DEFAULT '',
  `attribs` text NOT NULL,
  `published` int(1) NOT NULL DEFAULT '0',
  `iscore` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_relations`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_relations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry` int(10) unsigned NOT NULL DEFAULT '0',
  `tag` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_reports`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `comment_id` int(11) NOT NULL DEFAULT '0',
  `reason` text NOT NULL,
  `details` text,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_settings` (since v1.1.0)
--

CREATE TABLE IF NOT EXISTS `#__bloggies_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_tags`
--

CREATE TABLE IF NOT EXISTS `#__bloggies_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Structure for the `#__bloggies_themes` (modded in v1.1.0)
--

CREATE TABLE IF NOT EXISTS `#__bloggies_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `is_default` int(1) NOT NULL DEFAULT '0',
  `author` varchar(56) NOT NULL DEFAULT '',
  `email` varchar(124) NOT NULL DEFAULT '',
  `website` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(24) NOT NULL DEFAULT '',
  `license` varchar(255) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `create_date` varchar(56) NOT NULL DEFAULT '',
  `attribs` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;