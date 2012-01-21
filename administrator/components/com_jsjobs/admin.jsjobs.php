<?php

/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/jobsnapps.php
 ^ 
 * Description: Main entry point of site
 ^ 
 * History:		NONE
 ^ 
 */

/*
 * Make sure entry is initiated by Joomla!
 */
defined('_JEXEC') or die('Restricted access');

/*
 * Require our default controller - used if 'c' is not assigned
 * - c is the controller to use (should probably rename to 'controller')
 */
require_once (JPATH_COMPONENT . DS . 'controller.php');

/*
 * Checking if a controller was set, if so let's included it
 */
if ($c = JRequest :: getCmd('c', 'jsjobs'))
{
	$path = JPATH_COMPONENT . DS . 'controllers' . DS . $c . '.php';
	//echo 'Path'.$path;
	jimport('joomla.filesystem.file');
	/*
	 * Checking if the file exists and including it if it does
	 */
	if (JFile :: exists($path))
	{
		require_once ($path);
	}
	else
	{
		JError :: raiseError('500', JText :: _('Unknown controller: <br>' . $c . ':' . $path));
	}
}

/*
 * Define the name of the controller class we're going to use
 * Instantiate a new instance of the controller class
 * Execute the task being called (default to 'display')
 * If it's set, redirect to the URI
 */
$c = 'JSJobsControllerJsjobs';
$controller = new $c ();
$controller->execute(JRequest :: getCmd('task', 'display'));
$controller->redirect();
?>
