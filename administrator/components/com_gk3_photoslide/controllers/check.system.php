<?php

/**
 * 
 * @version		3.0.0
 * @package		Joomla
 * @subpackage	Photoslide GK3
 * @copyright	Copyright (C) 2008 - 2009 GavickPro. All rights reserved.
 * @license		GNU/GPL
 * 
 * ==========================================================================
 * 
 * CheckSystem controller.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class ControllerCheckSystem
{
    // component version
	var $version = '<strong>3.0.0 stable</strong>';
	// status of component tables
	var $groupsDB_status = false;
	var	$slidesDB_status = false;
	var	$optionsDB_status = false;
	// prefix for database tables
	var $prefix = '';	
	
	/**
	 * 
	 *  checking GD status
	 * 
	 *  @param null
	 *  @return null
	 *  
	 */
	 
	function GDStatus()
	{
		echo (!extension_loaded('gd')) ? 
		'<strong><font color="red">'.JText::_('DISABLED').'</font></strong>':
		'<strong><font color="green">'.JText::_('ENABLED').'</font></strong>'; 
	}
	
	/**
	 * 
	 *  checking PNG support
	 * 
	 *  @param null
	 *  @return null
	 * 
	 */
	 
	function PNGSupport()
	{
		$gd = gd_info();
    	
    	echo ($gd['PNG Support'] == false) ? 
		'<strong><font color="red">'.JText::_('DISABLED').'</font></strong>':
		'<strong><font color="green">'.JText::_('ENABLED').'</font></strong>';
	}
	
	/** 
	 * 
	 *  Checking GD version
	 * 
	 *  @param null
	 *  @return null
	 * 
	 */
	 
	function GDVersion()
	{
		$gd = gd_info();
    	echo (ereg_replace('[[:alpha:][:space:]()]+', '', $gd['GD Version']) < '2.0.1') ? 
		'<strong><font color="red">'.$gd['GD Version'].JText::_('GDTOOOLD').'</font></strong>':
		'<strong><font color="green">'.$gd['GD Version'].'</font></strong>';	
	}
	
	/**
	 *  Checking folder permissions
	 * 
	 *  @param string folder path
	 *  @return null
	 * 
	 */
	 
	function folderStatus($folder){
		if(is_writable($folder))
		{
			echo '<strong><font color="green">'. JText::_( 'WRITABLE' ) .'</font></strong>'; 
		} 
		else
		{
			echo '<strong><font color="red">'. JText::_( 'UNWRITABLE' ) .'</font></strong>';
		}
	}
	
	/**
	 * ControllerCheckSystem::task()
	 * 
	 * @param mixed $task
	 * @return null
	 */
	 
	function task($task)
	{
		switch($task)
		{
			case 'info' : $this->info(); break;
			case 'show_mainpage' : global $mainframe;$mainframe->redirect('index.php?option=com_gk3_photoslide&c=mainpage'); break; 
			case 'index' : 
			default: $this->index(); break;
		}
	}
	
	/**
	 * ControllerCheckSystem::index()
	 * 
	 * @return null
	 */
	 
	function index()
	{
		require_once(JPATH_COMPONENT.DS.'views'.DS.'check.system.view.php');
		ViewCheckSystem::mainpage();
	}
	
	/**
	 * ControllerCheckSystem::info()
	 * 
	 * @return null
	 */
	 
	function info()
	{
		global $mainframe;
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		// redirect
		$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=info&task=help');		
	}
	
	/**
	 * ControllerCheckSystem::DBStatus()
	 * 
	 * @return null
	 */
	 
	function DBStatus()
	{
		// getting tables list
		$db =& JFactory::getDBO();
		$results = $db->getTableList();
		// getting prefix values
		$jconf = new JConfig();
		$this->prefix = $jconf->dbprefix;	
		// setting tables status
		for($i=0;$i < count($results);$i++)
		{
			if($results[$i] == $this->prefix.'gk3_photoslide_groups') $this->groupsDB_status = true;
			if($results[$i] == $this->prefix.'gk3_photoslide_slides') $this->slidesDB_status = true;
			if($results[$i] == $this->prefix.'gk3_photoslide_options') $this->optionsDB_status = true;
		}	
	}

	/**
	 * ControllerCheckSystem::DBTableStatus()
	 * 
	 * @param mixed $table
	 * @return null
	 */
	 
	function DBTableStatus($table)
	{
		// check table name and write
		if($table == 'gk3_photoslide_groups')
		{
			echo ($this->groupsDB_status) ? 
			'<strong><font color="green">'.JText::_('CSC_YES').'</font></strong>' : 
			'<strong><font color="red">'.JText::_('CSC_NO').'</font></strong>';
		}
		elseif($table == 'gk3_photoslide_slides')
		{
			echo ($this->slidesDB_status) ? 
			'<strong><font color="green">'.JText::_('CSC_YES').'</font></strong>' : 
			'<strong><font color="red">'.JText::_('CSC_NO').'</font></strong>';
		}
		elseif($table == 'gk3_photoslide_options')
		{
			echo ($this->optionsDB_status) ? 
			'<strong><font color="green">'.JText::_('CSC_YES').'</font></strong>' : 
			'<strong><font color="red">'.JText::_('CSC_NO').'</font></strong>';
		}
	}
}

/* End of file check.system.php */
/* Location: ./controllers/check.system.php */