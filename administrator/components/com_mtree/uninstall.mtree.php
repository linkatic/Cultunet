<?php
/**
 * @version		$Id: uninstall.mtree.php 575 2009-03-10 11:44:00Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

function com_uninstall()
{
	$msg = '<table width="100%" border="0" cellpadding="8" cellspacing="0"><tr>';
	$msg .= '<td width="100%" align="left" valign="top"><center><h3>Mosets Tree</h3><h4>A powerful directory component for Joomla!</h4><font class="small">&copy; Copyright 2004-2009 by Lee Cher Yeong. <a href="http://www.mosets.com/">http://www.mosets.com/</a><br/></font></center><br />';

	$msg .= "<fieldset style=\"border: 1px dashed #C0C0C0;\"><legend>Details</legend>";

	$msg .= "<font color=#339900>OK</font> &nbsp; Mosets Tree Uninstalled Successfully</fieldset>";
	$msg .='<br /><br /></td></tr></table>';

	return $msg;
}
?>
