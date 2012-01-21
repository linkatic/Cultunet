<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipControllerConfiguration extends RSMembershipController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask('apply', 'save');
	}
	
	/**
	 * Logic to save configuration
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Get the model
		$model = $this->getModel('configuration');
		
		// Save
		$model->save();
		
		$tabposition = JRequest::getInt('tabposition');
		
		$task = JRequest::getCmd('task');
		if ($task == 'apply')
			$link = 'index.php?option=com_rsmembership&view=configuration&tabposition='.$tabposition;
		else
			$link = 'index.php?option=com_rsmembership';
		
		// Redirect
		$this->setRedirect($link, JText::_('RSM_CONFIGURATION_OK'));
	}
	
	function patchmodule()
	{
		jimport('joomla.filesystem.file');
		
		$module = JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php';
		
		$buffer = JFile::read($module);
		if (strpos($buffer, 'RSMembershipHelper') !== false)
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('RSM_PATCH_APPLIED'));
		if (!is_writable($module))
		{
			JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}
		
		$replace = "\$wheremenu = isset( \$Itemid ) ? ' AND ( mm.menuid = '. (int) \$Itemid .' OR mm.menuid = 0 )' : '';";
		$with = $replace."\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php')) {".
						 "\n"."\t\t\t"."include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');".
						 "\n"."\t\t\t"."\$wheremenu .= RSMembershipHelper::getModulesWhere();".
						 "\n"."\t\t"."}".
						 "\n";
		
		$buffer = str_replace($replace, $with, $buffer);
		
		if (JFile::write($module, $buffer))
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('RSM_PATCH_SUCCESS'));
		
		JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
		$this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
	}
	
	function unpatchmodule()
	{
		jimport('joomla.filesystem.file');
		
		$module = JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php';
		
		$buffer = JFile::read($module);
		if (strpos($buffer, 'RSMembershipHelper') === false)
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('RSM_PATCH_NOT_APPLIED'));
		if (!is_writable($module))
		{
			JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}
		
		$with = "\$wheremenu = isset( \$Itemid ) ? ' AND ( mm.menuid = '. (int) \$Itemid .' OR mm.menuid = 0 )' : '';";
		$replace = $with."\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php')) {".
						 "\n"."\t\t\t"."include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');".
						 "\n"."\t\t\t"."\$wheremenu .= RSMembershipHelper::getModulesWhere();".
						 "\n"."\t\t"."}".
						 "\n";
		
		$buffer = str_replace($replace, $with, $buffer);
		
		if (JFile::write($module, $buffer))
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('RSM_PATCH_REMOVED_SUCCESS'));
		
		JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
		$this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
	}
	
	function patchmenu()
	{
		jimport('joomla.filesystem.file');
		
		$menu = JPATH_SITE.DS.'modules'.DS.'mod_mainmenu'.DS.'helper.php';
		
		$buffer = JFile::read($menu);
		if (strpos($buffer, 'RSMembershipHelper') !== false)
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('RSM_PATCH_APPLIED'));
		if (!is_writable($menu))
		{
			JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}
		
		$replace = "\$rows = \$items->getItems('menutype', \$params->get('menutype'));";
		$with = $replace."\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php')) {".
						 "\n"."\t\t\t"."include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');".
						 "\n"."\t\t\t"."RSMembershipHelper::checkMenuShared(\$rows);".
						 "\n"."\t\t"."}".
						 "\n";
		
		$buffer = str_replace($replace, $with, $buffer);
		
		if (JFile::write($menu, $buffer))
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('RSM_PATCH_SUCCESS'));
		
		JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
		$this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
	}
	
	function unpatchmenu()
	{
		jimport('joomla.filesystem.file');
		
		$menu = JPATH_SITE.DS.'modules'.DS.'mod_mainmenu'.DS.'helper.php';
		
		$buffer = JFile::read($menu);
		if (strpos($buffer, 'RSMembershipHelper') === false)
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('RSM_PATCH_NOT_APPLIED'));
		if (!is_writable($menu))
		{
			JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}
		
		$possibles = array(
			"\n"."\t\t\t"."RSMembershipHelper::checkMenuShared(&\$rows);",
			"\n"."\t\t\t"."RSMembershipHelper::checkMenuShared(\$rows);"
		);
		
		foreach ($possibles as $possible)
		{
			$with = "\$rows = \$items->getItems('menutype', \$params->get('menutype'));";
			$replace = $with."\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php')) {".
							 "\n"."\t\t\t"."include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');".
							 $possible.
							 "\n"."\t\t"."}".
							 "\n";
							 
			$buffer = str_replace($replace, $with, $buffer);
		}
		
		if (JFile::write($menu, $buffer))
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration', JText::_('RSM_PATCH_REMOVED_SUCCESS'));
		
		JError::raiseWarning(500, JText::_('RSM_PATCH_NOT_WRITABLE'));
		$this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
	}
}
?>