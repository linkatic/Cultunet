ALTER TABLE `#__bloggies_tags`
 ADD COLUMN `published` tinyint(4) NOT NULL DEFAULT '1' AFTER `slug`;