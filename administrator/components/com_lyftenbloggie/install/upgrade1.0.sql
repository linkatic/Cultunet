--
-- Upgrade from 1.0.x to 1.1.x
--

ALTER TABLE `#__bloggies_reports` 
 ADD COLUMN `details` TEXT NOT NULL DEFAULT '' AFTER `reason`;

ALTER TABLE `#__bloggies_categories`
 ADD COLUMN `parent` int(11) NOT NULL default '0' AFTER `title`;

ALTER TABLE `#__bloggies_authors`
 DROP COLUMN `published`,
 DROP COLUMN `admin`,
 DROP COLUMN `permissions`,
 ADD COLUMN `url` varchar(255) AFTER `user_id`;

ALTER TABLE `#__bloggies_tags`
 ADD COLUMN `published` tinyint(4) NOT NULL DEFAULT '1' AFTER `slug`;

ALTER TABLE `#__bloggies_entries`
 CHANGE COLUMN `mask` `image` TEXT NOT NULL;

DROP TABLE IF EXISTS `#__bloggies_plugins`;
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

DROP TABLE IF EXISTS `#__bloggies_themes`;
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