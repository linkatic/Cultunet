<?php
/**
 * @version		$Id: install.mtree.php 830 2009-12-30 00:20:57Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mtree'.DS.'config.mtree.class.php' );

function com_install()
{
	$my	=& JFactory::getUser();
	$database =& JFactory::getDBO();
	$mtconf = new mtConfig($database);
	
	// Assign current user's email as Mosets Tree admin
	$database->setQuery("UPDATE #__mt_config SET value='" . $my->email . "' WHERE varname='admin_email' LIMIT 1");
	$database->query();

	// Change Admin Icon to Mosets icon
	$database->setQuery("UPDATE #__components SET admin_menu_img='../components/com_mtree/img/icon-16-mosetstree.png' WHERE admin_menu_link='option=com_mtree'");
	$database->query();

	// Rename htaccess.txt to .htaccess in attachments directory
	jimport('joomla.filesystem.file');
    if(!JFile::move(JPATH_SITE.$mtconf->get('relative_path_to_attachments').'htaccess.txt', JPATH_SITE.$mtconf->get('relative_path_to_attachments').'.htaccess' )) {
		$htaccess_rename_status = false;
	} else {
		$htaccess_rename_status = true;
	}
	
	// Check if this is a new installation by checking the number of available categories
	// If this is a new installation, populate #__mt_cats with sample categories.
	$database->setQuery("SELECT COUNT(*) FROM #__mt_cats");
	$num_of_cats = $database->loadResult();
	
	if( !is_null($num_of_cats) && $num_of_cats == 0 )
	{
		$isNew = true;
		loadSampleData();
	} else {
		$isNew = false;
	}
	
	?>
	<div>
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m" style="overflow:hidden;padding-bottom:12px;">
			<div style="padding: 20px;border-right:1px solid #ccc;float:left">
			<img src="../components/com_mtree/img/logo.png" alt="Mosets Tree" style="float:left;padding-right:15px;" />
			</div>
			<div style="margin-left:350px;">
				<h2 style="margin-bottom:0;">Mosets Tree <?php 
				if( $isNew ) { 
					echo $mtconf->get('version'); 
				}
				?></h2>
				<strong>A flexible directory component for Joomla!</strong>
				<br /><br />
				&copy; Copyright 2005-<?php echo date('Y'); ?> by Mosets Consulting. <a href="http://www.mosets.com/">www.mosets.com</a><br />
				<?php if( $isNew ) { ?>
				<input type="button" value="Go to Mosets Tree now" onclick="location.href='index.php?option=com_mtree'" style="margin-top:13px;cursor:pointer;width:200px;font-weight:bold" />
				<?php } else{ ?>
				<input type="button" value="Click here to complete the upgrade" onclick="location.href='index.php?option=com_mtree&amp;task=upgrade'" style="margin-top:13px;cursor:pointer;width:350px;font-weight:bold" />
				<?php } ?>
			</div>
		</div>
	</div>	
	<table class="adminlist">
		<tbody>
			<?php echo getHtaccessRenameRow($htaccess_rename_status); ?>
			<?php echo getWritableRow($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_cat_original_image')); ?>
			<?php echo getWritableRow($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_cat_small_image')); ?>
			<?php echo getWritableRow($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_original_image')); ?>
			<?php echo getWritableRow($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_medium_image')); ?>
			<?php echo getWritableRow($mtconf->getjconf('absolute_path').$mtconf->get('relative_path_to_listing_small_image')); ?>
		</tbody>
	</table>

	<?php
	
	return true;
}

function loadSampleData()
{
	$database =& JFactory::getDBO();
	
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (1, 'Arts', 'arts', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 10, 2, 9);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (2, 'Computers', 'computers', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 9, 10, 17);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (3, 'Health', 'health', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 8, 18, 25);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (4, 'Recreation', 'recreation', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 7, 26, 33);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (5, 'Science', 'science', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 6, 34, 41);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (6, 'Business', 'business', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 5, 42, 49);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (7, 'Games', 'games', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 4, 50, 57);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (8, 'Movies', 'movies', '', '', 1, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 3, 4);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (9, 'Reference', 'reference', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 58, 65);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (10, 'Shopping', 'shopping', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 66, 73);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (11, 'Sports', 'sports', '', '', 0, 0, 3, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 74, 81);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (12, 'Television', 'television', '', '', 1, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 5, 6);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (13, 'Music', 'music', '', '', 1, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 7, 8);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (14, 'Companies', 'companies', '', '', 6, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 43, 44);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (15, 'Finance', 'finance', '', '', 6, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 45, 46);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (16, 'Employment', 'employment', '', '', 6, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 47, 48);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (17, 'Internet', 'internet', '', '', 2, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 11, 12);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (18, 'Programming', 'programming', '', '', 2, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 13, 14);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (19, 'Software', 'software', '', '', 2, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 15, 16);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (20, 'Gambling', 'gambling', '', '', 7, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 51, 52);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (21, 'Roleplaying', 'roleplaying', '', '', 7, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 53, 54);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (22, 'Console', 'console', '', '', 7, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 55, 56);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (23, 'Fitness', 'fitness', '', '', 3, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 19, 20);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (24, 'Medicine', 'medicine', '', '', 3, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 21, 22);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (25, 'Alternative', 'alternative', '', '', 3, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 23, 24);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (74, 'Food', 'food', '', '', 4, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 27, 28);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (56, 'Outdoor', 'outdoor', '', '', 4, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 29, 30);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (55, 'Travel', 'travel', '', '', 4, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 31, 32);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (26, 'Education', 'education', '', '', 9, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 59, 60);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (66, 'Libraries', 'libraries', '', '', 9, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 61, 62);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (31, 'Maps', 'maps', '', '', 9, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 63, 64);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (32, 'Biology', 'biology', '', '', 5, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 35, 36);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (33, 'Psychology', 'psychology', '', '', 5, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 37, 38);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (34, 'Physics', 'physics', '', '', 5, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 39, 40);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (35, 'Autos', 'autos', '', '', 10, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 67, 68);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (36, 'Clothing', 'clothing', '', '', 10, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 69, 70);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (37, 'Gifts', 'gifts', '', '', 10, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 71, 72);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (38, 'Basketball', 'basketball', '', '', 11, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 3, 75, 76);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (39, 'Football', 'football', '', '', 11, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 2, 77, 78);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (40, 'Golf', 'golf', '', '', 11, 0, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 1, 1, '', '', 1, 79, 80);");
	$database->query();
	$database->setQuery("INSERT IGNORE INTO `#__mt_cats` VALUES (0, 'Root', 'root', '', '', -1, 1, 0, 0, '', 1, '2007-06-01 00:00:00', 1, '', 0, 0, 1, '', '', 0, 1, 82);");
	$database->query();

	$database->setQuery("UPDATE IGNORE `#__mt_cats` SET `cat_id` = '0' WHERE `cat_parent` =-1 LIMIT 1;");	
	$database->query();
}

function getHtaccessRenameRow($htaccess_rename_status)
{
	$msg = '';
	if(!$htaccess_rename_status)
	{
		$msg .= '<tr>';
		$msg .= '<td>';
		$msg .= 'Mosets Tree was unable to rename htaccess.txt to .htaccess at '.JPATH_SITE.$mtconf->get('relative_path_to_attachments').'. Please manually rename this file before using Mosets Tree.';
		$msg .= '</td>';
		$msg .= '<td>';
		$msg .= '<b><font color="red">Warning</font></b>';
		$msg .= '</td>';
		$msg .= '</tr>';
	}
	return $msg;
}

function getWritableRow($dir)
{
	$msg = '<tr>';
	$msg .= '<td>';
	$msg .= $dir;
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= (is_writable( $dir ) ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>');
	$msg .= '</td>';
	$msg .= '</tr>';
	return $msg;
}
?>