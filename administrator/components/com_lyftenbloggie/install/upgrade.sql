--
-- Alter some tables
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