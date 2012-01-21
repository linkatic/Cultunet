--
-- Database query file
-- For manual update
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
-- Rename old component name
--
UPDATE `jos_components`
	SET	`name` = 'Advanced Module Manager',
		`admin_menu_alt` = 'Advanced Module Manager'
	WHERE `name` = 'Advanced Modules';

--
-- Rename old plugin name
--
UPDATE `jos_plugins`
	SET `name` = 'System - Advanced Module Manager'
	WHERE `name` = 'System - Advanced Modules';

--
-- Fix the published = 2 from the first patch version
--
UPDATE `jos_modules`
	SET `published` = 1
	WHERE `published` = 2;

--
-- Remove the extra association table (from before v1.6.0)
--
DROP TABLE IF EXISTS jos_advancedmodules_menu;

--
-- Insert the system plugin record for NoNumber! Elements (if not exists)
--
INSERT IGNORE INTO `jos_plugins` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
( ( SELECT `id` FROM `jos_plugins` as x WHERE x.`element` = 'nonumberelements' ), 'System - NoNumber! Elements', 'nonumberelements', 'system', 0, 0, 1, 0, 0, 0, 0, '');