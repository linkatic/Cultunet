<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class RSMembershipViewFiles extends JView
{
	function display( $tpl = null )
	{
		JToolBarHelper::title('RSMembership!','rsmembership');
		
		JSubMenuHelper::addEntry(JText::_('RSM_TRANSACTIONS'), 'index.php?option=com_rsmembership&view=transactions');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIPS'), 'index.php?option=com_rsmembership&view=memberships');
		JSubMenuHelper::addEntry(JText::_('RSM_CATEGORIES'), 'index.php?option=com_rsmembership&view=categories');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_EXTRAS'), 'index.php?option=com_rsmembership&view=extras');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_UPGRADES'), 'index.php?option=com_rsmembership&view=upgrades');
		JSubMenuHelper::addEntry(JText::_('RSM_PAYMENT_INTEGRATIONS'), 'index.php?option=com_rsmembership&view=payments');
		JSubMenuHelper::addEntry(JText::_('RSM_FILES'), 'index.php?option=com_rsmembership&view=files', true);
		JSubMenuHelper::addEntry(JText::_('RSM_FILE_TERMS'), 'index.php?option=com_rsmembership&view=terms');
		JSubMenuHelper::addEntry(JText::_('RSM_USERS'), 'index.php?option=com_rsmembership&view=users');
		JSubMenuHelper::addEntry(JText::_('RSM_FIELDS'), 'index.php?option=com_rsmembership&view=fields');
		JSubMenuHelper::addEntry(JText::_('RSM_CONFIGURATION'), 'index.php?option=com_rsmembership&view=configuration');
		JSubMenuHelper::addEntry(JText::_('RSM_UPDATES'), 'index.php?option=com_rsmembership&view=updates');
		
		$task = JRequest::getVar('task','');
		
		if ($task == 'edit')
		{
			JToolBarHelper::title('RSMembership! <small>['.JText::_('RSM_EDIT_MEMBERSHIP_FILES').']</small>','rsmembership');
			
			JToolBarHelper::apply();
			JToolBarHelper::save();
			JToolBarHelper::cancel();
				
			$this->assignRef('editor', JFactory::getEditor());
				
			$row = $this->get('file');
			$this->assignRef('row', $row);
			
			$all_terms = $this->get('terms');
			$terms = array();
			$terms[] = JHTML::_('select.option', 0, JText::_('RSM_NO_TERMS_SELECTED'));
			foreach ($all_terms as $term)
				$terms[] = JHTML::_('select.option', $term->id, $term->name);
				
			$lists['terms'] = JHTML::_('select.genericlist', $terms, 'term_id', '', 'value', 'text', $row->term_id, 'term_id');
			
			$this->assignRef('lists', $lists);
			$this->assignRef('is_file', $this->get('isFile'));
		}
		else
		{
			$params = new stdClass();
			
			$files = $this->get('files');
			$this->assignRef('files', $files);
			
			$folders = $this->get('folders');
			$this->assignRef('folders', $folders);
			
			$this->assignRef('elements', $this->get('elements'));
			
			$this->assignRef('current', $this->get('current'));
			$this->assignRef('previous', $this->get('previous'));
			
			$link = 'index.php?option=com_rsmembership&controller=files&view=files';
			
			if ($task == 'addfolder' || $task == 'addfile')
			{
				$params->show_upload = 0;
				$params->show_new_dir = 0;
				$params->show_edit = 0;
				
				$membership_id = JRequest::getInt('membership_id', 0);
				$this->assignRef('membership_id', $membership_id);
				if (!empty($membership_id))
					$link .= '&membership_id='.$membership_id;
				
				$extra_value_id = JRequest::getInt('extra_value_id', 0);
				$this->assignRef('extra_value_id', $extra_value_id);
				if (!empty($extra_value_id))
					$link .= '&extra_value_id='.$extra_value_id;
				
				$link .= '&tmpl=component';
				
				$function = JRequest::getWord('function', '');
				$this->assignRef('function', $function);
				$link .= '&function='.$function;
				
				$start = 0;
				if ($task == 'addfolder')
				{
					$params->show_folders = 1;
					$params->show_files = 0;
					$start = 0;
					$count = count($folders);
				}
				if ($task == 'addfile')
				{
					$params->show_folders = 0;
					$params->show_files = 1;
					$start = count($folders);
					$count = $start + count($files);
				}
				$link .= '&task='.$task;
				
				$params->show_add = 1;
			}
			else
			{			
				JToolBarHelper::editListX('edit');
				JToolBarHelper::spacer();
				
				JToolBarHelper::deleteList('RSM_CONFIRM_DELETE');
				
				$params->show_upload = 1;
				$this->assignRef('canUpload', $this->get('canUpload'));
				
				$params->show_new_dir = 1;
				$params->show_edit = 1;
				
				$params->show_folders = 1;
				$params->show_files = 1;
				$count = count($files) + count($folders);
				
				$params->show_add = 0;
			}
			
			$this->assignRef('link', $link);
			$this->assignRef('params', $params);
			$this->assignRef('start', $start);
			$this->assignRef('count', $count);
			$this->assignRef('task', $task);
		}
		
		parent::display($tpl);
	}
}