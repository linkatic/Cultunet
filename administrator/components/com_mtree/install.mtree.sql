CREATE TABLE IF NOT EXISTS `#__mt_archived_log` (
  `log_id` int(10) unsigned NOT NULL,
  `log_ip` varchar(255) NOT NULL default '',
  `log_type` varchar(32) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `log_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `link_id` int(11) NOT NULL default '0',
  `rev_id` int(11) NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`log_id`),
  KEY `link_id2` (`link_id`,`log_ip`),
  KEY `link_id1` (`link_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `log_type` (`log_type`),
  KEY `log_ip` (`log_ip`,`user_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_archived_reviews` (
  `rev_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `guest_name` varchar(255) NOT NULL default '',
  `rev_title` varchar(255) NOT NULL default '',
  `rev_text` text NOT NULL,
  `rev_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `rev_approved` tinyint(4) NOT NULL default '1',
  `admin_note` mediumtext NOT NULL,
  `vote_helpful` int(10) unsigned NOT NULL default '0',
  `vote_total` int(10) unsigned NOT NULL default '0',
  `ownersreply_text` text NOT NULL,
  `ownersreply_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ownersreply_approved` tinyint(4) NOT NULL default '0',
  `ownersreply_admin_note` mediumtext NOT NULL,
  `send_email` tinyint(4) NOT NULL,
  `email_message` mediumtext NOT NULL,
  PRIMARY KEY  (`rev_id`),
  KEY `link_id` (`link_id`,`rev_approved`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_archived_users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `username` varchar(25) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `usertype` varchar(25) NOT NULL default '',
  `block` tinyint(4) NOT NULL default '0',
  `sendEmail` tinyint(4) default '0',
  `gid` tinyint(3) unsigned NOT NULL default '1',
  `registerDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL default '',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_cats` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat_desc` text NOT NULL,
  `cat_parent` int(11) NOT NULL default '0',
  `cat_links` int(11) NOT NULL default '0',
  `cat_cats` int(11) NOT NULL default '0',
  `cat_featured` tinyint(4) NOT NULL default '0',
  `cat_image` varchar(255) NOT NULL,
  `cat_published` tinyint(4) NOT NULL default '0',
  `cat_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `cat_approved` tinyint(4) NOT NULL default '0',
  `cat_template` varchar(255) NOT NULL default '',
  `cat_usemainindex` tinyint(4) NOT NULL default '0',
  `cat_allow_submission` tinyint(4) NOT NULL default '1',
  `cat_show_listings` tinyint(3) unsigned NOT NULL default '1',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `lft` int(11) NOT NULL default '0',
  `rgt` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cat_id`),
  KEY `cat_id` (`cat_id`,`cat_published`,`cat_approved`),
  KEY `cat_parent` (`cat_parent`,`cat_published`,`cat_approved`,`cat_cats`,`cat_links`),
  KEY `dtree` (`cat_published`,`cat_approved`),
  KEY `lft_rgt` (`lft`,`rgt`),
  KEY `func_getPathWay` (`lft`,`rgt`,`cat_id`,`cat_parent`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_cfvalues` (
  `id` int(11) NOT NULL auto_increment,
  `cf_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `value` mediumtext NOT NULL,
  `attachment` int(10) unsigned NOT NULL default '0',
  `counter` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cf_id` (`cf_id`,`link_id`),
  KEY `link_id` (`link_id`),
  KEY `value` (`value`(8))
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_cfvalues_att` (
  `att_id` int(10) unsigned NOT NULL auto_increment,
  `link_id` int(10) unsigned NOT NULL,
  `cf_id` int(10) unsigned NOT NULL,
  `raw_filename` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `extension` varchar(255) NOT NULL,
  PRIMARY KEY  (`att_id`),
  KEY `primary2` (`link_id`,`cf_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_cl` (
  `cl_id` int(11) NOT NULL auto_increment,
  `link_id` int(11) NOT NULL default '0',
  `cat_id` int(11) NOT NULL default '0',
  `main` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`cl_id`),
  KEY `link_id` (`link_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_claims` (
  `claim_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `comment` mediumtext NOT NULL,
  `created` datetime NOT NULL,
  `admin_note` mediumtext NOT NULL,
  PRIMARY KEY  (`claim_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_clone_owners` (
  `user_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY  (`user_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_config` (
  `varname` varchar(100) NOT NULL,
  `groupname` varchar(50) NOT NULL,
  `value` mediumtext NOT NULL,
  `default` mediumtext NOT NULL,
  `configcode` mediumtext NOT NULL,
  `ordering` smallint(6) NOT NULL,
  `displayed` smallint(5) unsigned NOT NULL default '1',
  PRIMARY KEY  (`varname`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__mt_config` VALUES ('admin_email', 'main', '', '', 'text', 500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('template', 'main', 'm2', 'm2', 'text', 200, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('admin_use_explorer', 'admin', '1', '1', 'yesno', 11500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('small_image_click_target_size', 'admin', '', 'o', 'text', 13000, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('allow_changing_cats_in_addlisting', 'listing', '1', '1', 'yesno', 3550, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('allow_imgupload', 'image', '1', '1', 'yesno', 10100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('allow_listings_submission_in_root', 'listing', '0', '0', 'yesno', 3500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('allow_owner_rate_own_listing', 'ratingreview', '0', '0', 'yesno', 1700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('allow_owner_review_own_listing', 'ratingreview', '0', '0', 'yesno', 10005, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('allow_rating_during_review', 'ratingreview', '1', '1', 'yesno', 2650, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('allow_user_assign_more_than_one_category', 'listing', '1', '1', 'yesno', 3575, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('alpha_index_additional_chars', 'listing', '', '', 'text', 3410, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('cat_image_dir', 'image', '/components/com_mtree/img/cats/', '/components/com_mtree/img/cats/', 'text', 700, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('display_empty_cat', 'category', '1', '1', 'yesno', 3300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('display_listings_in_root', 'listing', '1', '1', 'yesno', 3600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('explorer_tree_level', 'admin', '4', '9', 'text', 11600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_featured', 'listing', '20', '20', 'text', 6700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_links', 'listing', '20', '20', 'text', 5500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_favourite', 'listing', '20', '20', 'text', 6100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_mostrated', 'listing', '20', '20', 'text', 6300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_mostreview', 'listing', '20', '20', 'text', 6500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_new', 'listing', '20', '20', 'text', 5800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_popular', 'listing', '20', '20', 'text', 5700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_updated', 'listing', '20', '20', 'text', 6000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_reviews', 'listing', '20', '20', 'text', 5600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_searchresults', 'listing', '20', '20', 'text', 6600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_num_of_toprated', 'listing', '20', '20', 'text', 6400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_total_new', 'listing', '100', '60', 'text', 5900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_cat_order1', 'category', 'cat_name', 'cat_name', 'cat_order', 1400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_cat_order2', 'category', 'asc', 'asc', 'sort_direction', 1500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_listing_order1', 'listing', 'link_featured', 'link_rating', 'listing_order', 1800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_listing_order2', 'listing', 'desc', 'desc', 'sort_direction', 1900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_review_order1', 'ratingreview', 'rev_date', 'vote_helpful', 'review_order', 2900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_review_order2', 'ratingreview', 'desc', 'desc', 'sort_direction', 3000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_search_order1', 'search', 'link_featured', 'link_featured', 'listing_order', 2150, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('first_search_order2', 'search', 'desc', 'desc', 'sort_direction', 2151, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('hit_lag', 'main', '86400', '86400', 'text', 9000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('images_per_listing', 'image', '8', '10', 'text', 10200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('img_impath', 'image', '', '', 'text', 1100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('img_netpbmpath', 'image', '', '', 'text', 1200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('linkchecker_last_checked', 'linkchecker', '', '', 'text', 300, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('linkchecker_num_of_links', 'linkchecker', '10', '10', 'text	', 100, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('linkchecker_seconds', 'linkchecker', '1', '1', 'text', 200, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('link_new', 'main', '10', '7', 'text', 8800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('link_popular', 'main', '10', '120', 'text', 8900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('listing_image_dir', 'image', '/components/com_mtree/img/listings/', '/components/com_mtree/img/listings/', 'text', 600, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('map', 'feature', 'googlemaps', 'googlemaps', 'map', 4150, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('min_votes_for_toprated', 'ratingreview', '1', '1', 'text', 1500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('min_votes_to_show_rating', 'ratingreview', '0', '0', 'text', 1600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('name', 'core', 'Mosets Tree', 'Mosets Tree', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('needapproval_addcategory', 'main', '1', '1', 'yesno', 8500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('needapproval_addlisting', 'main', '1', '1', 'yesno', 8300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('needapproval_addreview', 'ratingreview', '1', '1', 'yesno', 2700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('needapproval_modifylisting', 'main', '0', '1', 'yesno', 8400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('needapproval_replyreview', 'ratingreview', '0', '1', 'yesno', 8500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_notify_admin', 'notify', '', '', 'note', 9099, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_notify_owner', 'notify', '', '', 'note', 9450, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_rating', 'ratingreview', '', '', 'note', 1000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_review', 'ratingreview', '', '', 'note', 2500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_rss_custom_fields', 'rss', '', '', 'note', 300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyadmin_delete', 'notify', '1', '1', 'yesno', 9300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyadmin_modifylisting', 'notify', '1', '1', 'yesno', 9200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyadmin_newlisting', 'notify', '1', '1', 'yesno', 9100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyadmin_newreview', 'notify', '1', '1', 'yesno', 9400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyuser_approved', 'notify', '1', '1', 'yesno', 9700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyuser_modifylisting', 'notify', '1', '1', 'yesno', 9600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyuser_newlisting', 'notify', '1', '1', 'yesno', 9500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyuser_review_approved', 'notify', '1', '1', 'yesno', 9800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('optional_email_sent_to_reviewer', 'ratingreview', '', '', 'note', 10010, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('owner_reply_review', 'ratingreview', '1', '1', 'yesno', 8000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('params_xml_filename', 'core', 'params.xml', 'params.xml', 'text', 100, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_1_message', 'ratingreview', '', '', 'predefined_reply', 10120, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_1_title', 'ratingreview', '', '', 'predefined_reply_title', 10110, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_2_message', 'ratingreview', '', '', 'predefined_reply', 10140, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_2_title', 'ratingreview', '', '', 'predefined_reply_title', 10130, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_3_message', 'ratingreview', '', '', 'predefined_reply', 10160, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_3_title', 'ratingreview', '', '', 'predefined_reply_title', 10150, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_4_message', 'ratingreview', '', '', 'predefined_reply', 10180, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_4_title', 'ratingreview', '', '', 'predefined_reply_title', 10170, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_5_message', 'ratingreview', '', '', 'predefined_reply', 10200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_5_title', 'ratingreview', '', '', 'predefined_reply_title', 10190, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_bcc', 'ratingreview', '', '', 'text', 10040, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_for_approved_or_rejected_review', 'ratingreview', '', '', 'note', 10100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_from_email', 'ratingreview', '', '', 'text', 10030, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('predefined_reply_from_name', 'ratingreview', '', '', 'text', 10020, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rate_once', 'ratingreview', '1', '0', 'yesno', 1400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('relative_path_to_js_library', 'core', '/components/com_mtree/js/jquery-1.2.6.min.js', '/components/com_mtree/js/jquery-1.2.6.min.js', 'text', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('require_rating_with_review', 'ratingreview', '1', '1', 'yesno', 2675, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('resize_cat_size', 'image', '80', '80', 'text', 1300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('resize_listing_size', 'image', '100', '100', 'text', 1000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('resize_medium_listing_size', 'image', '600', '600', 'text', 1050, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('resize_method', 'image', 'gd2', 'gd2', 'resize_method', 800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('resize_quality', 'image', '80', '80', 'text', 900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_address', 'rss', '0', '0', 'yesno', 400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_cat_name', 'rss', '0', '0', 'yesno', 310, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_cat_url', 'rss', '0', '0', 'yesno', 320, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_city', 'rss', '0', '0', 'yesno', 500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_country', 'rss', '0', '0', 'yesno', 800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_custom_fields', 'rss', '', '', 'text', 1500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_email', 'rss', '0', '0', 'yesno', 900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_fax', 'rss', '0', '0', 'yesno', 1200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_link_rating', 'rss', '0', '0', 'yesno', 340, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_link_votes', 'rss', '0', '0', 'yesno', 330, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_metadesc', 'rss', '0', '0', 'yesno', 1400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_metakey', 'rss', '0', '0', 'yesno', 1300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_postcode', 'rss', '0', '0', 'yesno', 600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_state', 'rss', '0', '0', 'yesno', 700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_telephone', 'rss', '0', '0', 'yesno', 1100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_website', 'rss', '0', '0', 'yesno', 1000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_lat', 'rss', '0', '0', 'yesno', 1410, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_lng', 'rss', '0', '0', 'yesno', 1420, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_zoom', 'rss', '0', '0', 'yesno', 1430, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_cat_order1', 'category', '', '', 'cat_order', 1600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_cat_order2', 'category', 'asc', 'asc', 'sort_direction', 1700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_listing_order1', 'listing', 'link_name', 'link_votes', 'listing_order', 2000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_listing_order2', 'listing', 'asc', 'desc', 'sort_direction', 2100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_review_order1', 'ratingreview', '', 'vote_total', 'review_order', 4000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_review_order2', 'ratingreview', 'desc', 'desc', 'sort_direction', 5000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_search_order1', 'search', 'link_hits', 'link_hits', 'listing_order', 2152, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('second_search_order2', 'search', 'desc', 'desc', 'sort_direction', 2153, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_claim', 'feature', '1', '1', 'yesno', 4500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_contact', 'feature', '1', '1', 'yesno', 4700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_listnewrss', 'rss', '1', '1', 'yesno', 100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_listupdatedrss', 'rss', '1', '1\n', 'yesno', 200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_map', 'feature', '0', '0', 'yesno', 4100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_ownerlisting', 'feature', '1', '1', 'yesno', 4600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_print', 'feature', '1', '0', 'yesno', 4200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_rating', 'ratingreview', '1', '1', 'yesno', 1100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_recommend', 'feature', '1', '1', 'yesno', 5100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_report', 'feature', '1', '1', 'yesno', 4900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_review', 'ratingreview', '1', '1', 'yesno', 2600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_visit', 'feature', '1', '1', 'yesno', 4300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('third_review_order1', 'ratingreview', '', 'rev_date', 'review_order', 6000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('third_review_order2', 'ratingreview', 'desc', 'desc', 'sort_direction', 7000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('trigger_modified_listing', 'listing', '', '', 'text', 3900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_addcategory', 'main', '1', '1', 'user_access', 8000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_addlisting', 'main', '1', '1', 'user_access', 7900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_allowdelete', 'main', '1', '1', 'yesno', 8200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_allowmodify', 'main', '1', '1', 'yesno', 8100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_contact', 'feature', '0', '0', 'user_access', 4800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_rating', 'ratingreview', '2', '1', 'user_access2', 1300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_recommend', 'feature', '0', '0', 'user_access', 5200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_report', 'feature', '1', '0', 'user_access', 5000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_report_review', 'ratingreview', '1', '1', 'user_access', 9000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_review', 'ratingreview', '2', '1', 'user_access2', 2800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_review_once', 'ratingreview', '1', '1', 'yesno', 9000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('user_vote_review', 'ratingreview', '1', '1', 'yesno', 10000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('use_internal_notes', 'admin', '1', '1', 'yesno', 11900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('use_owner_email', 'feature', '1', '0', 'yesno', 5300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('use_wysiwyg_editor', 'main', '0', '0', 'yesno', 11000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('use_wysiwyg_editor_in_admin', 'admin', '0', '0', 'yesno', 12000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('version', 'core', '2.1.5', '2.1.5', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('major_version', 'core', '2', '2', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('minor_version', 'core', '1', '1', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('dev_version', 'core', '5', '5', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('squared_thumbnail', 'image', '1', '1', 'yesno', 1025, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_favourite', 'feature', '1', '1', 'yesno', 4175, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('relative_path_to_cat_small_image', 'core', '', '/components/com_mtree/img/cats/s/', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('relative_path_to_cat_original_image', 'core', '', '/components/com_mtree/img/cats/o/', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('relative_path_to_listing_small_image', 'core', '', '/components/com_mtree/img/listings/s/', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('relative_path_to_listing_medium_image', 'core', '', '/components/com_mtree/img/listings/m/', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('relative_path_to_listing_original_image', 'core', '', '/components/com_mtree/img/listings/o/', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_title_separator', 'rss', ' - ', ' - ', 'text', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('cat_parse_plugin', 'category', '1', '1', 'yesno', 3400, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('image_maxsize', 'image', '819200', '3145728', 'text', 10300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('banned_text', 'email', '[/url];[/link]', '', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('banned_subject', 'email', '', '', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('banned_email', 'email', '', '', '', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('notifyowner_review_added', 'notify', '1', '1', 'yesno', 9900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('unpublished_message_cfid', 'listing', '0', '0', 'text', 6600, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('load_css', 'core', '1', '1', 'yesno', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_secret_token', 'rss', '', '', 'text', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_category_rss', 'rss', '1', '1', 'yesno', 0, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_total_updated', 'listing', '60', '60', 'text', 6050, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_total_popular', 'listing', '60', '60', 'text', 5750, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_total_favourite', 'listing', '60', '60', 'text', 6150, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_total_mostrated', 'listing', '60', '60', 'text', 6350, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_total_toprated', 'listing', '60', '60', 'text', 6450, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('fe_total_mostreview', 'listing', '60', '60', 'text', 6550, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('default_search_condition', 'search', '2', '2', 'text', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('reset_created_date_upon_approval', 'core', '1', '1', 'yesno', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('cache_registered_viewlink', 'main', '0', '0', 'yesno', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('relative_path_to_attachments', 'core', '/components/com_mtree/attachments/', '/components/com_mtree/attachments/', 'text', 0, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_link_slug_type', 'sef', '1', '1', 'sef_link_slug_type', 100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_image', 'sef', 'image', 'image', 'text', 200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_gallery', 'sef', 'gallery', 'gallery', 'text', 300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_review', 'sef', 'review', 'review', 'text', 400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_replyreview', 'sef', 'reply-review', 'reply-review', 'text', 500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_reportreview', 'sef', 'report-review', 'report-review', 'text', 600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_recommend', 'sef', 'recommend', 'recommend', 'text', 800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_print', 'sef', 'print', 'print', 'text', 850, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_contact', 'sef', 'contact', 'contact', 'text', 900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_report', 'sef', 'report', 'report', 'text', 1000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_claim', 'sef', 'claim', 'claim', 'text', 1100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_visit', 'sef', 'visit', 'visit', 'text', 1200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_category_page', 'sef', 'page', 'page', 'text', 1300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_delete', 'sef', 'delete', 'delete', 'text', 1400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_reviews_page', 'sef', 'reviews', 'reviews', 'text', 1500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_addlisting', 'sef', 'add', 'add', 'text', 1600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_editlisting', 'sef', 'edit', 'edit', 'text', 1650, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_addcategory', 'sef', 'add-category', 'add-category', 'text', 1700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_mypage', 'sef', 'my-page', 'my-page', 'text', 1800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_new', 'sef', 'new', 'new', 'text', 1900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_updated', 'sef', 'updated', 'updated', 'text', 2000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_favourite', 'sef', 'most-favoured', 'most-favoured', 'text', 2100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_featured', 'sef', 'featured', 'featured', 'text', 2200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_popular', 'sef', 'popular', 'popular', 'text', 2300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_mostrated', 'sef', 'most-rated', 'most-rated', 'text', 2400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_toprated', 'sef', 'top-rated', 'top-rated', 'text', 2500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_mostreview', 'sef', 'most-reviewed', 'most-reviewed', 'text', 2600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_listalpha', 'sef', 'list-alpha', 'list-alpha', 'text', 2700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_owner', 'sef', 'owner', 'owner', 'text', 2800, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_favourites', 'sef', 'favourites', 'favourites', 'text', 2900, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_reviews', 'sef', 'reviews', 'reviews', 'text', 3000, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_searchby', 'sef', 'search-by', 'search-by', 'text', 3050, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_search', 'sef', 'search', 'search', 'text', 3100, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_advsearch', 'sef', 'advanced-search', 'advanced-search', 'text', 3200, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_advsearch2', 'sef', 'advanced-search-results', 'advanced-search-results', 'text', 3300, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_rss', 'sef', 'rss', 'rss', 'text', 3400, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_rss_new', 'sef', 'new', 'new', 'text', 3500, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_rss_updated', 'sef', 'updated', 'updated', 'text', 3600, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_space', 'sef', '-', '-', 'text', 3700, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_sef_translations', 'sef', '', '', 'note', 150, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('sef_details', 'sef', 'details', 'details', 'text', 175, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('show_image_rss', 'rss', '1', '1', 'yesno', 250, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('use_map', 'feature', '0', '0', 'yesno', 3950, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('map_default_country', 'feature', '', '', 'text', 3960, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('map_default_state', 'feature', '', '', 'text', 3970, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('map_default_city', 'feature', '', '', 'text', 3980, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_map', 'feature', '', '', 'note', 3925, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('note_other_features', 'feature', '', '', 'note', 4170, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('gmaps_api_key', 'feature', '', '', 'text', 3955, 1);
INSERT IGNORE INTO `#__mt_config` VALUES ('map_default_lat', 'feature', '12.554563528593656', '12.554563528593656', 'text', 3985, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('map_default_lng', 'feature', '18.984375', '18.984375', 'text', 3986, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('map_default_zoom', 'feature', '1', '1', 'text', 3987, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('map_control', 'feature', 'GSmallMapControl,GMapTypeControl', 'GSmallMapControl,GMapTypeControl', 'text', 3988, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('display_pending_approval_listings_to_owners', 'listing', '1', '0', 'yesno', 4000, 0);
INSERT IGNORE INTO `#__mt_config` VALUES ('days_to_expire', 'listing', '0', '0', 'text', '6800', '0');
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_new_limit', 'rss', '40', '40', 'text', '220', '0');
INSERT IGNORE INTO `#__mt_config` VALUES ('rss_updated_limit', 'rss', '40', '40', 'text', '240', '0');
INSERT IGNORE INTO `#__mt_config` VALUES ('limit_max_chars', 'search', '20', '20', 'text', '2160', '0'), ('limit_min_chars', 'search', '3', '3', 'text', '2170', '0');

CREATE TABLE IF NOT EXISTS `#__mt_configgroup` (
  `groupname` varchar(50) NOT NULL,
  `ordering` smallint(6) NOT NULL,
  `displayed` smallint(6) NOT NULL,
  PRIMARY KEY  (`groupname`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__mt_configgroup` VALUES ('main', 100, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('category', 250, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('listing', 300, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('search', 400, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('ratingreview', 450, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('feature', 500, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('notify', 600, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('image', 650, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('rss', 675, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('admin', 700, 1);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('linkchecker', 800, 0);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('core', 999, 0);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('email', 999, 0);
INSERT IGNORE INTO `#__mt_configgroup` VALUES ('sef', 685, 1);

CREATE TABLE IF NOT EXISTS `#__mt_customfields` (
  `cf_id` int(11) NOT NULL auto_increment,
  `field_type` varchar(36) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `default_value` varchar(255) NOT NULL,
  `size` smallint(9) NOT NULL,
  `field_elements` text NOT NULL,
  `prefix_text_mod` varchar(255) NOT NULL,
  `suffix_text_mod` varchar(255) NOT NULL,
  `prefix_text_display` varchar(255) NOT NULL,
  `suffix_text_display` varchar(255) NOT NULL,
  `cat_id` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL default '0',
  `required_field` tinyint(4) NOT NULL default '0',
  `published` tinyint(4) NOT NULL default '1',
  `hide_caption` tinyint(4) NOT NULL default '0',
  `advanced_search` tinyint(4) NOT NULL default '0',
  `simple_search` tinyint(4) NOT NULL default '0',
  `tag_search` tinyint(3) unsigned NOT NULL default '0',
  `details_view` tinyint(3) unsigned NOT NULL default '1',
  `summary_view` tinyint(3) unsigned NOT NULL default '0',
  `search_caption` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `iscore` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`cf_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__mt_customfields` VALUES (1, 'corename', 'Name', '', 50, '', '', '', '', '', 0, 1, 0, 1, 1, 0, 1, 1, 0, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (2, 'coredesc', 'Description', '', 250, '', '', '', '', '', 0, 2, 0, 0, 1, 0, 1, 1, 0, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (3, 'coreuser', 'Owner', '', 0, '', '', '', '', '', 0, 3, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (4, 'coreaddress', 'Address', '', 0, '', '', '', '', '', 0, 4, 0, 0, 1, 0, 0, 1, 0, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (5, 'corecity', 'City', '', 0, '', '', '', '', '', 0, 5, 0, 0, 1, 0, 0, 0, 1, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (6, 'corestate', 'State', '', 0, '', '', '', '', '', 0, 6, 0, 0, 1, 0, 0, 0, 1, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (7, 'corecountry', 'Country', '', 0, '', '', '', '', '', 0, 7, 0, 0, 1, 0, 0, 0, 1, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (8, 'corepostcode', 'Postcode', '', 0, '', '', '', '', '', 0, 8, 0, 0, 1, 0, 0, 0, 0, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (9, 'coretelephone', 'Telephone', '', 0, '', '', '', '', '', 0, 9, 0, 0, 1, 0, 0, 0, 0, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (10, 'corefax', 'Fax', '', 0, '', '', '', '', '', 0, 10, 0, 0, 1, 0, 0, 0, 0, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (11, 'coreemail', 'E-mail', '', 0, '', '', '', '', '', 0, 11, 0, 0, 1, 0, 0, 0, 0, 0, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (12, 'corewebsite', 'Website', '', 0, '', '', '', '', '', 0, 12, 0, 0, 1, 0, 1, 1, 0, 1, 1, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (13, 'coreprice', 'Price', '', 0, '', '', '', '', '', 0, 13, 0, 0, 1, 0, 0, 1, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (14, 'corehits', 'Hits', '', 0, '', '', '', '', '', 0, 14, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (15, 'corevotes', 'Votes', '', 10, '', '', '', '', '', 0, 15, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (16, 'corerating', 'Rating', '', 0, '', '', '', '', '', 0, 16, 0, 0, 1, 0, 1, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (17, 'corefeatured', 'Featured', '', 0, '', '', '', '', '', 0, 17, 0, 0, 1, 0, 1, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (18, 'corecreated', 'Created', '', 0, '', '', '', '', '', 0, 18, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (19, 'coremodified', 'Modified', '', 0, '', '', '', '', '', 0, 19, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (20, 'corevisited', 'Visited', '', 0, '', '', '', '', '', 0, 20, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (21, 'corepublishup', 'Publish up', '', 0, '', '', '', '', '', 0, 21, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (22, 'corepublishdown', 'Publish down', '', 0, '', '', '', '', '', 0, 22, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (23, 'image', 'Image', '', 30, '', '', '', '', '', 0, 26, 0, 0, 1, 0, 1, 0, 0, 1, 0, '', '', 0);
INSERT IGNORE INTO `#__mt_customfields` VALUES (24, 'mfile', 'File', '', 30, '', '', '', '', '', 0, 28, 0, 0, 1, 0, 1, 0, 0, 1, 0, '', '', 0);
INSERT IGNORE INTO `#__mt_customfields` VALUES (25, 'multilineTextbox', 'Multi-line Textbox', '', 0, '', '', '', '', '', 0, 27, 0, 0, 0, 0, 0, 0, 0, 1, 0, '', '', 0);
INSERT IGNORE INTO `#__mt_customfields` VALUES (26, 'coremetakey', 'Meta Keys', '', 0, '', '', '', '', '', 0, 23, 0, 0, 0, 0, 0, 0, 1, 1, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (27, 'coremetadesc', 'Meta Description', '', 0, '', '', '', '', '', 0, 24, 0, 0, 0, 0, 0, 0, 0, 1, 0, '', '', 1);
INSERT IGNORE INTO `#__mt_customfields` VALUES (28, 'mtags', 'Tags', '', 40, '', '', '', '', '', 0, 25, 0, 0, 1, 0, 0, 0, 1, 1, 1, '', '', 0);

CREATE TABLE IF NOT EXISTS `#__mt_favourites` (
  `fav_id` int(11) NOT NULL auto_increment,
  `link_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fav_date` datetime NOT NULL,
  PRIMARY KEY  (`fav_id`),
  KEY `link_id` (`link_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_fieldtypes` (
  `ft_id` int(11) NOT NULL auto_increment,
  `field_type` varchar(36) NOT NULL,
  `ft_caption` varchar(255) NOT NULL,
  `ft_class` mediumtext NOT NULL,
  `use_elements` tinyint(3) unsigned NOT NULL default '0',
  `use_size` tinyint(3) unsigned NOT NULL default '0',
  `use_columns` tinyint(3) unsigned NOT NULL default '0',
  `taggable` tinyint(3) NOT NULL default '0',
  `iscore` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ft_id`),
  UNIQUE KEY `field_type` (`field_type`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (1, 'corerating', 'Rating', 'class mFieldType_corerating extends mFieldType_number {\r\n	var $name = ''link_rating'';\r\n	var $numOfSearchFields = 2;\r\n	var $numOfInputFields = 0;\r\n	function getOutput($view=1) {\r\n		return round($this->getValue(),2);\r\n	}\r\n	function getJSValidation() {\r\n		return null;\r\n	}\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (2, 'coreprice', 'Price', 'class mFieldType_coreprice extends mFieldType_number {\r\n	var $name = ''price'';\r\n	function getOutput() {\r\n		$price = $this->getValue();\r\n		$displayFree = $this->getParam(''displayFree'',1);\r\n		if($price == 0 && $displayFree == 1) {\r\n			return JText::_( ''FREE'' );\r\n		} else {\r\n			return $price;\r\n		}\r\n	}\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (3, 'coreaddress', 'Address', 'class mFieldType_coreaddress extends mFieldType {\r\n	var $name = ''address'';\r\n}\r\n', 0, 1, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (4, 'corecity', 'City', 'class mFieldType_corecity extends mFieldType {\r\n	var $name = ''city'';\r\n}\r\n', 1, 1, 0, 1, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (5, 'corestate', 'State', 'class mFieldType_corestate extends mFieldType {\r\n	var $name = ''state'';\r\n}\r\n', 1, 1, 0, 1, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (6, 'corecountry', 'Country', 'class mFieldType_corecountry extends mFieldType {\r\n	var $name = ''country'';\r\n}\r\n', 1, 0, 0, 1, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (7, 'corepostcode', 'Postcode', 'class mFieldType_corepostcode extends mFieldType {\r\n	var $name = ''postcode'';\r\n}\r\n', 0, 1, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (8, 'coretelephone', 'Telephone', 'class mFieldType_coretelephone extends mFieldType {\r\n	var $name = ''telephone'';\r\n}\r\n', 0, 1, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (9, 'corefax', 'Fax', 'class mFieldType_corefax extends mFieldType {\r\n	var $name = ''fax'';\r\n}\r\n', 0, 1, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (10, 'coreemail', 'E-mail', 'class mFieldType_coreemail extends mFieldType_email {\r\n	var $name = ''email'';\r\n}\r\n', 0, 1, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (11, 'corewebsite', 'Website', 'class mFieldType_corewebsite extends mFieldType_weblink {\r\n	var $name = ''website'';\r\n\r\n	function getOutput() {\r\n		$maxUrlLength = $this->getParam(''maxUrlLength'',60);\r\n		$text = $this->getParam(''text'','''');\r\n		$openNewWindow = $this->getParam(''openNewWindow'',1);\r\n		$useMTVisitRedirect = $this->getParam(''useMTVisitRedirect'',1);\r\n		$hideProtocolOutput = $this->getParam(''hideProtocolOutput'',1);\r\n	\r\n		$html = '''';\r\n		$html .= ''<a href="'';\r\n		if($useMTVisitRedirect) {\r\n			global $Itemid;\r\n			$html .= JRoute::_(''index.php?option=com_mtree&task=visit&link_id='' . $this->getLinkId() . ''&Itemid='' . $Itemid);\r\n		} else {\r\n			$html .= $this->getValue();\r\n		}\r\n		$html .= ''"'';\r\n		if( $openNewWindow == 1 ) {\r\n			$html .= '' target="_blank"'';\r\n		}\r\n		$html .= ''>'';\r\n		if(!empty($text)) {\r\n			$html .= $text;\r\n		} else {\r\n			$value = $this->getValue();\r\n			if(strpos($value,''://'') !== false && $hideProtocolOutput) {\r\n				$value = substr($value,(strpos($value,''://'')+3));\r\n\r\n				// If $value has a single slash and this is at the end of the string, we can safely remove this.\r\n				if( substr($value,-1) == ''/'' && substr_count($value,''/'') == 1 )\r\n				{\r\n					$value = substr($value,0,-1);\r\n				}\r\n			}\r\n			if( empty($maxUrlLength) || $maxUrlLength == 0 ) {\r\n				$html .= $value;\r\n			} else {\r\n				$html .= substr($value,0,$maxUrlLength);\r\n				if( strlen($value) > $maxUrlLength ) {\r\n					$html .= $this->getParam(''clippedSymbol'');\r\n				}\r\n			}\r\n		}\r\n		$html .= ''</a>'';\r\n		return $html;\r\n	}\r\n	\r\n	function getInputHTML() {\r\n		$showGo = $this->getParam(''showGo'',1);\r\n		$showSpider = $this->getParam(''showSpider'',0);\r\n		$inBackEnd = (substr(dirname($_SERVER[''PHP_SELF'']),-13) == ''administrator'') ? true : false;\r\n		$html = '''';\r\n		$html .= ''<input class="text_area inputbox" type="text" name="'' . $this->getInputFieldName(1) . ''" id="'' . $this->getInputFieldName(1) . ''" size="'' . ($this->getSize()?$this->getSize():''30'') . ''" value="'' . htmlspecialchars($this->getValue()) . ''" />'';\r\n		if($showGo && $inBackEnd) {\r\n			$html .= ''&nbsp;'';\r\n			$html .= ''<input type="button" class="button" onclick=\\'''';\r\n			$html .= ''javascript:window.open("index3.php?option=com_mtree&task=openurl&url="+escape(document.getElementById("website").value))\\'''';\r\n			$html .= ''value="'' . JText::_( ''Go'' ) . ''" />'';\r\n		}\r\n		\r\n		if($showSpider && $inBackEnd) {\r\n			$html .= ''&nbsp;'';\r\n			$html .= ''<input type="button" class="button" onclick=\\'''';\r\n			$html .= ''javascript: '';\r\n			$html .= ''jQuery("#spiderwebsite").html("'' . JText::_( ''SPIDER PROGRESS'' ) . ''");'';\r\n			$html .= ''jQuery.ajax({\r\n			  type: "POST",\r\n			  url: mosConfig_live_site+"/administrator/index.php",\r\n			  data: "option=com_mtree&task=ajax&task2=spiderurl&url="+document.getElementById("website").value+"&no_html=1",\r\n			  dataType: "script"\r\n			});'';\r\n			$html .= ''\\'''';\r\n			$html .= ''value="'' . JText::_( ''SPIDER'' ) . ''" />'';\r\n			$html .= ''<span id="spider'' . $this->getInputFieldName(1) . ''" style="margin-left:5px;background-color:white"></span>'';\r\n		}\r\n		return $html;\r\n	}\r\n	\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (12, 'corehits', 'Hits', 'class mFieldType_corehits extends mFieldType_number {\r\n	var $name = ''link_hits'';\r\n	var $numOfInputFields = 0;\r\n}\r\n', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (13, 'corevotes', 'Votes', 'class mFieldType_corevotes extends mFieldType_number {\r\n	var $name = ''link_votes'';\r\n	var $numOfInputFields = 0;\r\n}\r\n', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (14, 'corefeatured', 'Featured', 'class mFieldType_corefeatured extends mFieldType {\r\n	var $name = ''link_featured'';\r\n	var $numOfInputFields = 0;\r\n	function getOutput() {\r\n		$featured = $this->getValue();\r\n		$html = '''';\r\n		if($featured) {\r\n			$html .= JText::_( ''Yes'' );\r\n		} else {\r\n			$html .= JText::_( ''No'' );\r\n		}\r\n		return $html;\r\n	}\r\n	function getSearchHTML() {\r\n		$html = ''<select name="'' . $this->getSearchFieldName(1) . ''" class="inputbox text_area" size="1">'';\r\n		$html .= ''<option value="-1" selected="selected">'' . JText::_( ''Any'' ) . ''</option>'';\r\n		$html .= ''<option value="1">'' . JText::_( ''FEATURED ONLY'' ) . ''</option>'';\r\n		$html .= ''<option value="0">'' . JText::_( ''NON FEATURED ONLY'' ) . ''</option>'';\r\n		$html .= ''</select>'';\r\n		return $html;\r\n	}\r\n	\r\n	function getWhereCondition() {\r\n		$args = func_get_args();\r\n\r\n		$fieldname = $this->getName();\r\n		\r\n		if(  is_numeric($args[0]) ) {\r\n			switch($args[0]) {\r\n				case -1:\r\n					return null;\r\n					break;\r\n				case 1:\r\n					return $fieldname . '' = 1'';\r\n					break;\r\n				case 0:\r\n				return $fieldname . '' = 0'';\r\n					break;\r\n			}\r\n		} else {\r\n			return null;\r\n		}\r\n	}\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (15, 'coremodified', 'Modified', 'class mFieldType_coremodified extends mFieldType_date {\r\n	var $name = ''link_modified'';\r\n	var $numOfInputFields = 0;\r\n	function getOutput() {\r\n		$value = $this->getValue();\r\n		if($value == ''0000-00-00 00:00:00'') {\r\n			return JText::_( ''NEVER'' );\r\n		} else {\r\n			$dateFormat = $this->getParam(''dateFormat'',''%Y-%m-%d'');\r\n			return strftime($dateFormat,mktime(0,0,0,intval(substr($value,5,2)),intval(substr($value,8,2)),intval(substr($value,0,4))));\r\n		}\r\n	}\r\n\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (16, 'corevisited', 'Visited', 'class mFieldType_corevisited extends mFieldType_number {\r\n	var $name = ''link_visited'';\r\n	var $numOfInputFields = 0;\r\n	function getJSValidation() {\r\n		return null;\r\n	}\r\n}\r\n', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (17, 'corepublishup', 'Publish Up', 'class mFieldType_corepublishup extends mFieldType {\r\n	var $name = ''publish_up'';\r\n	var $numOfSearchFields = 0;\r\n	var $numOfInputFields = 0;\r\n}\r\n', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (18, 'corepublishdown', 'Publish Down', 'class mFieldType_corepublishdown extends mFieldType {\r\n	var $name = ''publish_down'';\r\n	var $numOfSearchFields = 0;\r\n	var $numOfInputFields = 0;\r\n}\r\n', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (19, 'coreuser', 'Owner', 'class mFieldType_coreuser extends mFieldType {\r\n	var $name = ''user_id'';\r\n	var $numOfSearchFields = 0;\r\n	var $numOfInputFields = 0;\r\n	\r\n	function getOutput() {\r\n		$html = ''<a href="'' . JRoute::_(''index.php?option=com_mtree&amp;task=viewowner&amp;user_id='' . $this->getValue(1)) . ''">'';\r\n		$html .= $this->getValue(2);\r\n		$html .= ''</a>'';\r\n		return $html;\r\n	}\r\n}\r\n', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (20, 'corename', 'Name', 'class mFieldType_corename extends mFieldType {\r\n	var $name = ''link_name'';\r\n	function getOutput($view=1) {\r\n		$params[''maxSummaryChars''] = intval($this->getParam(''maxSummaryChars'',55));\r\n		$params[''maxDetailsChars''] = intval($this->getParam(''maxDetailsChars'',0));\r\n		$value = $this->getValue();\r\n		$output = '''';\r\n		if($view == 1 AND $params[''maxDetailsChars''] > 0 AND JString::strlen($value) > $params[''maxDetailsChars'']) {\r\n			$output .= JString::substr($value,0,$params[''maxDetailsChars'']);\r\n			$output .= ''...'';\r\n		} elseif($view == 2 AND $params[''maxSummaryChars''] > 0 AND JString::strlen($value) > $params[''maxSummaryChars'']) {\r\n			$output .= JString::substr($value,0,$params[''maxSummaryChars'']);\r\n			$output .= ''...'';\r\n		} else {\r\n			$output = $value;\r\n		}\r\n		return $output;\r\n	}\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (21, 'coredesc', 'Description', 'class mFieldType_coredesc extends mFieldType {\r\n	var $name = ''link_desc'';\r\n	function parseValue($value) {\r\n		$params[''maxChars''] = intval($this->getParam(''maxChars'',3000));\r\n		$params[''stripAllTagsBeforeSave''] = $this->getParam(''stripAllTagsBeforeSave'',0);\r\n		$params[''allowedTags''] = $this->getParam(''allowedTags'',''u,b,i,a,ul,li,pre,blockquote,strong,em'');\r\n		if($params[''stripAllTagsBeforeSave'']) {\r\n			$value = $this->stripTags($value,$params[''allowedTags'']);\r\n		}\r\n		if($params[''maxChars''] > 0) {\r\n			$value = JString::substr( $value, 0, $params[''maxChars'']);\r\n		}\r\n		return $value;\r\n	}\r\n	function getInputHTML() {\r\n		global $mtconf;\r\n		\r\n		if( ($this->inBackEnd() AND $mtconf->get(''use_wysiwyg_editor_in_admin'')) || (!$this->inBackEnd() AND $mtconf->get(''use_wysiwyg_editor'')) ) {\r\n			$editor = &JFactory::getEditor();\r\n			$html = $editor->display( $this->getInputFieldName(1), $this->getValue() , ''100%'', ''250'', ''75'', ''25'', array(''pagebreak'', ''readmore'') );\r\n		} else {\r\n			$html = ''<textarea class="inputbox" name="'' . $this->getInputFieldName(1) . ''" style="width:95%;height:'' . $this->getSize() . ''px">'' . htmlspecialchars($this->getValue()) . ''</textarea>'';\r\n		}\r\n		return $html;\r\n	}\r\n	function getSearchHTML() {\r\n		return ''<input class="inputbox" type="text" name="'' . $this->getName() . ''" size="30" />'';\r\n	}\r\n	function getOutput($view=1) {\r\n		$params[''parseUrl''] = $this->getParam(''parseUrl'',1);\r\n		$params[''summaryChars''] = $this->getParam(''summaryChars'',255);\r\n		$params[''stripSummaryTags''] = $this->getParam(''stripSummaryTags'',1);\r\n		$params[''stripDetailsTags''] = $this->getParam(''stripDetailsTags'',1);\r\n		$params[''parseMambots''] = $this->getParam(''parseMambots'',0);\r\n		$params[''allowedTags''] = $this->getParam(''allowedTags'',''u,b,i,a,ul,li,pre,blockquote,strong,em'');\r\n		$params[''showReadMore''] = $this->getParam(''showReadMore'',0);\r\n		$params[''whenReadMore''] = $this->getParam(''whenReadMore'',0);\r\n		$params[''txtReadMore''] = $this->getParam(''txtReadMore'',JTEXT::_( ''Read More...'' ));\r\n		\r\n		$html = $this->getValue();\r\n		$output = '''';\r\n		\r\n		// Details view\r\n		if($view == 1) {\r\n			global $mtconf;\r\n			$output = $html;\r\n			if($params[''stripDetailsTags'']) {\r\n				$output = $this->stripTags($output,$params[''allowedTags'']);\r\n			}\r\n			if($params[''parseUrl'']) {\r\n				$regex = ''/http:\\/\\/(.*?)(\\s|$)/i'';\r\n				$output = preg_replace_callback( $regex, array($this,''linkcreator''), $output );\r\n			}\r\n			if (!$mtconf->get(''use_wysiwyg_editor'') && $params[''stripDetailsTags''] && !in_array(''br'',explode('','',$params[''allowedTags''])) && !in_array(''p'',explode('','',$params[''allowedTags''])) ) {\r\n				$output = nl2br(trim($output));\r\n			}\r\n			if($params[''parseMambots'']) {\r\n				$this->parseMambots($output);\r\n			}\r\n		// Summary view\r\n		} else {\r\n			$html = preg_replace(''@{[\\/\\!]*?[^<>]*?}@si'', '''', $html);\r\n			if($params[''stripSummaryTags'']) {\r\n				$html = strip_tags( $html );\r\n			}\r\n			if($params[''summaryChars''] > 0) {\r\n				$trimmed_desc = trim(JString::substr($html,0,$params[''summaryChars'']));\r\n			} else {\r\n				$trimmed_desc = '''';\r\n			}\r\n			if($params[''stripSummaryTags'']) {\r\n				$html = htmlspecialchars( $html );\r\n				$trimmed_desc = htmlspecialchars( $trimmed_desc );\r\n			}\r\n			if (JString::strlen($html) > $params[''summaryChars'']) {\r\n				$output .= $trimmed_desc;\r\n				$output .= '' <b>...</b>'';\r\n			} else {\r\n				$output = $html;\r\n			}\r\n			if( $params[''showReadMore''] && ($params[''whenReadMore''] == 1 || ($params[''whenReadMore''] == 0 && JString::strlen($html) > $params[''summaryChars''])) ) {\r\n				if(!empty($trimmed_desc)) {\r\n					$output .= ''<br />'';\r\n				}\r\n				$output .= '' <a href="'' . JRoute::_(''index.php?option=com_mtree&task=viewlink&link_id='' . $this->getLinkId()) . ''" class="readon">'' . $params[''txtReadMore''] . ''</a>'';\r\n			}\r\n		}\r\n		return $output;\r\n	}\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (22, 'corecreated', 'Created', 'class mFieldType_corecreated extends mFieldType_date {\r\n	var $name = ''link_created'';\r\n	var $numOfInputFields = 0;\r\n	function parseValue($value) {\r\n		return strip_tags($value);\r\n	}\r\n}', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (23, 'weblinknewwin', 'Web link', 'class mFieldType_weblinkNewWin extends mFieldType_weblink {\r\n\r\n}', 1, 1, 1, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (24, 'audioplayer', 'Audio Player', 'class mFieldType_audioplayer extends mFieldType_file {\r\n	function getJSValidation() {\r\n\r\n		$js = '''';\r\n		$js .= ''} else if (!hasExt(form.'' . $this->getName() . ''.value,\\''mp3\\'')) {''; \r\n		$js .= ''alert("'' . addslashes($this->getCaption()) . '': Please select a mp3 file.");'';\r\n		return $js;\r\n	}\r\n	function getOutput() {\r\n		$id = $this->getId();\r\n		$params[''text''] = $this->getParam(''textColour'');\r\n		$params[''displayfilename''] = $this->getParam(''displayfilename'',1);\r\n		$params[''slider''] = $this->getParam(''sliderColour'');\r\n		$params[''loader''] = $this->getParam(''loaderColour'');\r\n		$params[''track''] = $this->getParam(''trackColour'');\r\n		$params[''border''] = $this->getParam(''borderColour'');\r\n		$params[''bg''] = $this->getParam(''backgroundColour'');\r\n		$params[''leftbg''] = $this->getParam(''leftBackgrounColour'');\r\n		$params[''rightbg''] = $this->getParam(''rightBackgrounColour'');\r\n		$params[''rightbghover''] = $this->getParam(''rightBackgroundHoverColour'');\r\n		$params[''lefticon''] = $this->getParam(''leftIconColour'');\r\n		$params[''righticon''] = $this->getParam(''rightIconColour'');\r\n		$params[''righticonhover''] = $this->getParam(''rightIconHoverColour'');\r\n		\r\n		$html = '''';\r\n		$html .= ''<script language="JavaScript" src="'' . $this->getFieldTypeAttachmentURL(''audio-player.js''). ''"></script>'';\r\n		$html .= "\\n" . ''<object type="application/x-shockwave-flash" data="'' . $this->getFieldTypeAttachmentURL(''player.swf''). ''" id="audioplayer'' . $id . ''" height="24" width="290">'';\r\n		$html .= "\\n" . ''<param name="movie" value="'' . $this->getFieldTypeAttachmentURL(''player.swf'') . ''">'';\r\n		$html .= "\\n" . ''<param name="FlashVars" value="'';\r\n		$html .= ''playerID='' . $id;\r\n		$html .= ''&amp;soundFile='' . urlencode($this->getDataAttachmentURL());\r\n		foreach( $params AS $key => $value ) {\r\n			if(!empty($value)) {\r\n				$html .= ''&amp;'' . $key . ''=0x'' . $value;\r\n			}\r\n		}\r\n		$html .= ''">'';\r\n		$html .= "\\n" . ''<param name="quality" value="high">'';\r\n		$html .= "\\n" . ''<param name="menu" value="false">'';\r\n		$html .= "\\n" . ''<param name="wmode" value="transparent">'';\r\n		$html .= "\\n" . ''</object>'';\r\n		if($params[''displayfilename'']) {\r\n			$html .= "\\n<br />";\r\n			$html .= "\\n" . ''<a href="'' . $this->getDataAttachmentURL() . ''" target="_blank">'';\r\n			$html .= $this->getValue();\r\n			$html .= ''</a>'';\r\n		}\r\n		return $html;\r\n	}\r\n}', 0, 0, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (25, 'image', 'Image', 'class mFieldType_image extends mFieldType_file {\r\n	function parseValue($value) {\r\n		global $mtconf;\r\n		$params[''size''] = intval(trim($this->getParam(''size'')));\r\n		if($params[''size''] <= 0) {\r\n			$size = $mtconf->get(''resize_listing_size'');\r\n		} else {\r\n			$size = intval($params[''size'']);\r\n		}\r\n		$mtImage = new mtImage();\r\n		$mtImage->setMethod( $mtconf->get(''resize_method'') );\r\n		$mtImage->setQuality( $mtconf->get(''resize_quality'') );\r\n		$mtImage->setSize( $size );\r\n		$mtImage->setTmpFile( $value[''tmp_name''] );\r\n		$mtImage->setType( $value[''type''] );\r\n		$mtImage->setName( $value[''name''] );\r\n		$mtImage->setSquare(false);\r\n		$mtImage->resize();\r\n		$value[''data''] = $mtImage->getImageData();\r\n		$value[''size''] = strlen($value[''data'']);\r\n		\r\n		return $value;\r\n	}\r\n	function getJSValidation() {\r\n		$js = '''';\r\n		$js .= ''} else if (!hasExt(form.'' .$this->getInputFieldName(1) . ''.value,\\''gif|png|jpg|jpeg\\'')) {''; \r\n		$js .= ''alert("'' . addslashes($this->getCaption()) . '': Please select an image with one of these extensions - gif,png,jpg,jpeg.");'';\r\n		return $js;\r\n	}\r\n	function getOutput() {\r\n		$html = '''';\r\n		$html .= ''<img src="'' . $this->getDataAttachmentURL() . ''" />'';\r\n		return $html;\r\n	}\r\n	function getInputHTML() {\r\n		$html = '''';\r\n		if( $this->attachment > 0 ) {\r\n			$html .= $this->getKeepFileCheckboxHTML($this->attachment);\r\n			$html .= ''<label for="'' . $this->getKeepFileName() . ''"><img src="'' . $this->getDataAttachmentURL() . ''" hspace="5" vspace="0" /></label>'';\r\n			$html .= ''</br >'';\r\n		}\r\n		$html .= ''<input class="inputbox" type="file" name="'' . $this->getInputFieldName(1) . ''" />'';\r\n		return $html;\r\n	}\r\n	\r\n}', 0, 0, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (26, 'multilineTextbox', 'Multi-line Textbox', 'class mFieldType_multilineTextbox extends mFieldType {\r\n	function parseValue($value) {\r\n		$params[''stripAllTagsBeforeSave''] = $this->getParam(''stripAllTagsBeforeSave'',0);\r\n		$params[''allowedTags''] = $this->getParam(''allowedTags'',''u,b,i,a,ul,li,pre,br,blockquote'');\r\n		if($params[''stripAllTagsBeforeSave'']) {\r\n			$value = $this->stripTags($value,$params[''allowedTags'']);\r\n		}\r\n		return $value;		\r\n	}\r\n	function getInputHTML() {\r\n		$params[''cols''] = $this->getParam(''cols'',60);\r\n		$params[''rows''] = $this->getParam(''rows'',6);\r\n		$params[''style''] = $this->getParam(''style'','''');\r\n		$html = '''';\r\n		$html .= ''<textarea name="'' . $this->getInputFieldName(1) . ''" id="'' . $this->getInputFieldName(1) . ''" class="inputbox"'';\r\n		$html .= '' cols="'' . $params[''cols''] . ''" rows="'' . $params[''rows''] . ''"'';\r\n		if(!empty($params[''style''])) {\r\n			$html .=  '' style="'' . $params[''style''] . ''"'';\r\n		}\r\n		$html .=  ''>'' . $this->getValue() . ''</textarea>'';\r\n		return $html;\r\n	}\r\n	function getSearchHTML() {\r\n		return ''<input class="inputbox" type="text" name="'' . $this->getName() . ''" size="30" />'';\r\n	}\r\n	function getOutput($view=1) {\r\n		$params[''parseUrl''] = $this->getParam(''parseUrl'',1);\r\n		$params[''summaryChars''] = $this->getParam(''summaryChars'',255);\r\n		$params[''stripSummaryTags''] = $this->getParam(''stripSummaryTags'',1);\r\n		$params[''stripDetailsTags''] = $this->getParam(''stripDetailsTags'',1);\r\n		$params[''allowedTags''] = $this->getParam(''allowedTags'',''u,b,i,a,ul,li,pre,br,blockquote'');\r\n	\r\n		$html = $this->getValue();\r\n	\r\n		// Details view\r\n		if($view == 1) {\r\n			if($params[''stripDetailsTags'']) {\r\n				$html = $this->stripTags($html,$params[''allowedTags'']);\r\n			}\r\n			if($params[''parseUrl''] AND $view == 0) {\r\n				$regex = ''/http:\\/\\/(.*?)(\\s|$)/i'';\r\n				$html = preg_replace_callback( $regex, array($this,''linkcreator''), $html );\r\n			}\r\n		// Summary view\r\n		} else {\r\n			$html = preg_replace(''@{[\\/\\!]*?[^<>]*?}@si'', '''', $html);\r\n			if($params[''stripSummaryTags'']) {\r\n				$html = strip_tags( $html );\r\n			} else {\r\n			}\r\n			$trimmed_desc = trim(JString::substr($html,0,$params[''summaryChars'']));\r\n			if (JString::strlen($html) > $params[''summaryChars'']) {\r\n				$html = $trimmed_desc . '' <b>...</b>'';\r\n			}\r\n		}\r\n		return $html;\r\n	}\r\n}', 0, 0, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (29, 'onlinevideo', 'Online Video', 'class mFieldType_onlinevideo extends mFieldType {\r\n\r\n	function getOutput() {\r\n		$html ='''';\r\n		$id = $this->getVideoId();\r\n		$videoProvider = $this->getParam(''videoProvider'',''youtube'');\r\n		switch($videoProvider) {\r\n			case ''youtube'':\r\n				$params[''youtubeWidth''] = $this->getParam(''youtubeWidth'',425);\r\n				$params[''youtubeHeight''] = $this->getParam(''youtubeHeight'',350);\r\n				$html .= ''<object width="'' . $params[''youtubeWidth''] . ''" height="'' . $params[''youtubeHeight''] . ''">'';\r\n				$html .= ''<param name="movie" value="http://www.youtube.com/v/'' . $id . ''"></param>'';\r\n				$html .= ''<param name="wmode" value="transparent"></param>'';\r\n				$html .= ''<embed src="http://www.youtube.com/v/'' . $id . ''" type="application/x-shockwave-flash" wmode="transparent" width="'' . $params[''youtubeWidth''] . ''" height="'' . $params[''youtubeHeight''] . ''"></embed>'';\r\n				$html .= ''</object>'';\r\n				break;\r\n			case ''googlevideo'':\r\n				$html .= ''<embed style="width:400px; height:326px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId='' . $id . ''">'';\r\n				$html .= ''</embed>'';\r\n				break;\r\n			/*\r\n			case ''metacafe'':\r\n				$html .= ''<embed src="http://www.metacafe.com/fplayer/'' . $id . ''.swf" width="400" height="345" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>'';\r\n				break;\r\n			case ''ifilm'':\r\n				$html .= ''<embed width="448" height="365" src="http://www.ifilm.com/efp" quality="high" bgcolor="000000" name="efp" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="flvbaseclip='' . $id . ''&amp;"></embed>'';\r\n				break;\r\n			*/\r\n		}\r\n		return $html;\r\n	}\r\n	\r\n	function getVideoId() {\r\n		$videoProvider = $this->getParam(''videoProvider'',''youtube'');\r\n		$value = $this->getValue();\r\n		$id = null;\r\n		if(empty($value)) {\r\n			return null;\r\n		}\r\n		$url = parse_url($value);\r\n	    parse_str($url[''query''], $query);\r\n		switch($videoProvider) {\r\n			case ''youtube'':\r\n				if (isset($query[''v''])) {\r\n			        $id = $query[''v''];\r\n			    }\r\n				break;\r\n			case ''googlevideo'':\r\n			    if (isset($query[''docid''])) {\r\n			        $id = $query[''docid''];\r\n			    }\r\n				break;\r\n		}\r\n		return $id;\r\n	}\r\n	\r\n	function getInputHTML() {\r\n		$videoProvider = $this->getParam(''videoProvider'',''youtube'');\r\n		$youtubeInputDescription = $this->getParam(''youtubeInputDescription'',''Enter the full URL of the Youtube video page.<br />ie: <b>http://youtube.com/watch?v=OHpANlSG7OI</b>'');\r\n		$googlevideoInputDescription = $this->getParam(''googlevideoInputDescription'',''Enter the full URL of the Google video page.<br />ie: <b>http://video.google.com/videoplay?docid=832064557062572361</b>'');\r\n		$html = '''';\r\n		$html .= ''<input class="text_area" type="text" name="'' . $this->getInputFieldName(1) . ''" id="'' . $this->getInputFieldName(1) . ''" size="'' . $this->getSize() . ''" value="'' . htmlspecialchars($this->getValue()) . ''" />'';\r\n		switch($videoProvider) {\r\n			case ''youtube'':\r\n				if(!empty($youtubeInputDescription)) {\r\n					$html .= ''<br />'' . $youtubeInputDescription;\r\n				}\r\n				break;\r\n			case ''googlevideo'':\r\n				if(!empty($googlevideoInputDescription)) {\r\n					$html .= ''<br />'' . $googlevideoInputDescription;\r\n				}\r\n		}\r\n		return $html;\r\n	}\r\n	\r\n	function getSearchHTML() {\r\n		$checkboxLabel = $this->getParam(''checkboxLabel'',''Contains video'');\r\n		return ''<input class="text_area" type="checkbox" name="'' . $this->getSearchFieldName(1) . ''" id="'' . $this->getSearchFieldName(1) . ''" />&nbsp;<label for="'' . $this->getName() . ''">'' . $checkboxLabel . ''</label>'';\r\n	}\r\n	\r\n	function getWhereCondition() {\r\n		if( func_num_args() == 0 ) {\r\n			return null;\r\n		} else {\r\n			return ''(cfv#.value <> \\''\\'')'';\r\n		}\r\n	}\r\n}', 0, 1, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (30, 'coremetakey', 'Meta Keys', 'class mFieldType_coremetakey extends mFieldType {\r\n	var $name = ''metakey'';\r\n}', 0, 0, 0, 1, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (31, 'coremetadesc', 'Meta Description', 'class mFieldType_coremetadesc extends mFieldType {\r\n	var $name = ''metadesc'';\r\n}\r\n', 0, 0, 0, 0, 1);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (32, 'mtags', 'Tags', 'class mFieldType_mTags extends mFieldType_tags {\r\n\r\n}', 0, 1, 0, 1, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (45, 'videoplayer', 'Video Player', 'class mFieldType_videoplayer extends mFieldType_file {\r\n\r\n	function getOutput() {\r\n		$html ='''';\r\n		$filename = $this->getValue();\r\n		$format = $this->getParam(''format'');\r\n		$id = $format.$filename;\r\n		$width = $this->getParam(''width'');\r\n		$height = $this->getParam(''height'');\r\n		$autoplay = $this->getParam(''autoplay'',false);\r\n		if($autoplay) {\r\n			$autoplay = ''true'';\r\n		} else {\r\n			$autoplay = ''false'';\r\n		}\r\n		switch($format) {\r\n			case ''mov'':\r\n				$html .= ''<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="'' . $width . ''" height="'' . $height. ''" codebase="http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0" align="middle">'';\r\n				$html .= ''<param name="src" value="'' . $this->getDataAttachmentURL() . ''" />'';\r\n				$html .= ''<embed src="'' . $this->getDataAttachmentURL() . ''" type="video/quicktime" width="'' . $width . ''" height="'' . $height . ''" pluginspage="http://www.apple.com/quicktime/download/" align="middle" autoplay="'' . $autoplay . ''" />'';\r\n				$html .= ''</object>'';\r\n				break;\r\n			case ''divx'':\r\n				$html .= '''';\r\n				$html .= ''<object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="'' . $width . ''" height="'' . $height . ''" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">'';\r\n				$html .= ''<param name="src" value="'' . $this->getDataAttachmentURL() . ''" />'';\r\n				$html .= ''<param name="autoPlay" value="'' . $autoplay . ''" />'';\r\n				$html .= ''<embed src="'' . $this->getDataAttachmentURL() . ''" type="video/divx" width="'' . $width . ''" height="'' . $height . ''" autoPlay="'' . $autoplay . ''" pluginspage="http://go.divx.com/plugin/download/" />'';\r\n				$html .= ''</object>'';\r\n				break;\r\n			case ''windowsmedia'':\r\n				$html .= ''<object classid="CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6" id="'' . $id . ''" width="'' . $width . ''" height="'' . $height . ''" type="application/x-oleobject">'';\r\n				$html .= ''<param name="URL" value="'' . $this->getDataAttachmentURL() . ''" />'';\r\n				$html .= ''<param name="wmode" value="opaque" />'';\r\n				$html .= ''<param name="ShowControls" value="1" />'';\r\n				$html .= ''<param name="autoStart" value="'' . (($autoplay==''true'')?''1'':''0'') . ''" />'';\r\n				$html .= ''<embed src="'' . $this->getDataAttachmentURL() . ''" type="application/x-mplayer2" width="'' . $width . ''" height="'' . $height . ''" wmode="opaque" border="0" autoStart="'' . (($autoplay == ''true'')?''1'':''0'') . ''" />'';\r\n				$html .= ''</object>'';\r\n				break;\r\n		}\r\n		return $html;\r\n	}\r\n}', 0, 0, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (46, 'year', 'Year', 'class mFieldType_year extends mFieldType {\r\n	var $numOfSearchFields = 2;\r\n	function getSearchHTML() {\r\n		$startYear = $this->getParam(''startYear'',(date(''Y'')-70));\r\n		$endYear = $this->getParam(''endYear'',date(''Y''));\r\n		\r\n		$html = ''<select name="'' . $this->getSearchFieldName(2) . ''" class="inputbox" size="1">'';\r\n		$html .= ''<option value="1" selected="selected">'' . JText::_( ''EXACTLY'' ) . ''</option>'';\r\n		$html .= ''<option value="2">'' . JText::_( ''AFTER'' ) . ''</option>'';\r\n		$html .= ''<option value="3">'' . JText::_( ''BEFORE'' ) . ''</option>'';\r\n		$html .= ''</select>'';\r\n		$html .= ''&nbsp;'';\r\n\r\n		$html .= ''<select name="'' . $this->getInputFieldName(1) . ''" class="inputbox">'';\r\n		$html .= ''<option value="">&nbsp;</option>'';\r\n		for($year=$endYear;$year>=$startYear;$year--) {\r\n			$html .= ''<option value="'' . $year . ''">'' . $year . ''</option>'';\r\n		}\r\n		$html .= ''</select>'';		\r\n\r\n		return $html;\r\n	}\r\n\r\n	function getInputHTML() {\r\n		$startYear = $this->getParam(''startYear'',(date(''Y'')-70));\r\n		$endYear = $this->getParam(''endYear'',date(''Y''));\r\n		$value = $this->getValue();\r\n		\r\n		$html = '''';\r\n		$html .= ''<select name="'' . $this->getInputFieldName() . ''" class="inputbox">'';\r\n		$html .= ''<option value="">&nbsp;</option>'';\r\n		for($year=$endYear;$year>=$startYear;$year--) {\r\n			$html .= ''<option value="'' . $year . ''"'';\r\n			if( $year == $value ) {\r\n				$html .= '' selected'';\r\n			}\r\n			$html .= ''>'' . $year . ''</option>'';\r\n		}\r\n		$html .= ''</select>'';		\r\n		return $html;\r\n	}\r\n	\r\n	function getWhereCondition() {\r\n		$args = func_get_args();\r\n		$fieldname = ''cfv#.value'';\r\n		if( ($args[1] >= 1 || $args[1] <= 3) && is_numeric($args[0]) ) {\r\n			switch($args[1]) {\r\n				case 1:\r\n					return $fieldname . '' = \\'''' . $args[0] . ''\\'''';\r\n					break;\r\n				case 2:\r\n					return $fieldname . '' > \\'''' . $args[0] . ''\\'''';\r\n					break;\r\n				case 3:\r\n					return $fieldname . '' < \\'''' . $args[0] . ''\\'''';\r\n					break;\r\n			}\r\n		} else {\r\n			return null;\r\n		}\r\n	}	\r\n}', 0, 0, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (47, 'mdate', 'Date', 'class mFieldType_mDate extends mFieldType_date {\r\n}', 0, 0, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (48, 'mfile', 'File', 'class mFieldType_mFile extends mFieldType_file {\r\n	function getOutput() {\r\n	global $mtconf;\r\n	\r\n		$html = '''';\r\n		$showCounter 	= $this->getParam(''showCounter'',1);\r\n		$useImage		= $this->getParam(''useImage'','''');\r\n		$showFilename	= $this->getParam(''showFilename'',1);\r\n		$showText		= $this->getParam(''showText'','''');\r\n		if(!empty($this->value))\r\n		{\r\n			$html .= ''<a href="'' . $this->getDataAttachmentURL() . ''" target="_blank">'';\r\n			if( !empty($useImage) )\r\n			{\r\n				$live_site = $mtconf->getjconf(''live_site'');\r\n				$html .= ''<img src="'' . trim(str_replace(''{live_site}'',$live_site,$useImage)) . ''"'';\r\n				$html .= '' alt=""'';\r\n				$html .= '' /> '';\r\n			} \r\n\r\n			if( !empty($showText) )\r\n			{\r\n				$html .= $showText . '' '';\r\n			}\r\n			\r\n			if( $showFilename == 1 )\r\n			{\r\n				$html .= $this->getValue();\r\n			}\r\n\r\n			$html .= ''</a>'';\r\n		}\r\n\r\n		$append_html = array();\r\n		if( $showCounter ) {\r\n			$append_html[] = JText::sprintf(''{{n}} views'', $this->counter);\r\n		}\r\n\r\n		if( !empty($append_html) ) {\r\n			$html .= '' ('' . implode('', '',$append_html) . '')'';\r\n		}\r\n		return $html;\r\n	}\r\n	function getJSValidation() {\r\n		$fileExtensions = $this->getParam(''fileExtensions'','''');\r\n		if(is_array($fileExtensions)) {\r\n			$fileExtensions = implode(''|'',$fileExtensions);\r\n		} else {\r\n			$fileExtensions = trim($fileExtensions);\r\n		}\r\n		if(!empty($fileExtensions)) {\r\n			$js = '''';\r\n			$js .= ''} else if (!hasExt(form.'' .$this->getInputFieldName(1) . ''.value,\\'''' . $fileExtensions . ''\\'')) {''; \r\n			$js .= ''alert("'' . addslashes($this->getCaption()) . '': Please select files with these extension(s) - '' . str_replace(''|'','', '',$fileExtensions) . ''.");'';\r\n			return $js;\r\n		} else {\r\n			return null;\r\n		}\r\n	}\r\n}', 0, 0, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (50, 'memail', 'E-mail', 'class mFieldType_mEmail extends mFieldType_email {}', 0, 1, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (51, 'mnumber', 'Number', 'class mFieldType_mNumber extends mFieldType_number {\r\n}', 0, 1, 0, 0, 0);
INSERT IGNORE INTO `#__mt_fieldtypes` VALUES (54, 'digg', 'Digg', 'class mFieldtype_digg extends mFieldType { \r\n    var $numOfSearchFields = 0; \r\n    var $numOfInputFields = 0; \r\n\r\n    function getOutput($view=1) { \r\n        global $mtconf, $Itemid; \r\n        $html = ''''; \r\n        $html .= ''<script type="text/javascript">''; \r\n        $html .= ''digg_url=\\''''; \r\n        $uri =& JURI::getInstance(); \r\n        if(substr($mtconf->getjconf(''live_site''),0,16) == ''http://localhost'') { \r\n            // Allow for debugging \r\n            $html .= str_replace(''http://localhost'',''http://127.0.0.1'',$uri->toString(array( ''scheme'', ''host'', ''port'' ))); \r\n        } else { \r\n            $html .= $uri->toString(array( ''scheme'', ''host'', ''port'' )); \r\n        } \r\n        $html .= JRoute::_(''index.php?option=com_mtree&task=viewlink&link_id=''.$this->getLinkId().''&Itemid=''.$Itemid, false) .''\\'';''; \r\n        // Display the compact version when displayed in Summary view \r\n        if($view==2) { \r\n            $html .= ''digg_skin = \\''compact\\'';''; \r\n        } \r\n        $html .= ''</script>''; \r\n        $html .= ''<script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>''; \r\n        return $html; \r\n    } \r\n}', 0, 0, 0, 0, 0);

CREATE TABLE IF NOT EXISTS `#__mt_fieldtypes_att` (
  `fta_id` int(11) NOT NULL auto_increment,
  `ft_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filedata` mediumblob NOT NULL,
  `filesize` int(11) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY  (`fta_id`),
  KEY `filename` (`filename`)
) ENGINE=MyISAM CHARACTER SET `utf8`;


DELETE IGNORE FROM `#__mt_fieldtypes_att` WHERE `ft_id` IN (2,24,25,45,46,47,26,20,11,29,22,15,48,23,21,32) AND `filename` = 'params.xml';
DELETE IGNORE FROM `#__mt_fieldtypes_att` WHERE `ft_id` = 24 AND `filename` = 'audio-player.js' LIMIT 1;
DELETE IGNORE FROM `#__mt_fieldtypes_att` WHERE `ft_id` = 24 AND `filename` = 'player.swf' LIMIT 1;

INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 2, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d22646973706c6179467265652220747970653d22726164696f222064656661756c743d223122206c6162656c3d22446973706c61792046726565207768656e207072696365206973203022206465736372697074696f6e3d2253657474696e67207468697320746f205965732077696c6c20646973706c61792074686520746578742046726565207768656e2074686520707269636520697320302e30302e223e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a09093c2f706172616d3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 313, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 24, 'audio-player.js', 0x7661722061705f696e7374616e636573203d206e657720417272617928293b0d0a0d0a66756e6374696f6e2061705f73746f70416c6c28706c61796572494429207b0d0a09666f72287661722069203d20303b693c61705f696e7374616e6365732e6c656e6774683b692b2b29207b0d0a0909747279207b0d0a09090969662861705f696e7374616e6365735b695d20213d20706c6179657249442920646f63756d656e742e676574456c656d656e74427949642822617564696f706c6179657222202b2061705f696e7374616e6365735b695d2e746f537472696e672829292e5365745661726961626c652822636c6f7365506c61796572222c2031293b0d0a090909656c736520646f63756d656e742e676574456c656d656e74427949642822617564696f706c6179657222202b2061705f696e7374616e6365735b695d2e746f537472696e672829292e5365745661726961626c652822636c6f7365506c61796572222c2030293b0d0a09097d20636174636828206572726f724f626a6563742029207b0d0a0909092f2f2073746f7020616e79206572726f72730d0a09097d0d0a097d0d0a7d0d0a0d0a66756e6374696f6e2061705f7265676973746572506c61796572732829207b0d0a09766172206f626a65637449443b0d0a09766172206f626a65637454616773203d20646f63756d656e742e676574456c656d656e747342795461674e616d6528226f626a65637422293b0d0a09666f722876617220693d303b693c6f626a656374546167732e6c656e6774683b692b2b29207b0d0a09096f626a6563744944203d206f626a656374546167735b695d2e69643b0d0a09096966286f626a65637449442e696e6465784f662822617564696f706c617965722229203d3d203029207b0d0a09090961705f696e7374616e6365735b695d203d206f626a65637449442e737562737472696e672831312c206f626a65637449442e6c656e677468293b0d0a09097d0d0a097d0d0a7d0d0a0d0a7661722061705f636c6561724944203d20736574496e74657276616c282061705f7265676973746572506c61796572732c2031303020293b, 791, 'application/x-javascript', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 24, 'player.swf', 0x435753062e3d0000789ced3b0b705b5576e74996f4fc91e33876fc89ec386c361f082121214020891cc7b14d1249c809211070644b5614cb9290e4c4e1d3188a09900f59169c1f01efb25b28d3d9a6ec14b6a53366d8663125bb43db650a85d92ccbec2c9ddd19b2d3366da705f79c7bef7befbea7a77c4a7f332d619eee3dbf7bceb9e79e7beef57b1928df0c300da0be11da1c5353536b9c4f2f02787c81fbf7615d241f839de9a16c0e32e95c229f48a72017cb77c7fad2a9688e9a9b12a9a17c8c353b195ddc40c70d745c432f817cba3b9f4da4e2b01272d93ec8a58752d1f589640c2032944fe7f2916c1ef6220bf58299580afa92e95c2c948cec8d656998be74328d826283fdfd10ecdd15ebcbc3e0de6e9202add96c642f6488343a988bf54122158d0d03cacc0fe5209a8dc4d10af6bb3b9d84eecdc150a87d1d8436b66eeb0a7440a8754b377693e9740659b2b1c82069b908729964220f89dcc674241a8b320369b4c0d0602f6a948ca5e2f99d9080cc506e27703d50cb3bd2c9a1c118d03849e4eb8e2551d15894e31984b5faf2d9e4da583c816ecdb361c97a027653971aa1c8502ec65b68176c8ae060599d37948ded4ea4d138ea0462c37948a798e4b6f4602619c3d943750dc5d7eec5c9b07437a7f39124f5988bb972f8932730736274281b61339f4a434f3c99ee45f24474d9e6481c67219b4d67714c9209f99d090c84a1bebe582e87b0ae75cb201ac9478818d0ae442e8fb39d8aa7228331eec554bc2bd59fa6763b93434a686186ed75dac0193ef9839161c3adf4d38f9a0687f2d03bd4df1f6331d5b6314873180cb507684ae997c1a8c3e208d218363dd9741a75c9c42203b16ccf601fc4d3f9746b2aca9c4e83419a028f0fdbb50e76457647727dd94426bf3292e9a1996a4d2617c042c038cf67d34912914e85718e23395a2fbb2952f3b476109a4e26830410cd219aa2f6543e965d9f254764687e7b122889a4f4ec4ee412bd490dcc44e948d2c7dc31e37bf32901e98df40dc4599468703324934dc7b3384ba676ef503ecf05a5532182403fae852c0c65701663adfda872fbee582a6f988ac6e412d118f40cb389a5c9247616c51842cc06fedcc857093a117dd09d8f6538b80d1542b180cdce08062d66081c3045719cc7c7a6486e409a1eb60008b93e114b46b1b5a78badef442a3394efc90b26d8c94781fe74764f241b05c3748a200d9b8ce48c60eba7a960ca403e8be45c2e2ee3369e6c06d11b91782cc70661b1da42118211d7d24fa94b4b8e0c4f0b08316c3af724a268f65a2d3e172f5e0c8389643291e32972b3960b7be39848faf3e2872614b289f84e02885f1e55ac63a0a925c22d891391e5daf3259c85de7496fd98a69e5944a6853bd6327f0d0a27d3c096302134a76772191513cda28a49a7d6c9abec8d528c4de30f94b1e950a294384a9c6e10ffa9e0dfaf805237a680131b0eade1ac1b7380aab4d2d3e11b7381e7332780b77eac143c0af29528aa335c67c152e356b090951427b3a57721bd17740511e20e73cdcc504f98ab390dd594e16ab869cc03250e4f19f63bab4f39a00188a8d42072a865e10e2e13299d17a1740a4ae10e66834e3d8b512b123d51201df234097a7c9677e0b3c4d961b41d1dc7f109c2d550c70660faf0b920e4eab112502b54af8fa8ba70b47220015e1fb2ba58b3928d318d8d51d5450a798198a6bb9426245245079ac69c48e66ca26e3575f17706d2a03fd51a43f55a3f9357e323f84c035ee70f0a7839a8f53a5c0236d8011b0b8038ec2c97dcf3b96486a602060fa8cd2c2a24c06c8fc32cb2c524729aa937c7a5480354a85731696a0573d9d7c241c3deb976f6e2f3eb275d049c0106a82240cf7981d541123a5f6764de9b4f2319b28490300b97106094bb789c0bcc5c266b0146bcccb5d08f96ca64337532346a3e0e12a2093e3c652875f5c952c0a54e95ceeb34efb80eaef1f1602ac78e2e9b29329d14d1419c0ad781e28962372e0209158dead82b337409c947710516ccf7b19fab2d867c6f8a8d121cabe063ca8c57f3ecc13b8bf86cec9466634ec03a33bcdd100890131d56150c2cfe5c2b0b9f6bc2e31383478a84c57cecbf92c66e94389a025dd564fb42b0f18bcdd0d7e943db2ea0027d024cfa4c3be98b2ce15374cc25fa98e8fd46816b0ec8562ee556de7b092b575fb195d2884da611afe7238e1619b139c0325b3f1872ea8b8fe70804bf8262b34d8a2de38a552bc55d818ae525bdfffb66cc5ea5d95ca51d924af50163d7d572ca7286b9216c9b64ae346056702fcd57aed4ef7c95497928cefcb54853de2b2f0b3dbf8c89fc591ec4bcd12c054583215327b9780cffcfcc9ca4d48d5fcd7576e957b7bd9399d0a89968ce3197745dc7ff52d75dd936f46f9296a6fde726eef8b3579cc42ecbf1bc302c24ba315c878edf2a29d512b804f1d5f6f35724a5ca5bea2fbe3486b9999bbbef1293e6355505ab2fcf372bc39747774bd8bb5a0aaa5bb94a7f71f1dc6ad5c896566c10df07ca18abfe73556ebae476d3841aaaab791a5de5f35a03ce44bd2650c162b69e0157fbbc452c12697cfcbfdca2faafaabe3c3b6c76e5c348a59462a68942bca508be45e0e730bcbf003f4794f5adac578350a9765f6bb31cb5ad8d8af92bcb1a4715ab17286be0a86d2eb93a33a11ab021e1d6cab85545d8f067dd492f15edda655d1533b89d99d8a6aef755078b3176f0e5f3b1a8f4db7ddab1d15080212cc9aad61fb4a30990133b8b1c806432ec76062ca7f02e06be2d705116e370cfc937142367fe5282e2382027b40e29a16de4e63782d5e57abccab96613a76e29d80d4cd1dd66e2095c168fc3c413bc2c9e3582a795856ea8607242ec947b3b3b076900f443987eb1777b400276bb6492cd620df1de0c0387f1bfc5bc79f041ee30ced61cb0d5385b73c09d1ea7598d46a1c6165d0d7c6ee3868f8001e2e98cb7b78aa2c1c5ee3b56170abbd36c9312d42f2a3039ddc5d7f1dd8c723b137b8fa590e0b87bc375924e3d5ca7fb8be8b4e5523add613948e863d0cf0e7e27c324d4b35b9608c2d8fd4e2f7b569e5084db385f1f1b3aca75bacfa413ddffc87af5f35b285c1186ff08d602b80856984b05612a2b1566d9a1b631945515941c7315c2fad57b0b548e73956ba05080381ccbd43b8b5343217582537f664962acd3cf28eeed12d7260cb62b30e60675005b492674c0459713cc8e1208729a419d2616a816b09404a3d2770358695345e5edb2919794e57150ba109431718a84a67be03e665f963d73dc0f2769bb976e6a44dab81e43ae0a3c7f8a102beae67005959db444cb2d87bb7c983b730e78be989a9af27c898f1285fd30324354bb6fbec851ec7a4e3187fdd0aabaa2aa237dd4c69cdd7a715dc2ee9a17884d0d9f7b029abe1dd4209bbcdafd52a171fc7e960b619752651457250e79afc30451cfb71687d74648103d842c4a45c1d9d7ee9a60997ceea09b4ae346560e0cdd13c3cc077b5d1618e6cd060f3a7aaa80f47e91a165d207d8057801f8412337eb60cce30f99f3b82cfc77ac7a1819a791ea68751ff7009f8a11867f385c57c0f3c8c94a2a4af4bfeb5ca3d8accfbdfcc88033e2665d61d8ef4a89b14ae01a104acbee1eb0ad49c48e1ea667a7b859d50f535de230e9658ca6032c56872a83329121b1ea1e65b5cc6851f15aa16190a3531f939cca5466f3c45afba9c546973d8f3c4bb4824677a4759d0ccb49ee889d131f65f3f6b80b0c6db8f95259ddb5113de0833a99e2093305dfc7d025f3c420f7b363e56dc2df0f09ab1fe0a78a16d06c8442cb50c80ce1d787186b88403f1192f633b951d075c104f798aed563629c07031d267b34b8a63bbf996800cdc1b2d371b00859b2fd9263945bc7b07aa401ece692ed8662a670169f948e05d83d60ee1e341f1af8b48a09bdc74d9d193e1aaad96eeb3dc4efb867b83873b5cfe65e44dfa4edaf14ac26cdd00a057618518c6b9c10ab25dbac223685a582a88d4dde29696b9613f9004d43f16a4be739cca847d5a78256d8807de527d11d3146b2a42e06d3ea3033b12299d0cd4ce895bca055c561963254d02efd64a8fed7bab976f72cd773eb8c7a1a9dbafc22852ccaa9b551e0f680795c8fdddf3064b77f8325aaa70b773d52791201765b963ec266e689566932b589f966e1c458371de17034f4c70502acb3f38c035e36bbad95616a02465a5dc594d92bc93a683af6f0ede03a0d8fa31cd47141ac6d74d9dcdc475651cddac4c8f7fab4ad445601298461c69f3907c43ad36e53b4b25abb23a212fa5a51566bb72c045bafc8b026063ba4a0d81607893da01ec0e327a10eb03ffdbab4b5b811ac09435f879bad931a606e7ad2d7450957ca393a5a4a2fe645a9fff5129fb3f896bbc96e05a3d83d56b1b786b51aab689528624a5f71765b67c0b84b796695cd99e17e0aa4bf87ff801c93598567063a8ea0e863604dd4c51398ac43b5ac838dfe3a33165b7b58916c7f202a7ea82a9e24c8a05b2fb9b66cd5163069ad1fe21bcea325388b540a8bb734ccf73abcaa60dbdcb3eab33eb1e38da9635af3a87a546b1e538f69cde3ea71ad79423da1354faa27b5e6a83aaa359f539fd39aa7d4535af379f579adf982fa824feccacf068c3a90ff2971dca74fffb7fc46654f94dae59043fd36cf93c3ccee170397c9c1a063d288fc4f48df2932e2982dffd102feed45f88fdaf21f93f8af67fc7dcc8a4c112b8ed94a395e444aba8894e3b6524e4852a29294c122524ed8cc01e7d875b91c0c7ab2c8b8a922524e161d3779b91c0c3a2a8d7b0b1bf730db66beab1e11bf3ca27835f95dc6611ce80acefccf154cc27d3e3d57d828f59cad52a78a48f9bd22524ed94a79be8894978a4879de56ca0b45a4bc5c44ca0b1629c6393808137882a0f3e14e98bd08603db62e5cb80055b57f3856127fd57bf7eb81b7df4dbd0847dceb911cde73cd7bffb58f5fda77ba0af9da90cf0119f1a2b3029f7ffe395479df6d77ee7fe682bff69f53dbfc5bcf1cde3efceb91f32f3f72d7cf97361d6d78af6cf8f63ff12f58b9bbfb579be1fcd647b6b4547e0a53a55528c889021e73bb5131003ff627dc0df89ce740c4e4d43f22c4fc0e1dd7ba84b43e02989b15e8efef27ad279d6fbfea99b5b4e9a9c90f8785689726ba448866bc6ee2c5ee02c4e26641bce38efdaf56cc5ad1f4d4f88721c1ebd178dd9c9743550daac8124b5162f379ba2e53e0dcb973247158e978757ae36bb734fff69571c15ba6f1966abcd7d04e0e21987c68745c81e3c78f23e7865f7df897ddad9bde778f9eb83ad3ddb862e7b63776bcd7bdfe272e4d4c8526a65c53ec183ebd089d705793e714b4db1b1b31de279c704f23b8136d325e3c9c703722b0df85264d2a5d7e703b96482f5a96227e26317950edb30f6f202b8d571527dc73b0bfb81c759956fd6602fc558eb336afbdf2d9a461e695a0829353bff4437966d4f4b6ec9a8ae988afa458bd23968d465211165bfb4a9b81622b7da1f61b5f8c1c1cf12ea984499ae929b1532e20ff911655e8fbda9fe31eaec561ed2be74be3af7edbb7e2a5de3fbb29fc26faa7022ba6e950a9ac713ad8c432ef5591f7a6ca68ebe56f89fbe10977a902648df31fe4ce59b97344ee2c973a8e3f973b37491de519a9031fc81d1cf4fffffd5ffd375546e5207b395b8e968c1c47a670db2e77308ed63817503c6381ac1790d0938964e99d72fa5b91f46e77b80e1e25d6129e3156e2b35ace18d3a0f25b7ee3bdef09770dcfc1d3a1c23522bf3a2eade9e928e21db816d7f4c00ba7e5f7d5f9e29d818b77e6474517af7b16c9a841b27760a67f59796624647a2b7c5fe93ca08c1affcdfc7ffa8d3a39daa2c95074194b96ac64ffa3301f09ab45f2c9a90bfea53fceee7099df219f801b90a216eec42216cea3e59f7cf209a68ac4f8e7ff72ee4de7b53bea7ffbee81fa59be1b174dfcfaf9b7177ebc6b24db72cf677b5efbd71f7e4764dd995ad6ad05e183d934e44c44b05dca5165f3bef7045c854475b035bf0cca9628b06cd9321cb4edfa431f5db7e68f1e78e3c10fdafef6afd7bcf522d291fef5b81334b35d82d3b5fee2e19f3ef1c31dbb6efd84f6a40fd0ee061c4d4b625365b8a9b1af2f985a75c0228ace86e9147cd35d497fcf7a07e0e6659dfb6ab45c83d1e3e089a745ee8c481da5e51977a593f32edaff4efce09b05cc0e6a3a6539a23382ac259cb561d6931d3ff24bac4e211d9b2585ac4ca480eff04b9d49a9a384a48e832d2143ed4ed9a01fc89d9031ba89a8c650dc442f191432c6ee940dad3114fc810c97149775851a5aab74a775d049b3e396502298d872abc709ae7e77de88f421cd042c07baf5bf13627e16b767ce9cc1522bf9f2d25f2a3d336fbeefd3ef399f3e955e77cbeb3f6bde7ea091aa2a5e816955cc2c2d6c1bc5484c9ecf90c78aa7cb97d7a4c9f369f228729b3172b78da8f57c695679af593eeda115536f3dfcf9b673239c6fb6c6d72cf3b5e87c67cf9eb5e59ba3f1b5687c0bf17915f2f58ea85562bcda4521cfa8e383d39f4cbc56a384c61f4ede9939f751996f419550fa6b9a90ab64217375216cf05abfc345026e0a5d7b0036f78edf3dd377e385b7f6e32ae342beae09992b84a85e9a3352406da4729008ff0e7bf3595eade77915935ce1d74213ee665e8f3541d16f8c44f9e542cfd59c393c62fa5489975f589acd81335fcc9d722e3cd864f7f9d284bb8ec84ad1fe9a3f3ebcc3fc61d4849b52d3bc72b46bf2cbeffba1ecaefd7ebb6fa426dc549fcdf392a5a2b2439545d4b242bd092d6629b03c34227fc04531bf11091e6f41d7882fcec49783e2ab32fd8b443ac1003be029f4ebe06fffe28f93ddb5bbc17ddc815ea5a487252cfff02c0a87dcae5a2ddfb7b8d878a4eb580518925de8e3836e179e5f2a7f44e7973da7b1d7c4f5add8348e4c1c5b77ba16b139199bf1ebd8d99f1276e0f4211debed644b97a3e7ef20749f8cbe63dc402f9e47e81e19dd3769a097df40e8bb6574fa8881bef930a1b7cae8bde70df4ea25cf22ba5b4257b60e1be8b5be2d880ecae8f64e03dd7efbb3567467d44077bc47e80d32fab69f1ae8ae6384ee90d11b0f1ae8db5e29406f3a066c9e0e515cb4e129c31c17ecf342f621aafe7d26a6cb625f1732bc7de8c8576a4e06a54f974a4175f133b29bc13cecb24f0dd779442dc6be52e4ca6f70152a2fb9fdb6ee5aab6736ad33d09d2bb758d11b6e32d0ebde78df8aee90fcbae66fdeb7ce695bcd6547c4d29f6db1c6d30018e805ef6cb106ebb61d06ba395010eaed1277f5e0fbd67572f77b6c4acb697bf3686b53906b9706063925094a1eff0efdcde7bd, 5260, 'application/x-shockwave-flash', 2);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 24, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d226175746f53746172742220747970653d22726164696f222064656661756c743d223022206c6162656c3d224175746f20537461727422206465736372697074696f6e3d224175746f6d61746963616c6c79206f70656e2074686520706c6179657220616e6420737461727420706c6179696e672074686520747261636b2e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d22646973706c617966696c656e616d652220747970653d22726164696f222064656661756c743d223122206c6162656c3d22446973706c61792046696c656e616d6522206465736372697074696f6e3d22446973706c61792074686520617564696f27732066696c656e616d652062656c6f772074686520706c617965722e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d226c6f6f702220747970653d22726164696f222064656661756c743d223022206c6162656c3d224c6f6f7022206465736372697074696f6e3d2254686520747261636b2077696c6c206265206c6f6f70656420696e646566696e6974656c79223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d2274657874436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d225465787420636f6c6f757222202f3e0a09093c706172616d206e616d653d22736c69646572436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d22536c6964657220636f6c6f757222202f3e0a09093c706172616d206e616d653d226c6f61646572436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d224c6f6164657220636f6c6f757222202f3e0a09093c706172616d206e616d653d22747261636b436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d22547261636b20636f6c6f757222202f3e0a09093c706172616d206e616d653d22626f72646572436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d22426f7264657220636f6c6f757222202f3e0a09093c706172616d206e616d653d226261636b67726f756e64436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d224261636b67726f756e6420636f6c6f757222202f3e0a09093c706172616d206e616d653d226c6566744261636b67726f756e64436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d224c656674206261636b67726f756e6420636f6c6f757222202f3e0a09093c706172616d206e616d653d2272696768744261636b67726f756e64436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d225269676874206261636b67726f756e6420636f6c6f757222202f3e0a09093c706172616d206e616d653d2272696768744261636b67726f756e64486f766572436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d225269676874206261636b67726f756e6420636f6c6f75722028686f7665722922202f3e0a09093c706172616d206e616d653d226c65667449636f6e436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d224c6566742069636f6e20636f6c6f757222202f3e0a09093c706172616d206e616d653d22726967687449636f6e436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d2252696768742069636f6e20636f6c6f757222202f3e0a09093c706172616d206e616d653d22726967687449636f6e486f766572436f6c6f75722220747970653d2274657874222064656661756c743d2222206c6162656c3d2252696768742069636f6e20636f6c6f75722028686f7665722922202f3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 1719, 'text/xml', 3);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 25, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d2273697a652220747970653d2274657874222064656661756c743d223022206c6162656c3d224d61782e2077696474682026616d703b2068656967687422206465736372697074696f6e3d22456e74657220746865206d6178696d756d2073697a65206f662074686520776964746820616e6420686569676874206f662074686520726573697a656420696d6167652e20456e746572203020746f20757365207468652076616c756520636f6e6669677572656420666f72206c697374696e67207468756d626e61696c27732073697a652e22202f3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 288, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 45, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d22666f726d61742220747970653d226c697374222064656661756c743d2222206c6162656c3d22566964656f20466f726d6174223e0a0909093c6f7074696f6e2076616c75653d226d6f76223e517569636b74696d65204d6f7669653c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2264697678223e446976583c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2277696e646f77736d65646961223e57696e646f7773204d6564696120566964656f3c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d2277696474682220747970653d2274657874222064656661756c743d2222206c6162656c3d22576964746822202f3e0a09093c706172616d206e616d653d226865696768742220747970653d2274657874222064656661756c743d2222206c6162656c3d2268656967687422202f3e0a09093c706172616d206e616d653d226175746f706c61792220747970653d22726164696f222064656661756c743d2266616c736522206c6162656c3d224175746f20506c6179223e0a0909093c6f7074696f6e2076616c75653d2274727565223e5965733c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2266616c7365223e4e6f3c2f6f7074696f6e3e0a09093c2f706172616d3e0a09090a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 572, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 46, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d227374617274596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d225374617274207965617222206465736372697074696f6e3d22456e74657220746865207374617274696e672079656172206f72206561726c69657374207965617220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c2069742077696c6c2064656661756c7420746f2037302079656172732061676f2066726f6d207468652063757272656e7420796561722e22202f3e0a09093c706172616d206e616d653d22656e64596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d22456e64207965617222206465736372697074696f6e3d22456e74657220746865206c61746573742079656172206f7220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c207468652063757272656e7420796561722077696c6c20626520757365642e22202f3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 457, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 47, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d227374617274596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d225374617274207965617222206465736372697074696f6e3d22456e74657220746865207374617274696e672079656172206f72206561726c69657374207965617220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c2069742077696c6c2064656661756c7420746f2037302079656172732061676f2066726f6d207468652063757272656e7420796561722e22202f3e0a09093c706172616d206e616d653d22656e64596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d22456e64207965617222206465736372697074696f6e3d22456e74657220746865206c61746573742079656172206f7220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c207468652063757272656e7420796561722077696c6c20626520757365642e22202f3e0a09093c706172616d206e616d653d2264617465466f726d61742220747970653d226c697374222064656661756c743d2222206c6162656c3d224461746520466f726d617422203e0a0909093c6f7074696f6e2076616c75653d2225592d256d2d2564223e323030372d30362d30313c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2225652e256d2e2559223e312e30362e323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d222565202542202559223e31204a756e6520323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2225652f256d2f2559223e312f30362f323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d22256d2f25652f2559223e30362f312f323030373c2f6f7074696f6e3e0a09093c2f706172616d3e09090a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 780, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 26, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d22726f77732220747970653d2274657874222064656661756c743d223622206c6162656c3d22526f777322202f3e0a09093c706172616d206e616d653d22636f6c732220747970653d2274657874222064656661756c743d22363022206c6162656c3d22436f6c756d6e7322202f3e0a09093c706172616d206e616d653d227374796c652220747970653d2274657874222064656661756c743d2222206c6162656c3d225374796c6522206465736372697074696f6e3d225468652074657874626f782062792064656661756c74206973207374796c6564206279207468652027696e707574626f78272043535320636c6173732e20596f752063616e2073706563696679206164646974696f6e616c207374796c65206865726522202f3e0a0a09093c706172616d206e616d653d22407370616365722220747970653d22737061636572222064656661756c743d2222206c6162656c3d2222206465736372697074696f6e3d2222202f3e0a0a09093c706172616d206e616d653d2273756d6d61727943686172732220747970653d2274657874222064656661756c743d2232353522206c6162656c3d224e756d626572206f662053756d6d617279206368617261637465727322202f3e0a09093c706172616d206e616d653d22737472697053756d6d617279546167732220747970653d22726164696f222064656661756c743d223122206c6162656c3d22537472697020616c6c2048544d4c207461677320696e2053756d6d617279207669657722206465736372697074696f6e3d2253657474696e67207468697320746f207965732077696c6c2072656d6f766520616c6c2074616773207468617420636f756c6420706f74656e7469616c6c7920616666656374207768656e2076696577696e672061206c697374206f66206c697374696e67732e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d22737472697044657461696c73546167732220747970653d22726164696f222064656661756c743d223122206c6162656c3d22537472697020616c6c2048544d4c207461677320696e2044657461696c73207669657722206465736372697074696f6e3d2253657474696e67207468697320746f207965732077696c6c2072656d6f766520616c6c2074616773206578636570742074686f73652074686174206172652073706563696669656420696e2027416c6c6f7765642074616773272e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d22706172736555726c2220747970653d22726164696f222064656661756c743d223122206c6162656c3d2250617273652055524c206173206c696e6b20696e2044657461696c732076696577223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a0a09093c706172616d206e616d653d227374726970416c6c546167734265666f7265536176652220747970653d22726164696f222064656661756c743d223022206c6162656c3d22537472697020616c6c2048544d4c2074616773206265666f72652073746f72696e6720746f20646174616261736522206465736372697074696f6e3d224966205759535957494720656469746f7220697320656e61626c656420696e207468652066726f6e742d656e642c2074686973206665617475726520616c6c6f7720796f7520746f20737472697020616e7920706f74656e7469616c6c79206861726d66756c20636f6465732e20596f752063616e207374696c6c20616c6c6f7720736f6d6520746167732077697468696e206465736372697074696f6e206669656c642c2077686963682063616e206265207370656369666965642062656c6f772e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d22616c6c6f776564546167732220747970653d2274657874222064656661756c743d22752c622c692c612c756c2c6c692c7072652c626c6f636b71756f746522206c6162656c3d22416c6c6f776564207461677322206465736372697074696f6e3d22456e7465722074686520746167206e616d65732073657065726174656420627920636f6d6d612e205468697320706172616d6574657220616c6c6f7720796f7520746f2061636365707420736f6d652048544d4c2074616773206576656e20696620796f75206861766520656e61626c65207374726970696e67206f6620616c6c2048544d4c20746167732061626f76652e22202f3e0a09090a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 1967, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 20, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d226d617853756d6d61727943686172732220747970653d2274657874222064656661756c743d22353522206c6162656c3d224d61782e206368617261637465727320696e2053756d6d61727920766965772e22206465736372697074696f6e3d22456e746572203020746f2073686f77207468652066756c6c206e616d65207265676172646c657373206f6620697473206c656e6774682e22202f3e0a09093c706172616d206e616d653d226d617844657461696c7343686172732220747970653d2274657874222064656661756c743d223022206c6162656c3d224d61782e206368617261637465727320696e2044657461696c7320766965772e22206465736372697074696f6e3d22456e746572203020746f2073686f77207468652066756c6c206e616d65207265676172646c657373206f6620697473206c656e6774682e22202f3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 400, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 11, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d226f70656e4e657757696e646f772220747970653d22726164696f222064656661756c743d223122206c6162656c3d224f70656e204e65772057696e646f7722206465736372697074696f6e3d224f70656e2061206e65772077696e646f77207768656e20746865206c696e6b20697320636c69636b65642e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d227573654d54566973697452656469726563742220747970653d22726164696f222064656661756c743d223122206c6162656c3d2255736520696e7465726e616c20726564697265637422206465736372697074696f6e3d225573696e6720696e7465726e65742072656469726563742077696c6c206272696e672076697369746f7273207468726f75676820616e20696e7465726e616c2055524c206265666f7265207265646972656374696e67207468656d20746f207468652061637475616c20776562736974652e205468697320616c6c6f7773204d6f73657473205472656520746f206b65657020747261636b206f6620746865206869747320616e64206869646573207468652061637475616c792055524c2066726f6d2076697369746f722e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d22746578742220747970653d2274657874222064656661756c743d2222206c6162656c3d224c696e6b205465787422206465736372697074696f6e3d22557365207468697320706172616d6574657220746f207370656369667920746865206c696e6b20746578742e204966206c65667420656d7074792c207468652066756c6c2055524c2077696c6c20626520646973706c6179656420617320746865206c696e6b277320746578742e22202f3e0a09093c706172616d206e616d653d226d617855726c4c656e6774682220747970653d2274657874222064656661756c743d22363022206c6162656c3d224d61782e2055524c204c656e67746822206465736372697074696f6e3d22456e74657220746865206d6178696d756d2055524c2773206c656e677468206265666f726520697420697320636c697070656422202f3e0a09093c706172616d206e616d653d22636c697070656453796d626f6c2220747970653d2274657874222064656661756c743d222e2e2e22206c6162656c3d22436c69707065642073796d626f6c22202f3e0a0a09093c706172616d206e616d653d226869646550726f746f636f6c4f75747075742220747970653d22726164696f222064656661756c743d223122206c6162656c3d22486964652027687474703a2f2f2720647572696e67206f7574707574223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d2273686f77476f2220747970653d22726164696f222064656661756c743d223122206c6162656c3d2253686f7720476f20627574746f6e22206465736372697074696f6e3d225468697320476f20627574746f6e2077696c6c20626520617661696c61626c6520696e20746865206261636b2d656e642045646974204c697374696e67207061676520746f20616c6c6f772061646d696e206120666173742077617920746f206f70656e20746865206c697374696e67277320776562736974652e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d2273686f775370696465722220747970653d22726164696f222064656661756c743d223022206c6162656c3d2253686f772053706964657220627574746f6e22206465736372697074696f6e3d225768656e20656e61626c65642c20612053706964657220627574746f6e2077696c6c20626520617661696c61626c65206e65787420746f20746865207765627369746520696e707574206669656c6420696e206261636b2d656e642e205768656e2074686520627574746f6e20697320636c69636b65642c2069742077696c6c20636865636b20746865207765627369746520696e20746865206261636b67726f756e20616e6420706f70756c61746520746865206c697374696e672773204d455441204b65797320616e64204d455441204465736372697074696f6e206669656c642e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 1948, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 29, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0d0a093c706172616d733e0d0a09093c706172616d206e616d653d22766964656f50726f76696465722220747970653d226c697374222064656661756c743d2222206c6162656c3d22566964656f2050726f7669646572223e0d0a0909093c6f7074696f6e2076616c75653d22796f7574756265223e596f75747562653c2f6f7074696f6e3e0d0a0909093c6f7074696f6e2076616c75653d22676f6f676c65766964656f223e476f6f676c6520566964656f3c2f6f7074696f6e3e0d0a0909093c212d2d203c6f7074696f6e2076616c75653d226d65746163616665223e4d657461636166653c2f6f7074696f6e3e202d2d3e0d0a0909093c212d2d203c6f7074696f6e2076616c75653d226966696c6d223e6946696c6d3c2f6f7074696f6e3e202d2d3e0d0a09093c2f706172616d3e0d0a09093c706172616d206e616d653d22636865636b626f784c6162656c2220747970653d2274657874222064656661756c743d22436f6e7461696e7320766964656f22206c6162656c3d22536561726368277320636865636b626f78206c6162656c22202f3e0d0a0d0a09093c706172616d206e616d653d22407370616365722220747970653d2273706163657222202f3e0d0a0d0a09093c706172616d206e616d653d22796f757475626557696474682220747970653d2274657874222064656661756c743d2234323522206c6162656c3d22596f757475626520766964656f20706c6179657227732077696474682e22206465736372697074696f6e3d22204c6561766520656d70747920666f722064656661756c742e22202f3e0d0a09093c706172616d206e616d653d22796f75747562654865696768742220747970653d2274657874222064656661756c743d2233353022206c6162656c3d22596f757475626520766964656f20706c617965722773206865696768742e22206465736372697074696f6e3d22204c6561766520656d70747920666f722064656661756c742e22202f3e0d0a09093c706172616d206e616d653d22796f7574756265496e7075744465736372697074696f6e2220747970653d2274657874222064656661756c743d22456e746572207468652066756c6c2055524c206f662074686520596f757475626520766964656f20706167652e266c743b6272202f2667743b69653a20266c743b622667743b687474703a2f2f796f75747562652e636f6d2f77617463683f763d4f4870414e6c5347374f49266c743b2f622667743b22206c6162656c3d22596f7574756265277320496e707574206465736372697074696f6e22202f3e0d0a0d0a09093c706172616d206e616d653d22407370616365722220747970653d2273706163657222202f3e0d0a09090d0a09093c706172616d206e616d653d22676f6f676c65766964656f496e7075744465736372697074696f6e2220747970653d2274657874222064656661756c743d22456e746572207468652066756c6c2055524c206f662074686520476f6f676c6520766964656f20706167652e266c743b6272202f2667743b69653a20266c743b622667743b687474703a2f2f766964656f2e676f6f676c652e636f6d2f766964656f706c61793f646f6369643d383332303634353537303632353732333631266c743b2f622667743b22206c6162656c3d22476f6f676c6520566964656f277320496e707574206465736372697074696f6e22202f3e0d0a093c2f706172616d733e0d0a3c2f6d6f73706172616d733e, 1300, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 22, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d227374617274596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d225374617274207965617222206465736372697074696f6e3d22456e74657220746865207374617274696e672079656172206f72206561726c69657374207965617220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c2069742077696c6c2064656661756c7420746f2037302079656172732061676f2066726f6d207468652063757272656e7420796561722e22202f3e0a09093c706172616d206e616d653d22656e64596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d22456e64207965617222206465736372697074696f6e3d22456e74657220746865206c61746573742079656172206f7220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c207468652063757272656e7420796561722077696c6c20626520757365642e22202f3e0a09093c706172616d206e616d653d2264617465466f726d61742220747970653d226c697374222064656661756c743d2222206c6162656c3d224461746520466f726d617422203e0a0909093c6f7074696f6e2076616c75653d2225592d256d2d2564223e323030372d30362d30313c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2225642e256d2e2559223e30312e30362e323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d222564202542202559223e3031204a756e6520323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2225642f256d2f2559223e30312f30362f323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d22256d2f25642f2559223e30362f30312f323030373c2f6f7074696f6e3e0a09093c2f706172616d3e09090a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 784, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 15, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d227374617274596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d225374617274207965617222206465736372697074696f6e3d22456e74657220746865207374617274696e672079656172206f72206561726c69657374207965617220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c2069742077696c6c2064656661756c7420746f2037302079656172732061676f2066726f6d207468652063757272656e7420796561722e22202f3e0a09093c706172616d206e616d653d22656e64596561722220747970653d2274657874222064656661756c743d2222206c6162656c3d22456e64207965617222206465736372697074696f6e3d22456e74657220746865206c61746573742079656172206f7220617661696c61626c6520666f722073656c656374696f6e2e204966206c65667420656d7074792c207468652063757272656e7420796561722077696c6c20626520757365642e22202f3e0a09093c706172616d206e616d653d2264617465466f726d61742220747970653d226c697374222064656661756c743d2222206c6162656c3d224461746520466f726d617422203e0a0909093c6f7074696f6e2076616c75653d2225592d256d2d2564223e323030372d30362d30313c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2225642e256d2e2559223e30312e30362e323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d222564202542202559223e3031204a756e6520323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2225642f256d2f2559223e30312f30362f323030373c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d22256d2f25642f2559223e30362f30312f323030373c2f6f7074696f6e3e0a09093c2f706172616d3e09090a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 784, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 48, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d7320616464706174683d222f61646d696e6973747261746f722f636f6d706f6e656e74732f636f6d5f6d747265652f656c656d656e7473223e200a09093c706172616d206e616d653d2266696c65457874656e73696f6e732220747970653d22706970656474657874222064656661756c743d2222206c6162656c3d2241636365707461626c652066696c6520657874656e73696f6e7322206465736372697074696f6e3d22456e746572207468652061636365707461626c652066696c652074797065206f6620657874656e73696f6e20666f722074686973206669656c642e20496620796f752068617665206d6f7265207468616e206f6e6520657874656e73696f6e2c20706c656173652073657065726174652074686520657874656e73696f6e207769746820612062617220277c272e204578616d706c653a20276769667c706e677c6a70677c6a70656727206f722027706466272e20506c6561736520646f206e6f74207374617274206f7220656e64207468652076616c756520776974682061206261722e2022202f3e0a09093c706172616d206e616d653d2273686f77436f756e7465722220747970653d22726164696f222064656661756c743d223122206c6162656c3d2253686f7720636f756e746572223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d22757365496d6167652220747970653d2274657874222064656661756c743d2222206c6162656c3d2255736520496d61676522206465736372697074696f6e3d22456e746572207468652055524c20746f2074686520696d61676520796f7520776f756c64206c696b6520746f2075736520746f206c696e6b20746f20616e2075706c6f616465642066696c652e20596f752063616e20757365207b6c6976655f736974657d20617320746865207265706c6163656d656e7420666f72207468652076616c7565206f662073697465277320646f6d61696e2e2069653a207b6c6976655f736974657d2f696d616765732f736176655f66322e706e6722202f3e0a09093c706172616d206e616d653d2273686f77546578742220747970653d2274657874222064656661756c743d2222206c6162656c3d2253686f77205465787422202f3e0a09093c706172616d206e616d653d2273686f7746696c656e616d652220747970653d22726164696f222064656661756c743d223122206c6162656c3d2253686f772046696c656e616d6522206465736372697074696f6e3d225468697320616c6c6f777320796f7520746f2068696465207468652066696c656e616d65206c696e6b2e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 1162, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 23, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d226f70656e4e657757696e646f772220747970653d22726164696f222064656661756c743d223122206c6162656c3d224f70656e204e65772057696e646f7722206465736372697074696f6e3d224f70656e2061206e65772077696e646f77207768656e20746865206c696e6b20697320636c69636b65642e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d22746578742220747970653d2274657874222064656661756c743d2222206c6162656c3d224c696e6b205465787422206465736372697074696f6e3d22557365207468697320706172616d6574657220746f207370656369667920746865206c696e6b20746578742e204966206c65667420656d7074792c207468652066756c6c2055524c2077696c6c20626520646973706c6179656420617320746865206c696e6b277320746578742e22202f3e0a09093c706172616d206e616d653d226d617855726c4c656e6774682220747970653d2274657874222064656661756c743d22363022206c6162656c3d224d61782e2055524c204c656e67746822206465736372697074696f6e3d22456e74657220746865206d6178696d756d2055524c2773206c656e677468206265666f726520697420697320636c697070656422202f3e0a09093c706172616d206e616d653d22636c697070656453796d626f6c2220747970653d2274657874222064656661756c743d222e2e2e22206c6162656c3d22436c69707065642073796d626f6c22202f3e0a09093c706172616d206e616d653d22757365496e7465726e616c52656469726563742220747970653d22726164696f222064656661756c743d223022206c6162656c3d2255736520696e7465726e616c20726564697265637422206465736372697074696f6e3d225573696e6720696e7465726e616c2072656469726563742077696c6c2068696465207468652061637475616c2064657374696e6174696f6e2055524c20616e642075736520616e20696e7465726e616c2055524c20746f20726564697265637420757365727320746f207468652061637475616c2055524c2e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d226869646550726f746f636f6c4f75747075742220747970653d22726164696f222064656661756c743d223122206c6162656c3d22486964652027687474703a2f2f2720647572696e67206f7574707574223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a09093c706172616d206e616d653d2273686f77476f2220747970653d22726164696f222064656661756c743d223022206c6162656c3d2253686f7720476f20627574746f6e22206465736372697074696f6e3d225468697320476f20627574746f6e2077696c6c20626520617661696c61626c6520696e20746865206261636b2d656e642045646974204c697374696e67207061676520746f20616c6c6f772061646d696e206120666173742077617920746f206f70656e20746865206c697374696e67277320776562736974652e223e0a0909093c6f7074696f6e2076616c75653d2230223e4e6f3c2f6f7074696f6e3e0a0909093c6f7074696f6e2076616c75653d2231223e5965733c2f6f7074696f6e3e0a09093c2f706172616d3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 1464, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 21, 'params.xml', 0x3C6D6F73706172616D7320747970653D226D6F64756C65223E0A093C706172616D733E0A09093C706172616D206E616D653D2273756D6D61727943686172732220747970653D2274657874222064656661756C743D2232353522206C6162656C3D224E756D626572206F662053756D6D617279206368617261637465727322202F3E0A09093C706172616D206E616D653D226D617843686172732220747970653D2274657874222064656661756C743D223330303022206C6162656C3D224D61782E206368617261637465727322206465736372697074696F6E3D22546865206D6178696D756D206E756D626572206F66206368617261637465727320616C6C6F77656420696E2074686973206669656C642E204465736372697074696F6E207468617420666F6573206F7665722074686973206C696D69742077696C6C206265207472696D6D65642E222F3E0A09093C706172616D206E616D653D22737472697053756D6D617279546167732220747970653D22726164696F222064656661756C743D223122206C6162656C3D22537472697020616C6C2048544D4C207461677320696E2053756D6D617279207669657722206465736372697074696F6E3D2253657474696E67207468697320746F207965732077696C6C2072656D6F766520616C6C2074616773207468617420636F756C6420706F74656E7469616C6C7920616666656374207768656E2076696577696E672061206C697374206F66206C697374696E67732E223E0A0909093C6F7074696F6E2076616C75653D2230223E4E6F3C2F6F7074696F6E3E0A0909093C6F7074696F6E2076616C75653D2231223E5965733C2F6F7074696F6E3E0A09093C2F706172616D3E0A09093C706172616D206E616D653D22737472697044657461696C73546167732220747970653D22726164696F222064656661756C743D223122206C6162656C3D22537472697020616C6C2048544D4C207461677320696E2044657461696C73207669657722206465736372697074696F6E3D2253657474696E67207468697320746F207965732077696C6C2072656D6F766520616C6C2074616773206578636570742074686F73652074686174206172652073706563696669656420696E2027416C6C6F7765642074616773272E223E0A0909093C6F7074696F6E2076616C75653D2230223E4E6F3C2F6F7074696F6E3E0A0909093C6F7074696F6E2076616C75653D2231223E5965733C2F6F7074696F6E3E0A09093C2F706172616D3E0A09093C706172616D206E616D653D22706172736555726C2220747970653D22726164696F222064656661756C743D223122206C6162656C3D2250617273652055524C206173206C696E6B20696E2044657461696C732076696577223E0A0909093C6F7074696F6E2076616C75653D2230223E4E6F3C2F6F7074696F6E3E0A0909093C6F7074696F6E2076616C75653D2231223E5965733C2F6F7074696F6E3E0A09093C2F706172616D3E0A0A09093C706172616D206E616D653D227374726970416C6C546167734265666F7265536176652220747970653D22726164696F222064656661756C743D223122206C6162656C3D22537472697020616C6C2048544D4C2074616773206265666F72652073746F72696E6720746F20646174616261736522206465736372697074696F6E3D224966205759535957494720656469746F7220697320656E61626C656420696E207468652066726F6E742D656E642C2074686973206665617475726520616C6C6F7720796F7520746F20737472697020616E7920706F74656E7469616C6C79206861726D66756C20636F6465732E20596F752063616E207374696C6C20616C6C6F7720736F6D6520746167732077697468696E206465736372697074696F6E206669656C642C2077686963682063616E206265207370656369666965642062656C6F772E223E0A0909093C6F7074696F6E2076616C75653D2230223E4E6F3C2F6F7074696F6E3E0A0909093C6F7074696F6E2076616C75653D2231223E5965733C2F6F7074696F6E3E0A09093C2F706172616D3E0A09093C706172616D206E616D653D22616C6C6F776564546167732220747970653D2274657874222064656661756C743D22752C622C692C612C756C2C6C692C7072652C626C6F636B71756F74652C7374726F6E672C656D22206C6162656C3D22416C6C6F776564207461677322206465736372697074696F6E3D22456E7465722074686520746167206E616D65732073657065726174656420627920636F6D6D612E205468697320706172616D6574657220616C6C6F7720796F7520746F2061636365707420736F6D652048544D4C2074616773206576656E20696620796F75206861766520656E61626C65207374726970696E67206F6620616C6C2048544D4C20746167732061626F76652E22202F3E0A09093C706172616D206E616D653D2270617273654D616D626F74732220747970653D22726164696F222064656661756C743D223022206C6162656C3D225061727365204D616D626F747322206465736372697074696F6E3D22456E61626C696E6720746869732077696C6C207061727365206D616D626F747320636F646573732077697468696E20746865206465736372697074696F6E206669656C64223E0A0909093C6F7074696F6E2076616C75653D2230223E4E6F3C2F6F7074696F6E3E0A0909093C6F7074696F6E2076616C75653D2231223E5965733C2F6F7074696F6E3E0A09093C2F706172616D3E0A09093C706172616D206E616D653D2273686F77526561644D6F72652220747970653D22726164696F222064656661756C743D223022206C6162656C3D2253686F77202671756F743B52656164204D6F72652E2E2E2671756F743B22206465736372697074696F6E3D2253686F77202671756F743B52656164204D6F72652E2E2671756F743B2069662061206465736372697074696F6E207465787420636C697070656420696E2053756D6D61727920566965772E223E0A0909093C6F7074696F6E2076616C75653D2230223E4E6F3C2F6F7074696F6E3E0A0909093C6F7074696F6E2076616C75653D2231223E5965733C2F6F7074696F6E3E0A09093C2F706172616D3E0A09093C706172616D206E616D653D227768656E526561644D6F72652220747970653D226C697374222064656661756C743D223022206C6162656C3D225768656E20746F2073686F77202671756F743B52656164204D6F72652E2E2671756F743B22206465736372697074696F6E3D225468697320616C6C6F7720796F7520746F20736574207768656E20746F2073686F7720746865202671756F743B52656164204D6F72652E2E2671756F743B206C696E6B2E223E0A0909093C6F7074696F6E2076616C75653D2230223E5768656E206465736372697074696F6E20697320636C69707065643C2F6F7074696F6E3E0A0909093C6F7074696F6E2076616C75653D2231223E416C6C207468652074696D653C2F6F7074696F6E3E0A09093C2F706172616D3E0A09093C706172616D206E616D653D22747874526561644D6F72652220747970653D2274657874222064656661756C743D2252656164204D6F72652E2E2E22206C6162656C3D2252656164204D6F7265207465787422206465736372697074696F6E3D22456E74657220746865202671756F743B52656164204D6F72652E2E2671756F743B20746578742E22202F3E0A093C2F706172616D733E0A3C2F6D6F73706172616D733E, 2738, 'text/xml', 1);
INSERT IGNORE INTO `#__mt_fieldtypes_att` VALUES ('', 32, 'params.xml', 0x3c6d6f73706172616d7320747970653d226d6f64756c65223e0a093c706172616d733e0a09093c706172616d206e616d653d226d617843686172732220747970653d2274657874222064656661756c743d22383022206c6162656c3d224d61782e206368617261637465727322206465736372697074696f6e3d22546865206d6178696d756d206e756d626572206f66206368617261637465727320616c6c6f77656420696e2074686973206669656c642e222f3e0a093c2f706172616d733e0a3c2f6d6f73706172616d733e, 205, 'text/xml', 1);

CREATE TABLE IF NOT EXISTS `#__mt_fieldtypes_info` (
  `ft_id` smallint(6) NOT NULL,
  `ft_version` varchar(64) NOT NULL,
  `ft_website` varchar(255) NOT NULL,
  `ft_desc` text NOT NULL,
  PRIMARY KEY  (`ft_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (1, '', '', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (24, '3', 'http://www.mosets.com', 'Audio Player allows users to upload a mucis file and play the music from within the listing page. Provides basic playback options such as play, pause and volumne control. Made possible by http://www.1pixelout.net/code/audio-player-wordpress-plugin/.');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (25, '3', 'http://www.mosets.com', 'Image field type accepts gif, png & jpg file and resize it according to the value set in the parameter before it is stored to the database.');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (29, '2', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (32, '1', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (36, '1', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (37, '1', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (38, '1', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (39, '1', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (45, '3', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (46, '1', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (47, '1', 'http://www.mosets.com', 'Date input');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (48, '4', 'http://www.mosets.com', 'File field type accept any type of file uploads. You can choose to limit the acceptable file extension in the parameter settings.');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (50, '1', 'http://www.mosets.com', 'E-mail field type validates the e-mail entered by users before storing it to the database. The e-mail will be displayed with the ''mailto'' protocol in the front-end. To protect against e-mail harvester, e-mail is cloaked through javascript.');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (51, '1', 'http://www.mosets.com', 'Number field type accepts numeric value with up to 2 decimals.');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (53, '1', 'http://www.mosets.com', '');
INSERT IGNORE INTO `#__mt_fieldtypes_info` VALUES (54, '1', 'http://www.digg.com', 'Displays the Digg button for each listings.');

CREATE TABLE IF NOT EXISTS `#__mt_images` (
  `img_id` int(11) NOT NULL auto_increment,
  `link_id` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`img_id`),
  KEY `link_id_ordering` (`link_id`,`ordering`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_linkcheck` (
  `id` int(11) NOT NULL auto_increment,
  `link_id` int(11) NOT NULL,
  `field` varchar(255) NOT NULL,
  `link_name` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `status_code` smallint(5) unsigned NOT NULL,
  `check_status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_links` (
  `link_id` int(11) NOT NULL auto_increment,
  `link_name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `link_desc` mediumtext NOT NULL,
  `user_id` int(11) NOT NULL default '0',
  `link_hits` int(11) NOT NULL default '0',
  `link_votes` int(11) NOT NULL default '0',
  `link_rating` decimal(7,6) unsigned NOT NULL default '0.000000',
  `link_featured` smallint(6) NOT NULL default '0',
  `link_published` tinyint(4) NOT NULL default '0',
  `link_approved` int(4) NOT NULL default '0',
  `link_template` varchar(255) NOT NULL,
  `attribs` text NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `internal_notes` text NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `link_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `link_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `link_visited` int(11) NOT NULL default '0',
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postcode` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `price` double(9,2) NOT NULL default '0.00',
  `lat` float(10,6) NOT NULL COMMENT 'Latitude',
  `lng` float(10,6) NOT NULL COMMENT 'Longitude',
  `zoom` tinyint(3) unsigned NOT NULL COMMENT 'Map''s zoom level',
  PRIMARY KEY  (`link_id`),
  KEY `link_rating` (`link_rating`),
  KEY `link_votes` (`link_votes`),
  KEY `link_name` (`link_name`),
  KEY `publishing` (`link_published`,`link_approved`,`publish_up`,`publish_down`),
  KEY `count_listfeatured` (`link_published`,`link_approved`,`link_featured`,`publish_up`,`publish_down`,`link_id`),
  KEY `count_viewowner` (`link_published`,`link_approved`,`user_id`,`publish_up`,`publish_down`),
  KEY `mylisting` (`user_id`,`link_id`),
  FULLTEXT KEY `link_name_desc` (`link_name`,`link_desc`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `log_ip` varchar(255) NOT NULL default '',
  `log_type` varchar(32) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `log_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `link_id` int(11) NOT NULL default '0',
  `rev_id` int(11) NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`log_id`),
  KEY `link_id2` (`link_id`,`log_ip`),
  KEY `link_id1` (`link_id`,`user_id`),
  KEY `log_type` (`log_type`),
  KEY `log_ip` (`log_ip`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_relcats` (
  `cat_id` int(11) NOT NULL default '0',
  `rel_id` int(11) NOT NULL default '0',
  KEY `cat_id` (`cat_id`,`rel_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_reports` (
  `report_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `guest_name` varchar(255) NOT NULL,
  `link_id` int(11) NOT NULL,
  `rev_id` int(10) unsigned NOT NULL default '0',
  `subject` varchar(255) NOT NULL,
  `comment` mediumtext NOT NULL,
  `created` datetime NOT NULL,
  `admin_note` mediumtext NOT NULL,
  PRIMARY KEY  (`report_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_reviews` (
  `rev_id` int(11) NOT NULL auto_increment,
  `link_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `guest_name` varchar(255) NOT NULL default '',
  `rev_title` varchar(255) NOT NULL default '',
  `rev_text` text NOT NULL,
  `rev_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `rev_approved` tinyint(4) NOT NULL default '1',
  `admin_note` mediumtext NOT NULL,
  `vote_helpful` int(10) unsigned NOT NULL default '0',
  `vote_total` int(10) unsigned NOT NULL default '0',
  `ownersreply_text` text NOT NULL,
  `ownersreply_date` datetime NOT NULL,
  `ownersreply_approved` tinyint(4) NOT NULL default '0',
  `ownersreply_admin_note` mediumtext NOT NULL,
  `send_email` tinyint(3) unsigned NOT NULL,
  `email_message` mediumtext NOT NULL,
  PRIMARY KEY  (`rev_id`),
  KEY `link_id` (`link_id`,`rev_approved`,`rev_date`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_searchlog` (
  `search_id` int(11) NOT NULL auto_increment,
  `search_text` text NOT NULL,
  PRIMARY KEY  (`search_id`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__mt_templates` (
  `tem_id` int(11) NOT NULL auto_increment,
  `tem_name` varchar(255) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`tem_id`),
  UNIQUE KEY `tem_name` (`tem_name`)
) ENGINE=MyISAM CHARACTER SET `utf8`;

INSERT IGNORE INTO `#__mt_templates` VALUES (1, 'm2', '');