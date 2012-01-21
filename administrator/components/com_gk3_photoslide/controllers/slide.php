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
 * Slide controller.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class ControllerSlide
{
	
	/**
	 * ControllerSlide::task()
	 * 
	 * @return null
	 */
	function task($task)
	{
		// switching task
		switch($task)
		{
			case 'saveorder' : $this->saveOrder(); break;
			case 'delete_slide' : $this->removeSlide(); break;
			case 'publish_slide' : $this->publishSlide(); break;
			case 'unpublish_slide' : $this->unpublishSlide(); break;
			case 'info' : $this->info(); break;	
			case 'access_slide' : $this->accessSlide(); break;
			case 'add' : $this->addSlideForm(); break;
			case 'add_slide' : $this->addSlide(); break;
			case 'edit' : $this->editSlideForm(); break;
			case 'edit_slide' : $this->editSlide(); break;
			case 'cancel' : $this->cancel(); break;
			case 'show_mainpage' : global $mainframe;$mainframe->redirect('index.php?option=com_gk3_photoslide&c=mainpage'); break;
			case 'index' : 
			default: $this->index(); break;
		}
	}
	
	/**
	 * ControllerSlide::index()
	 * 
	 * @return null
	 */
	function index()
	{
		require_once(JPATH_COMPONENT.DS.'views'.DS.'group.view.php');
		$view = new ViewGroup();
		$view->mainpage();
	}
	
	/**
	 * ControllerSlide::info()
	 * 
	 * @return null
	 */
	function info()
	{
		global $mainframe;
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		// reditect
		$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=info&task=help');	
	}
	
	/**
	 * ControllerSlide::publishSlide()
	 * 
	 * @return null
	 */
	function publishSlide()
	{
		global $mainframe;
		require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$gid = JRequest::getString( 'gid', '', 'get' );
		$slide_model = new ModelSlide();
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));	
		// operation success ?
		if($slide_model->publishSlide($cid))
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_HAS_BEEN_PUBLISHED'));
		}
		else
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_DOES_NOT_EXIST'), 'error');		
		}		
	}
	
	/**
	 * ControllerSlide::unpublishSlide()
	 * 
	 * @return null
	 */
	function unpublishSlide()
	{
		global $mainframe;
		require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$gid = JRequest::getString( 'gid', '', 'get' );
		$slide_model = new ModelSlide();
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));	
		// operation success ?
		if($slide_model->unpublishSlide($cid))
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_HAS_BEEN_UNPUBLISHED'));
		}
		else
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_DOES_NOT_EXIST'), 'error');		
		}
	}
	
	/**
	 * ControllerSlide::saveOrder()
	 * 
	 * @return null
	 */
	function saveOrder()
	{
		global $mainframe;
		$order = JRequest::getVar( 'order', array (0), 'get', 'array' );
		$gid = JRequest::getString( 'gid', '', 'get' );
		require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		$slide_model = new ModelSlide();
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));	
		// save order
		$slide_model->orderSlide($order, $gid);
		// redirect
		$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDES_ORDER_HAVE_BEEN_SAVED'));
	}
	
	/**
	 * ControllerSlide::accessSlide()
	 * 
	 * @return null
	 */
	function accessSlide()
	{
		global $mainframe;
		require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$gid = JRequest::getString( 'gid', '', 'get' );
		$level = JRequest::getString( 'level', '', 'get' );
		$slide_model = new ModelSlide();
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));	
		// operation success ?
		if($slide_model->accessSlide($level, $cid[0]))
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_STATUS_HAS_BEEN_CHANGED'));
		}
		else
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_STATUS_HAS_NOT_BEEN_CHANGED'), 'error');		
		}
	}
	
	/**
	 * ControllerSlide::removeSlide()
	 * 
	 * @return null
	 */
	function removeSlide()
	{
		global $mainframe;
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$gid = JRequest::getString( 'gid', '', 'get' );
		require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		$slide_model = new ModelSlide();
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));	
		// operation success ?
		if($slide_model->removeSlide($cid))
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SELECTED_SLIDES_HAVE_BEEN_REMOVED'));
		}
		else
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SELECTED_SLIDES_HAVE_NOT_BEEN_REMOVED'), 'error');		
		}
	}

	/**
	 * ControllerSlide::addSlideForm()
	 * 
	 * @return null
	 */
	function addSlideForm()
	{
		require_once(JPATH_COMPONENT.DS.'views'.DS.'slide.view.php');
		$view = new ViewSlide();
		$view->addSlide();
	}
	
	/**
	 * ControllerSlide::addSlide()
	 * 
	 * @return null
	 */
	 
	function addSlide()
	{
		global $mainframe;
		require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		require_once(JPATH_COMPONENT.DS.'models'.DS.'group.php');
		$slide_model = new ModelSlide();
		$group_model = new ModelGroup();
		// basic variables
		$option	= JRequest::getCmd('option');
		$gid = $_POST['gid'];
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		//
		$group_type = $group_model->groupType($gid);
		// basic variables		
		require_once(JPATH_COMPONENT.DS.'classes'.DS.'class.image.php');
		$img = new Image();
		$db =& JFactory::getDBO();
		// group settings
		$query = 'SELECT * FROM #__gk3_photoslide_groups WHERE id = '.$_POST['gid'].';';
		$db->setQuery($query);
		// escape datas
		foreach($db->loadObjectList() as $r){
			$quality = $r->default_quality;
			$mW = $r->image_x;
			$mH = $r->image_y;
			$sW = $r->thumb_x;
			$sH = $r->thumb_y;
			$bg = $r->background;
		}
		// uploading image
		if($_POST['image_x'] != 0) $mW = $_POST['image_x'];
		if($_POST['image_y'] != 0) $mH = $_POST['image_y'];
		//
		$hash = $img->upload($mW, $mH, $sW, $sH, $bg, $quality, true, ($group_type == 'Image Show 1') ? true : false);	
		// operation success ?
		if($slide_model->addSlide($hash))
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_HAS_BEEN_ADDED'));
		}
		else
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_HAS_NOT_BEEN_ADDED'), 'error');		
		}
	}

	/**
	 * ControllerSlide::editSlideForm()
	 * 
	 * @return null
	 */
	 
	function editSlideForm()
	{
		require_once(JPATH_COMPONENT.DS.'views'.DS.'slide.view.php');
		$view = new ViewSlide();
		$view->editSlide();
	}

	/**
	 * ControllerSlide::editSlide()
	 * 
	 * @return null
	 */
	 
	function editSlide()
	{
		global $mainframe;
		require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
		require_once(JPATH_COMPONENT.DS.'models'.DS.'group.php');
		$slide_model = new ModelSlide();
		$group_model = new ModelGroup();
		$gid = $_POST['gid'];
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));	
		//
		$group_type = $group_model->groupType($gid);
		// basic variables		
		require_once(JPATH_COMPONENT.DS.'classes'.DS.'class.image.php');
		//
		$img = new Image();
		$db =& JFactory::getDBO();
		// group settings
		$query = 'SELECT * FROM #__gk3_photoslide_groups WHERE id = '.$_POST['gid'].';';
		$db->setQuery($query);
		// escape datas
		foreach($db->loadObjectList() as $r){
			$quality = $r->default_quality;
			$mW = $r->image_x;
			$mH = $r->image_y;
			$sW = $r->thumb_x;
			$sH = $r->thumb_y;
			$bg = $r->background;
		}
		//
		jimport('joomla.filesystem.file');
		// creating thumbnails
		if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_big'.DS.$_POST['filename'])){
			JFile::delete(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_big'.DS.$_POST['filename']);
			$img->createThumbnail(
				JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$_POST['filename'], 
				$_POST['filename'],
				($_POST['image_x'] != 0) ? $_POST['image_x'] : $mW,
				($_POST['image_y'] != 0) ? $_POST['image_y'] : $mH,
				's_big',
				$_POST['stretch'],
				$bg,
				JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS, 
				$quality);
		}
		//
		if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_small'.DS.$_POST['filename'])){
			JFile::delete(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_small'.DS.$_POST['filename']);
			$img->createThumbnail(
				JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$_POST['filename'], 
				$_POST['filename'],
				$sW,
				$sH,
				's_small',
				$_POST['stretch'],
				$bg,
				JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS, 
				$quality);
		}
		// operation success ?
		if($slide_model->editSlide($cid[0]))
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_HAS_BEEN_EDITED'));
		}
		else
		{
			// redirect
			$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('SLIDE_HAS_NOT_BEEN_EDITED'), 'error');		
		}
	}
	
	/**
	 * ControllerSlide::cancel()
	 * 
	 * @return null
	 */
	 
	function cancel()
	{
		global $mainframe;
		// basic variables
		$option	= JRequest::getCmd('option');
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$gid = (isset($_POST['gid'])) ? $_POST['gid'] : $_GET['gid'];
		// redirect
		$mainframe->redirect('index.php?option='.$option.'&client='.$client->id.'&c=group&task=view&cid[]='.$gid, JText::_('PREVIOUS_ACTION_HAS_BEEN_CANCELED'), 'notice');	
	}
}

/* End of file slide.php */
/* Location: ./controllers/slide.php */