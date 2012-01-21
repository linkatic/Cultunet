--
-- Set Defaults since v1.1.0
--

INSERT IGNORE INTO `#__bloggies_plugins` (`id`, `name`, `type`, `title`, `author`, `email`, `website`, `version`, `license`, `copyright`, `create_date`, `attribs`, `published`, `iscore`) VALUES 
  (1,'default','comment','LyftenBloggie Comment','Daniel Stainback','joomla@lyften.com','http://www.lyften.com','1.0.0','','','March 13, 2010',' ',1,1),
  (2,'jcomment','comment','JComment','Daniel Stainback','joomla@lyften.com','http://www.lyften.com','1.0.0','','','March 13, 2010',' ',1,0),
  (3,'jomcomment','comment','JomComment','Daniel Stainback','joomla@lyften.com','http://www.lyften.com','1.0.0','','','March 13, 2010',' ',1,0),
  (4,'default','avatar','LyftenBloggie Avatar','Daniel Stainback','joomla@lyften.com','http://www.lyften.com','1.0.0','','','March 20, 2010','uploads=1',1,1),
  (5,'jomsocial','avatar','jomSocial Avatar','Daniel Stainback','joomla@lyften.com','http://www.lyften.com','1.0.0','','','March 20, 2010','',1,0),
  (6,'combuilder','avatar','ComBuilder Avatar','Daniel Stainback','joomla@lyften.com','http://www.lyften.com','1.0.0','','','March 20, 2010','',1,0),
  (7,'juser','avatar','JUser Avatar','Daniel Stainback','joomla@lyften.com','http://www.lyften.com','1.0.0','','','March 20, 2010','',1,0);
COMMIT;

INSERT IGNORE INTO `#__bloggies_themes` (`id`, `name`, `title`, `is_default`, `author`, `email`, `website`, `version`, `license`, `copyright`, `create_date`, `attribs`) VALUES 
  (1,'default','Default Theme',1,'Lyften Designs','joomla@lyften.com','http://www.lyften.com','1.1.0','GNU/Lesser General Public License','Copyright 2009-2010 Lyften Designs','March 20, 2010','style=0\nleft_object=avatar\nleftobj_width=48px\nleftobj_height=48px'),
  (2,'anemoi','Anemoi',0,'Lyften Designs','joomla@lyften.com','http://www.lyften.com','1.1.0','GNU/Lesser General Public License','Copyright 2010 Lyften Designs','March 20, 2010','style=blue\nleftcol_object=image\nleftcol_width=208px'),
  (3,'classic','Classic',0,'Lyften Designs','joomla@lyften.com','http://www.lyften.com','1.1.0','GNU/Lesser General Public License','Copyright 2008-2010 Lyften Designs','March 20, 2010',' '),
  (4,'simple','Simple',0,'Lyften Designs','joomla@lyften.com','http://www.lyften.com','1.1.0','GNU/Lesser General Public License','Copyright 2008-2010 Lyften Designs','March 20, 2010','leftcol_object=image\nleftcol_width=240px');
COMMIT;

INSERT IGNORE INTO `#__bloggies_groups` (`id`, `group`, `permissions`, `email_all`, `published`) VALUES 
  (1,'1','a:1:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}}',0,1),
  (2,'18','a:4:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}s:6:\"author\";a:5:{s:13:\"author_access\";s:1:\"1\";s:11:\"can_publish\";s:1:\"0\";s:10:\"can_delete\";s:1:\"1\";s:10:\"can_upload\";s:1:\"1\";s:11:\"create_tags\";s:1:\"1\";}s:5:\"admin\";a:1:{s:12:\"admin_access\";s:1:\"0\";}s:6:\"emails\";a:1:{s:11:\"can_recnoti\";s:1:\"0\";}}',0,1),
  (3,'19','a:3:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}s:6:\"author\";a:1:{s:13:\"author_access\";s:1:\"0\";}s:5:\"admin\";a:1:{s:12:\"admin_access\";s:1:\"0\";}}',0,1),
  (4,'20','a:3:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}s:6:\"author\";a:1:{s:13:\"author_access\";s:1:\"0\";}s:5:\"admin\";a:1:{s:12:\"admin_access\";s:1:\"0\";}}',0,1),
  (5,'21','a:3:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}s:6:\"author\";a:1:{s:13:\"author_access\";s:1:\"0\";}s:5:\"admin\";a:1:{s:12:\"admin_access\";s:1:\"0\";}}',0,1),
  (6,'23','a:3:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}s:6:\"author\";a:1:{s:13:\"author_access\";s:1:\"0\";}s:5:\"admin\";a:1:{s:12:\"admin_access\";s:1:\"0\";}}',0,1),
  (7,'24','a:4:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}s:6:\"author\";a:4:{s:13:\"author_access\";s:1:\"1\";s:11:\"can_publish\";s:1:\"1\";s:10:\"can_delete\";s:1:\"1\";s:10:\"can_upload\";s:1:\"1\";}s:5:\"admin\";a:2:{s:12:\"admin_access\";s:1:\"1\";s:11:\"can_approve\";s:1:\"1\";}s:6:\"emails\";a:4:{s:11:\"can_recnoti\";s:1:\"1\";s:14:\"comment_report\";s:1:\"1\";s:11:\"new_comment\";s:1:\"1\";s:13:\"pending_entry\";s:1:\"1\";}}',0,1),
  (8,'25','a:4:{s:6:\"system\";a:1:{s:13:\"system_access\";s:1:\"1\";}s:6:\"author\";a:5:{s:13:\"author_access\";s:1:\"1\";s:11:\"can_publish\";s:1:\"1\";s:10:\"can_delete\";s:1:\"1\";s:10:\"can_upload\";s:1:\"1\";s:11:\"create_tags\";s:1:\"1\";}s:5:\"admin\";a:2:{s:12:\"admin_access\";s:1:\"1\";s:11:\"can_approve\";s:1:\"1\";}s:6:\"emails\";a:8:{s:11:\"can_recnoti\";s:1:\"1\";s:11:\"receive_all\";s:1:\"1\";s:9:\"new_entry\";s:1:\"1\";s:17:\"new_pending_entry\";s:1:\"1\";s:13:\"pending_entry\";s:1:\"1\";s:14:\"pending_delete\";s:1:\"0\";s:14:\"comment_report\";s:1:\"1\";s:11:\"new_comment\";s:1:\"1\";}}',1,1);
COMMIT;

INSERT IGNORE INTO `#__bloggies_settings` (`id`, `name`, `value`) VALUES 
  (1,'incomingLinks','0'),
  (2,'checkUpdates','1'),
  (3,'entrylistLimit','5'),
  (4,'powerby','1'),
  (5,'avatarUsed','default'),
  (6,'maxAvatarWidth','80'),
  (7,'maxAvatarHeight','80'),
  (8,'frontEditor','joomla'),
  (9,'frontUploads','0'),
  (10,'allowTrackbacks','1'),
  (11,'useUpdateServices','0'),
  (12,'updateServices','http://rpc.pingomatic.com/'),
  (13,'useIntrotext','0'),
  (14,'autoReadmorePCount','2'),
  (15,'necessaryReadmore','0'),
  (16,'commentEntryLimit','5'),
  (17,'useRSSFeed','1'),
  (18,'title','Lyften Bloggie'),
  (19,'mainBlogDesc','Powered by Lyften Bloggie'),
  (20,'feedLimit','8'),
  (21,'feedSummarize','0'),
  (22,'feedLength','2'),
  (23,'useRSS1','1'),
  (24,'useAtom','1'),
  (25,'typeComments','default'),
  (26,'allowAnon','0'),
  (27,'commentlistLimit','5'),
  (28,'allowReport','1'),
  (29,'enableCaptcha','1'),
  (30,'enableBadWord','1'),
  (31,'theBadWords',''),
  (32,'replaceBadWords','@#$*!'),
  (33,'AkismetApi',''),
  (34,'spamCheck','0'),
  (35,'groups','system=system_access\r\nauthor=author_access,can_publish,can_delete,can_upload,create_tags\r\nadmin=admin_access,can_approve\r\nemails=can_recnoti,receive_all,new_entry,new_pending_entry,pending_entry,pending_delete,comment_report,new_comment'),
  (36,'display_img_h','200'),
  (37,'display_img_w','200'),
  (38,'charset','UTF-8'),
  (39,'stripObjects','1'),
  (40,'patches',''),
  (41,'correctImageUrl','1'),
  (42,'filterTrackbacks','0');
COMMIT;