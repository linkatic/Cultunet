<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelConfiguration extends JModel
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function save()
	{
		global $mainframe;
		$config = RSMembershipHelper::getConfig();
		
		$post = JRequest::get('post');
		
		if (isset($post['global_register_code']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$this->_db->getEscaped($post['global_register_code'])."' WHERE `name`='global_register_code'");
			$this->_db->query();
		}
		
		if (isset($post['date_format']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$this->_db->getEscaped($post['date_format'])."' WHERE `name`='date_format'");
			$this->_db->query();
		}
		
		if (isset($post['currency']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$this->_db->getEscaped($post['currency'])."' WHERE `name`='currency'");
			$this->_db->query();
		}
		
		if (isset($post['price_format']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$this->_db->getEscaped($post['price_format'])."' WHERE `name`='price_format'");
			$this->_db->query();
		}
		
		if (isset($post['price_show_free']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['price_show_free']."' WHERE `name`='price_show_free'");
			$this->_db->query();
		}
		
		if (isset($post['delete_pending_after']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['delete_pending_after']."' WHERE `name`='delete_pending_after'");
			$this->_db->query();
		}
		
		if (isset($post['show_login']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['show_login']."' WHERE `name`='show_login'");
			$this->_db->query();
		}
		
		if (isset($post['create_user_instantly']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['create_user_instantly']."' WHERE `name`='create_user_instantly'");
			$this->_db->query();
		}
		
		if (isset($post['choose_username']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['choose_username']."' WHERE `name`='choose_username'");
			$this->_db->query();
		}
		
		if (isset($post['disable_registration']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['disable_registration']."' WHERE `name`='disable_registration'");
			$this->_db->query();
		}
		
		if (isset($post['registration_page']))
		{
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$this->_db->getEscaped($post['registration_page'])."' WHERE `name`='registration_page'");
			$this->_db->query();
		}
		
		if (isset($post['expire_emails']))
		{
			$post['expire_emails'] = (int) $post['expire_emails'];
			if (empty($post['expire_emails']))
				$post['expire_emails'] = 10;
			
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$post['expire_emails']."' WHERE `name`='expire_emails'");
			$this->_db->query();
		}
		
		if (isset($post['expire_check_in']))
		{
			$post['expire_check_in'] = (int) $post['expire_check_in'];
			if (empty($post['expire_check_in']))
				$post['expire_check_in'] = 10;
				
			$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$post['expire_check_in']."' WHERE `name`='expire_check_in'");
			$this->_db->query();
		}
		
		$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['captcha_enabled']."' WHERE `name`='captcha_enabled' LIMIT 1");
		$this->_db->query();
		if (isset($post['captcha_enabled']))
		{
			if ($post['captcha_enabled'] == 1)
			{
				$captcha_enabled_for = array();
				if (isset($post['captcha_enabled_for_unregistered']))
					$captcha_enabled_for[] = 1;
				else
					$captcha_enabled_for[] = 0;
					
				if (isset($post['captcha_enabled_for_registered']))
					$captcha_enabled_for[] = 1;
				else
					$captcha_enabled_for[] = 0;
					
				$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".implode(',',$captcha_enabled_for)."' WHERE `name`='captcha_enabled_for' LIMIT 1");
				$this->_db->query();
				
				if (isset($post['captcha_characters']))
				{
					$post['captcha_characters'] = (int) $post['captcha_characters'];
					if ($post['captcha_characters'] < 3)
					{
						$post['captcha_characters'] = 3;
						JError::raiseWarning(500, JText::_('RST_CAPTCHA_CHARACTERS_ERROR'));
					}
					
					$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$post['captcha_characters']."' WHERE `name`='captcha_characters' LIMIT 1");
					$this->_db->query();
				}
				if (isset($post['captcha_lines']))
				{
					$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['captcha_lines']."' WHERE `name`='captcha_lines' LIMIT 1");
					$this->_db->query();
				}
				if (isset($post['captcha_case_sensitive']))
				{
					$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".(int) $post['captcha_case_sensitive']."' WHERE `name`='captcha_case_sensitive' LIMIT 1");
					$this->_db->query();
				}
			}
			elseif ($post['captcha_enabled'] == 2)
			{
				if (isset($post['recaptcha_public_key']))
				{
					$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$post['recaptcha_public_key']."' WHERE `name`='recaptcha_public_key' LIMIT 1");
					$this->_db->query();
				}
				if (isset($post['recaptcha_private_key']))
				{
					$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$post['recaptcha_private_key']."' WHERE `name`='recaptcha_private_key' LIMIT 1");
					$this->_db->query();
				}
				if (isset($post['recaptcha_theme']))
				{
					$this->_db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".$post['recaptcha_theme']."' WHERE `name`='recaptcha_theme' LIMIT 1");
					$this->_db->query();
				}
			}
		}
		
		RSMembershipHelper::readConfig();
	}
}
?>