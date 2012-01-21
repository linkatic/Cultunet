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
 * Toolbar class.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class Toolbar{
	
	function check_system()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_CHECKSYSTEM' ) );
		JToolBarHelper::back();		
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::custom( 'info', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );
	}
	
	function news()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_GAVICKNEWS' ) );
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::custom( 'info', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );
	}
	
	function info()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_INFO' ) );
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::custom( 'info', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );		
	}
	
	function options()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_OPTIONS' ) );
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::save( 'save' );
		JToolBarHelper::custom( 'info', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );		
	}
	
	function mainpage()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_MAINPAGE' ) );
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::custom( 'group', 'edit.png', 'edit_f2.png', JText::_('MANAGE_GROUPS'), false, false );
		JToolBarHelper::custom( 'option', 'config.png', 'config_f2.png', JText::_('OPTIONS'), false, false );
		JToolBarHelper::custom( 'check_system', 'apply.png', 'apply_f2.png', JText::_('CHECK_SYSTEM'), false, false );
		JToolBarHelper::custom( 'news', 'css.png', 'css_f2.png', JText::_('GAVICK_NEWS'), false, false );
		JToolBarHelper::custom( 'info', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );	
	}
	
	function view_groups()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_VIEWGROUPS' ) );
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::addNew( 'add', JText::_( 'TOOLBAR_B_ADDGROUP' ));
		JToolBarHelper::editListX( 'edit', JText::_( 'TOOLBAR_B_EDITGROUP' ));
		JToolBarHelper::custom( 'view', 'preview.png', 'preview_f2.png', JText::_( 'TOOLBAR_B_VIEWGROUP' ), true, false );
		JToolBarHelper::deleteList( JText::_('TOOLBAR_B_REALLYWANTREMOVEGROUPS'), 'delete_group');
		JToolBarHelper::custom( 'info', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );
	}
	
	function add_group()
	{
		JToolBarHelper::title( JText::_( 'Photoslide GK3 - '.JText::_('TOOLBAR_ADDGROUP' ) ));
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::save( 'add_group' );
		JToolBarHelper::cancel( 'cancel', JText::_('CLOSE') );
		JToolBarHelper::custom( 'help', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );
	}
	
	function edit_group()
	{
		JToolBarHelper::title( JText::_( 'Photoslide GK3 - '.JText::_('TOOLBAR_EDITGROUP' ) ));
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::save( 'edit_group' );
		JToolBarHelper::cancel( 'cancel', JText::_('CLOSE') );
		JToolBarHelper::custom( 'help', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );
	}
	
	function view_group()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_('TOOLBAR_VIEWGROUP') );
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::custom( 'publish_slide', 'publish.png', 'publish_f2.png', JText::_( 'TOOLBAR_B_PUBLISH_SLIDE' ), true, false );
		JToolBarHelper::custom( 'unpublish_slide', 'unpublish.png', 'unpublish_f2.png', JText::_( 'TOOLBAR_B_UNPUBLISH_SLIDE' ), true, false );
		JToolBarHelper::addNew( 'add', JText::_( 'TOOLBAR_B_ADD_SLIDE' ));
		JToolBarHelper::editListX( 'edit', JText::_( 'TOOLBAR_B_EDIT_SLIDE' ));
		JToolBarHelper::deleteList( JText::_( 'TOOLBAR_B_REALLYWANTREMOVESLIDES' ), 'delete_tab');
		JToolBarHelper::custom( 'help', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );
	}

	function add_slide()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_ADDSLIDE' ) );
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage', 'frontpage.png', 'frontpage_f2.png', JText::_('MAINPAGE'), false, false );
		JToolBarHelper::save( 'add_slide' );
		JToolBarHelper::cancel( 'cancel', JText::_('CLOSE') );
		JToolBarHelper::custom( 'info', 'help.png', 'help_f2.png', JText::_('HELP'), false, false );
	}
	
	function edit_slide()
	{
		JToolBarHelper::title( 'Photoslide GK3 - '.JText::_( 'TOOLBAR_EDITSLIDE' ));
		JToolBarHelper::back();
		JToolBarHelper::custom( 'show_mainpage' , 'frontpage.png', 'frontpage_f2.png' , JText::_('MAINPAGE') , false , false );
		JToolBarHelper::save( 'edit_slide' );
		JToolBarHelper::cancel( 'cancel' , JText::_('CLOSE') );
		JToolBarHelper::custom( 'info' , 'help.png' , 'help_f2.png' , JText::_('HELP') , false , false );
	}
}

/* End of file class.toolbar.php */
/* Location: ./interface/class.toolbar.php */