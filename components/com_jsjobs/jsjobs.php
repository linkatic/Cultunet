<?php

/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	controllers/application.php
 ^ 
 * Description: Entry point for the component (jobsnapps)
 ^ 
 * History:		NONE
 ^ 
 * @package com_jsjobs
 ^ 
 * You should have received a copy of the GNU General Public License along with this program;
 ^ 
 * 
 * */

defined('_JEXEC') or die('Restricted access');

// requires the default controller 
require_once (JPATH_COMPONENT . DS . 'controller.php');

if ($c = JRequest :: getCmd('c', 'jsjobs'))
{
	$path = JPATH_COMPONENT . DS . 'controllers' . DS . $c . '.php';
	jimport('joomla.filesystem.file');

	if (JFile :: exists($path))
	{
		require_once ($path);
	}
	else
	{
		JError :: raiseError('500', JText :: _('Unknown controller: <br>' . $c . ':' . $path));
	}
}

$c = 'JSJobsControllerJsjobs';
$controller = new $c ();
$controller->execute(JRequest :: getCmd('task', 'display'));
$controller->redirect();
?>
