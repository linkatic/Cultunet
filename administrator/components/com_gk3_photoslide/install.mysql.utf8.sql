DROP TABLE IF EXISTS `#__gk3_photoslide_groups`;
DROP TABLE IF EXISTS `#__gk3_photoslide_slides`;
DROP TABLE IF EXISTS `#__gk3_photoslide_options`;

CREATE TABLE `#__gk3_photoslide_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `type` varchar(32) NOT NULL,
  `thumb_x` int(11) NULL,
  `thumb_y` int(11) NULL,
  `image_x` int(11) NOT NULL,
  `image_y` int(11) NOT NULL,
  `background` varchar(11) NOT NULL,
  `default_quality` int(3) NOT NULL,
  `default_image` int(11) NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `#__gk3_photoslide_slides` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `article` int(11) NULL,
  `title` text NULL,
  `link_type` int(2) NULL,
  `link` text NULL,
  `content` text NOT NULL,
  `published` int(2) NOT NULL,
  `access` int(1) NOT NULL,
  `order` int(11) NOT NULL,
  `image_x` int(11) NULL,
  `image_y` int(11) NULL,
  `stretch` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `#__gk3_photoslide_options` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `#__gk3_photoslide_options` (`id`, `name`, `value`) VALUES
(1, 'modal_news', '0'),
(2, 'modal_settings', '1'),
(3, 'group_shortcuts', '1'),
(4, 'slide_shortcuts', '1'),
(5, 'wysiwyg', '1'),
(6, 'gavick_news', '1'),
(7, 'colorpickers', '1');