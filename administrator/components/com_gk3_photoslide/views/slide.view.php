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
 * Slide view.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
class ViewSlide
{	    
    /**
     * ViewSlide::addSlide()
     * 
     * @return null
     */
     
    function addSlide()
    {
    	//
    	$uri =& JURI::getInstance();
    	//
    	$gid = (isset($_GET['gid'])) ? $_GET['gid'] : $_POST['gid'];
		//
    	require_once(JPATH_COMPONENT.DS.'views'.DS.'navigation.view.php');
    	//
		require_once(JPATH_COMPONENT.DS.'models'.DS.'option.php');
		$option_model = new ModelOption();
		$wysiwyg = (int) $option_model->getOption('wysiwyg');
 		require_once(JPATH_COMPONENT.DS.'models'.DS.'group.php');
 		$group_model = new ModelGroup();
 		$group_type = $group_model->groupType($gid);
        //
    	$db_art =& JFactory::getDBO();
    	$db_art->setQuery( 'SELECT a.`id` AS `id` , a.`title` AS `art_title`, k.`title` AS `cat_name` FROM `#__content` AS `a` LEFT JOIN `#__categories` AS `k` ON a.`catid` = k.`id` ORDER BY k.`title` ASC;' );
    	$arts = $db_art->loadObjectList();
    	//
		require_once(JPATH_COMPONENT.DS.'views'.DS.'tmpl'.DS.'slide.add.html.php');			
    }

    /**
     * ViewSlide::editSlide()
     * 
     * @return null
     */
     
    function editSlide()
    {
    	global $mainframe;
    	//
    	$uri =& JURI::getInstance();
    	//
    	require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		$slide_model = new ModelSlide();
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$gid = (isset($_GET['gid'])) ? $_GET['gid'] : $_POST['gid'];
		$data = $slide_model->getSlide($cid[0]);    	
    	//
    	if($data !== FALSE)
    	{
			//
    		require_once(JPATH_COMPONENT.DS.'views'.DS.'navigation.view.php');
			require_once(JPATH_COMPONENT.DS.'models'.DS.'option.php');
			$option_model = new ModelOption();
			$wysiwyg = (int) $option_model->getOption('wysiwyg');
			require_once(JPATH_COMPONENT.DS.'models'.DS.'group.php');
 			$group_model = new ModelGroup();
 			$group_type = $group_model->groupType($gid);
 			//
 			$db_art =& JFactory::getDBO();
    		$db_art->setQuery( 'SELECT a.`id` AS `id` , a.`title` AS `art_title`, k.`title` AS `cat_name` FROM `#__content` AS `a` LEFT JOIN `#__categories` AS `k` ON a.`catid` = k.`id` ORDER BY k.`title` ASC;' );
    		$arts = $db_art->loadObjectList();
    		//
			require_once(JPATH_COMPONENT.DS.'views'.DS.'tmpl'.DS.'slide.edit.html.php');			
		}
		else
		{
			// basic variables
			$option	= JRequest::getCmd('option');
			$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));	
			//
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SELECTED_SLIDE_DOES_NOT_EXIST'),'error');
		}
    }
}

/* End of file slide.view.php */
/* Location: ./views/com_gk3_photoslide/slide.view.php */