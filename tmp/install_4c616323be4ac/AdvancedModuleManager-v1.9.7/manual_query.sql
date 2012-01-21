--
-- Database query file
-- For manual installation
--
-- @package     Advanced Module Manager
-- @version     1.9.7
--
-- @author      Peter van Westen <peter@nonumber.nl>
-- @link        http://www.nonumber.nl
-- @copyright   Copyright (C) 2010 NoNumber! All Rights Reserved
-- @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
--

--
-- NOTE: The queries assume you are using 'jos_' as the prefix.
--       Please change that if you are using another prefix.
--

--
-- Table structure for table `jos_advancedmodules`
--
CREATE TABLE IF NOT EXISTS `jos_advancedmodules` (
	`moduleid` int(11) NOT NULL default '0',
	`params` text NOT NULL,
	PRIMARY KEY  (`moduleid`)
);

--
-- Insert the component record
--
INSERT INTO `jos_components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
(NULL, 'Advanced Module Manager', '', 0, 0, '', 'Advanced Module Manager', 'com_advancedmodules', 0, '', 0, '', 1);

--
-- Insert the system plugin record
--
INSERT INTO `jos_plugins` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
(NULL, 'System - Advanced Module Manager', 'advancedmodules', 'system', 0, 0, 1, 0, 0, 0, 0, '');

--
-- Insert the system plugin record for NoNumber! Elements (if not exists)
--
INSERT IGNORE INTO `jos_plugins` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
( ( SELECT `id` FROM `jos_plugins` as x WHERE x.`element` = 'nonumberelements' ), 'System - NoNumber! Elements', 'nonumberelements', 'system', 0, 0, 1, 0, 0, 0, 0, '');