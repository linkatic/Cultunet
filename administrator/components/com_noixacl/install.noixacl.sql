DROP TABLE IF EXISTS `#__noixacl_adapters`;

DROP TABLE IF EXISTS `#__noixacl_multigroups`;

DROP TABLE IF EXISTS `#__noixacl_rules`;

DROP TABLE IF EXISTS `#__noixacl_groups_level` ;

CREATE TABLE `#__noixacl_adapters` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(80) default NULL,
  `adapter` varchar(50) default NULL,
  `ordering` int(11) default NULL,
  `enabled` tinyint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__noixacl_multigroups` (
  `id_user` int(11) default NULL,
  `id_group` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__noixacl_rules` (
  `id` int(11) NOT NULL auto_increment,
  `aco_section` varchar(50) default NULL,
  `aco_value` varchar(50) default NULL,
  `aro_section` varchar(50) default NULL,
  `aro_value` varchar(50) default NULL,
  `axo_section` varchar(255) default NULL,
  `axo_value` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__noixacl_groups_level` (
  `id_group` int(11) NOT NULL,
  `id_levels` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

COMMIT;
