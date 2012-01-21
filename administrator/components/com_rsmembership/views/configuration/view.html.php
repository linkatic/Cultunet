<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pane');

class RSMembershipViewConfiguration extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		
		JToolBarHelper::title('RSMembership!','rsmembership');
		
		JSubMenuHelper::addEntry(JText::_('RSM_TRANSACTIONS'), 'index.php?option=com_rsmembership&view=transactions');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIPS'), 'index.php?option=com_rsmembership&view=memberships');
		JSubMenuHelper::addEntry(JText::_('RSM_CATEGORIES'), 'index.php?option=com_rsmembership&view=categories');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_EXTRAS'), 'index.php?option=com_rsmembership&view=extras');
		JSubMenuHelper::addEntry(JText::_('RSM_MEMBERSHIP_UPGRADES'), 'index.php?option=com_rsmembership&view=upgrades');
		JSubMenuHelper::addEntry(JText::_('RSM_PAYMENT_INTEGRATIONS'), 'index.php?option=com_rsmembership&view=payments');
		JSubMenuHelper::addEntry(JText::_('RSM_FILES'), 'index.php?option=com_rsmembership&view=files');
		JSubMenuHelper::addEntry(JText::_('RSM_FILE_TERMS'), 'index.php?option=com_rsmembership&view=terms');
		JSubMenuHelper::addEntry(JText::_('RSM_USERS'), 'index.php?option=com_rsmembership&view=users');
		JSubMenuHelper::addEntry(JText::_('RSM_FIELDS'), 'index.php?option=com_rsmembership&view=fields');
		JSubMenuHelper::addEntry(JText::_('RSM_CONFIGURATION'), 'index.php?option=com_rsmembership&view=configuration', true);
		JSubMenuHelper::addEntry(JText::_('RSM_UPDATES'), 'index.php?option=com_rsmembership&view=updates');
		
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();
			
		$config = RSMembershipHelper::getConfig();
		$this->assignRef('config', $config);
		
		$lists['show_login'] = JHTML::_('select.booleanlist','show_login', 'class="inputbox"', $config->show_login);
		
		$create_user_instantly = array(
			JHTML::_('select.option', '0', JText::_('RSM_WHEN_PAYMENT')),
			JHTML::_('select.option', '1', JText::_('RSM_WHEN_ORDER'))
		);
		
		$lists['price_show_free'] = JHTML::_('select.booleanlist','price_show_free', 'class="inputbox"', $config->price_show_free);
		$lists['create_user_instantly'] = JHTML::_('select.genericlist', $create_user_instantly, 'create_user_instantly', 'class="inputbox"', 'value', 'text', $config->create_user_instantly);
		$lists['choose_username'] = JHTML::_('select.booleanlist','choose_username', 'class="inputbox"', $config->choose_username);
		$lists['disable_registration'] = JHTML::_('select.booleanlist','disable_registration', 'class="inputbox" onclick="rsm_enable_registration(this.value)"', $config->disable_registration);
		
		// CAPTCHA
		$captcha = array(
				JHTML::_('select.option', 0, JText::_('No')),
				JHTML::_('select.option', 1, JText::_('RSM_USE_BUILTIN_CAPTCHA')),
				JHTML::_('select.option', 2, JText::_('RSM_USE_RECAPTCHA'))
			);
		$lists['captcha_enabled'] = JHTML::_('select.genericlist', $captcha, 'captcha_enabled', 'class="inputbox" onclick="rsm_captcha_enable(this.value);"', 'value', 'text', $config->captcha_enabled);
		
		$lists['captcha_enabled_for'] = '';
		$captcha_enabled_for = explode(',', $config->captcha_enabled_for);		
		$lists['captcha_enabled_for'] .= '<input type="checkbox" '.($captcha_enabled_for[0] ? 'checked="checked"' : '').' '.($config->captcha_enabled ? '' : 'disabled="disabled"').' name="captcha_enabled_for_unregistered" value="1" id="captcha_enabled_for0" /> <label for="captcha_enabled_for0">'.JText::_('RSM_CAPTCHA_UNREGISTERED').'</label>';
		$lists['captcha_enabled_for'] .= '<input type="checkbox" '.($captcha_enabled_for[1] ? 'checked="checked"' : '').' '.($config->captcha_enabled ? '' : 'disabled="disabled"').' name="captcha_enabled_for_registered" value="1" id="captcha_enabled_for1" /> <label for="captcha_enabled_for1">'.JText::_('RSM_CAPTCHA_REGISTERED').'</label>';
		
		$lists['captcha_lines'] = JHTML::_('select.booleanlist','captcha_lines','class="inputbox"'.($config->captcha_enabled != 1 ? ' disabled="disabled"' : ''),$config->captcha_lines);
		$lists['captcha_case_sensitive'] = JHTML::_('select.booleanlist','captcha_case_sensitive','class="inputbox"'.($config->captcha_enabled != 1 ? ' disabled="disabled"' : ''),$config->captcha_case_sensitive);
		
		$themes = array(
				JHTML::_('select.option', 'red', JText::_('RSM_RECAPTCHA_THEME_RED')),
				JHTML::_('select.option', 'white', JText::_('RSM_RECAPTCHA_THEME_WHITE')),
				JHTML::_('select.option', 'blackglass', JText::_('RSM_RECAPTCHA_THEME_BLACKGLASS')),
				JHTML::_('select.option', 'clean', JText::_('RSM_RECAPTCHA_THEME_CLEAN')),
			);
		$lists['recaptcha_theme'] = JHTML::_('select.genericlist', $themes, 'recaptcha_theme', 'class="inputbox"'.($config->captcha_enabled != 2 ? ' disabled="disabled"' : ''), 'value', 'text', $config->recaptcha_theme);
		
		$this->assignRef('lists', $lists);
		
		$params = array();
		$params['startOffset'] = JRequest::getInt('tabposition', 0);
		$pane =& JPane::getInstance('Tabs', $params, true);
		$this->assignRef('pane', $pane);
		
		$this->assign('module_helper', JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php');
		$this->assign('module_writable', is_writable($this->module_helper));
		$this->assign('module_patched', RSMembershipHelper::checkPatches('module'));
		
		$this->assign('menu_helper', JPATH_SITE.DS.'modules'.DS.'mod_mainmenu'.DS.'helper.php');
		$this->assign('menu_writable', is_writable($this->menu_helper));
		$this->assign('menu_patched', RSMembershipHelper::checkPatches('menu'));
		
		parent::display($tpl);
	}
}