<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

define('_RSMEMBERSHIP_VERSION', '15');
define('_RSMEMBERSHIP_VERSION_LONG', '1.0.0');
define('_RSMEMBERSHIP_KEY', 'MB86SH10F3');
define('_RSMEMBERSHIP_PRODUCT', 'RSMembership!');
define('_RSMEMBERSHIP_COPYRIGHT', '&copy;2009-2010 www.rsjoomla.com');
define('_RSMEMBERSHIP_LICENSE', 'GPL Commercial License');
define('_RSMEMBERSHIP_AUTHOR', '<a href="http://www.rsjoomla.com" target="_blank">www.rsjoomla.com</a>');

class RSMembershipHelper
{
	function readConfig()
	{
		global $mainframe;
		
		$rsmembership_config = new stdClass();
		
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__rsmembership_configuration`");
		$config = $db->loadObjectList();
		if (!empty($config))
			foreach ($config as $config_item)
				$rsmembership_config->{$config_item->name} = $config_item->value;
		$mainframe->setUserState('rsmembership_config', $rsmembership_config);
	}
	
	function getConfig($name = null)
	{
		global $mainframe;
		$config = $mainframe->getUserState('rsmembership_config');
		if ($name != null)
		{
			if (isset($config->$name))
				return $config->$name;
			else
				return false;
		}
		else
			return $config;
	}
	
	function checkPatches($type)
	{
		jimport('joomla.filesystem.file');
		
		if ($type == 'module')
		{
			$module = JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php';
		
			$buffer = JFile::read($module);
			if (strpos($buffer, 'RSMembershipHelper') !== false)
				return true;
		}
		elseif ($type == 'menu')
		{
			$menu = JPATH_SITE.DS.'modules'.DS.'mod_mainmenu'.DS.'helper.php';
		
			$buffer = JFile::read($menu);
			if (strpos($buffer, 'RSMembershipHelper') !== false)
				return true;
		}
		
		return false;
	}
	
	function checkMenuShared(&$rows)
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$db->setQuery("SELECT `membership_id` FROM #__rsmembership_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$memberships = $db->loadResultArray();
		
		$db->setQuery("SELECT `extras` FROM #__rsmembership_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$extras = array();
		$extras_array = $db->loadResultArray();
		if (is_array($extras_array))
			foreach ($extras_array as $extra)
			{
				if (empty($extra)) continue;
				
				$extra = explode(',', $extra);
				$extras = array_merge($extras, $extra);
			}
		
		$db->setQuery("SELECT `membership_id`, `params` FROM #__rsmembership_membership_shared WHERE `type`='menu' AND `published`='1'");
		$shared = $db->loadObjectList();

		$db->setQuery("SELECT `extra_value_id`, `params` FROM #__rsmembership_extra_value_shared WHERE `type`='menu' AND `published`='1'");
		$shared2 = $db->loadObjectList();
		if (!empty($shared2))
			$shared = array_merge($shared, $shared2);
		
		$allowed = array();
		foreach ($shared as $share)
		{
			$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
			$where = isset($share->membership_id) ? $memberships : $extras;
			
			if (in_array($share->{$what}, $where))
				$allowed[] = $share->params;
		}
		
		foreach ($shared as $share)
		{
			$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
			$where = isset($share->membership_id) ? $memberships : $extras;
			
			if (!in_array($share->params, $allowed))
			{
				if (is_array($rows))
				foreach ($rows as $i => $row)
					if ($row->id == $share->params)
					{
						unset($rows[$i]);
						break;
					}
			}
		}
	}
	
	function getModulesWhere()
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$db->setQuery("SELECT `membership_id` FROM #__rsmembership_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$memberships = $db->loadResultArray();
			
		$db->setQuery("SELECT `extras` FROM #__rsmembership_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$extras = array();
		$extras_array = $db->loadResultArray();
		if (is_array($extras_array))
			foreach ($extras_array as $extra)
			{
				if (empty($extra)) continue;
				
				$extra = explode(',', $extra);
				$extras = array_merge($extras, $extra);
			}
		
		$db->setQuery("SELECT `membership_id`, `params` FROM #__rsmembership_membership_shared WHERE `type`='module' AND `published`='1'");
		$shared = $db->loadObjectList();
		if (empty($shared))
			$shared = array();
		
		$db->setQuery("SELECT `extra_value_id`, `params` FROM #__rsmembership_extra_value_shared WHERE `type`='module' AND `published`='1'");
		$shared2 = $db->loadObjectList();
		if (!empty($shared2))
			$shared = array_merge($shared, $shared2);
		
		$allowed = array();
		$not_allowed = array();
		foreach ($shared as $share)
		{
			$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
			$where = isset($share->membership_id) ? $memberships : $extras;
			
			if (in_array($share->{$what}, $where))
				$allowed[] = $share->params;
		}
		
		foreach ($shared as $share)
		{
			if (!in_array($share->params, $allowed))
				$not_allowed[] = $share->params;
		}
		
		if ($not_allowed)
			return " AND m.id NOT IN (".implode(',', $not_allowed).")";
		
		return '';
	}
	
	function getPriceFormat($price)
	{
		$price = number_format($price, 2, '.', '');
		
		$format    = RSMembershipHelper::getConfig('price_format');
		$currency  = RSMembershipHelper::getConfig('currency');
		$show_free = RSMembershipHelper::getConfig('price_show_free');
		
		if ($show_free && (empty($price) || $price == '0.00'))
			return JText::_('RSM_FREE');
		
		return str_replace(array('{price}', '{currency}'), array($price, $currency), $format);
	}
	
	function genKeyCode()
	{
		global $mainframe;
		$code = RSMembershipHelper::getConfig('global_register_code');
		if ($code === false)
			$code = '';
		return md5($code._RSMEMBERSHIP_KEY);
	}
	
	function createThumb($src, $dest, $thumb_w, $type='jpg')
	{
		jimport('joomla.filesystem.file');
		
		$dest = $dest.'.'.$type;
		
		// load image
		$img = imagecreatefromjpeg($src);
		if ($img)
		{
			// get image size
			$width = imagesx($img);
			$height = imagesy($img);

			// calculate thumbnail size
			$new_width = $thumb_w;
			$new_height = floor($height*($thumb_w/$width));

			// create a new temporary image
			$tmp_img = imagecreatetruecolor($new_width, $new_height);

			// copy and resize old image into new image
			imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			// save thumbnail into a file
			imagejpeg($tmp_img, $dest);
			return true;
		}
		else
			return false;
	}
	
	function getStatuses()
	{		
		$return = array();
		for ($i=0;$i<=3;$i++)
			$return[] = JHTML::_('select.option', $i, JText::_('RSM_STATUS_'.$i));
		
		return $return;
	}
	
	function parseParams($params)
	{
		$return = array();
		
		$params = explode(';', $params);
		foreach ($params as $param)
		{
			$param = explode('=', $param);
			if ($param[0] == 'extras')
				$param[1] = explode(',', $param[1]);
				
			$return[$param[0]] = @$param[1];
		}
		
		return $return;
	}
	
	function getCache()
	{
		$return = new stdClass();
		
		$return->memberships = array();
		$this->_db->setQuery("SELECT `id`, `name` FROM #__rsmembership_memberships");
		$result = $this->_db->loadObjectList();
		foreach ($result as $row)
			$return->memberships[$row->id] = $row->name;
		
		$return->extra_values = array();
		$this->_db->setQuery("SELECT `id`, `name` FROM #__rsmembership_extra_values");
		$result = $this->_db->loadObjectList();
		foreach ($result as $row)
			$return->extra_values[$row->id] = $row->name;
		
		return $return;
	}
	
	function sendExpirationEmails()
	{
		RSMembershipHelper::readConfig();
		
		$db = JFactory::getDBO();
		
		// Check the last time this has been run
		if (time() < RSMembershipHelper::getConfig('expire_last_run') + RSMembershipHelper::getConfig('expire_check_in')*60)
			return;
		
		$db->setQuery("UPDATE #__rsmembership_configuration SET `value`='".time()."' WHERE `name`='expire_last_run'");
		$db->query();
		
		// Get custom fields
		$db->setQuery("SELECT id, name FROM #__rsmembership_fields WHERE published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		
		// Get expiration intervals and memberships
		// Performance check - if no emails can be sent, no need to grab the membership
		$db->setQuery("SELECT * FROM #__rsmembership_memberships WHERE `user_email_from_addr`!='' AND published='1'");
		$memberships = $db->loadObjectList();
		
		$update_ids = array();
		foreach ($memberships as $membership)
		{
			// Select all the subscriptions that match (about to expire)
			$db->setQuery("SELECT u.id AS user_id, u.email AS user_email, u.name AS user_name, u.username AS user_username, mu.id AS muid, mu.extras, mu.membership_end FROM #__rsmembership_membership_users mu LEFT JOIN #__users u ON (mu.user_id=u.id) WHERE mu.`status`='0' AND mu.`notified`='0' AND mu.`membership_end` < '".(time() + $membership->expire_notify_interval*86400)."' AND mu.`membership_id`='".$membership->id."' LIMIT ".(int) RSMembershipHelper::getConfig('expire_emails'));
			$results = $db->loadObjectList();
			if (empty($results)) continue;
			
			foreach ($results as $result)
			{
				$extras = '';
				// Performance check
				if (strpos($membership->user_email_expire_text.$membership->user_email_expire_subject, '{extras}') !== false && $result->extras)
				{
					$db->setQuery("SELECT `name` FROM #__rsmembership_extra_values WHERE `id` IN (".$result->extras.")");
					$extras = implode(', ', $db->loadResultArray());
				}
				
				$replace = array('{membership}', '{extras}', '{email}', '{name}', '{username}', '{interval}');
				$with = array($membership->name, $extras, $result->user_email, $result->user_name, $result->user_username, ceil(($result->membership_end - time())/86400));
				
				$db->setQuery("SELECT * FROM #__rsmembership_users WHERE user_id='".(int) $result->user_id."'");
				$user_data_tmp = $db->loadObject();
				
				$user_data = array();
				foreach ($fields as $field)
				{
					$field_id = 'f'.$field->id;
					$user_data[$field->name] = $user_data_tmp->{$field_id};
				}
				unset($user_data_tmp);
				
				foreach ($fields as $field)
				{
					$name = $field->name;
					$replace[] = '{'.$name.'}';
					if (isset($user_data[$name]))
						$with[] = is_array($user_data[$name]) ? implode("\n", $user_data[$name]) : $user_data[$name];
					else
						$with[] = '';
				}
				
				$message = str_replace($replace, $with, $membership->user_email_expire_text);
				// from address
				$from = $membership->user_email_from_addr;
				// from name
				$fromName = $membership->user_email_from;
				// recipient
				$recipient = $result->user_email; // user email
				// subject
				$subject = str_replace($replace, $with, $membership->user_email_expire_subject);
				// body
				$body = $message;
				// mode
				$mode = $membership->user_email_mode; 
				// cc
				$cc = null;
				// bcc
				$bcc = null;
				// attachments
				$db->setQuery("SELECT `path` FROM #__rsmembership_membership_attachments WHERE `membership_id`='".$membership->id."' AND `published`='1' ORDER BY `ordering`");
				$attachment = $db->loadResultArray();
				// reply to
				$replyto = $from;
				// reply to name
				$replytoname = $fromName;
				// send to user
				
				if ($subject != '')
					JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
					
				$update_ids[] = $result->muid;
			}
		}
		
		if (!empty($update_ids))
		{
			$db->setQuery("UPDATE #__rsmembership_membership_users SET `notified`='1' WHERE id IN (".implode(',', $update_ids).")");
			$db->query();
		}
	}
	
	function executeSystemPlugin($task)
	{
		RSMembershipHelper::readConfig();
		
		global $mainframe;
		
		if ($task == 'init')
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT value FROM #__rsmembership_configuration WHERE name='delete_pending_after'");
			$offset = $db->loadResult();
			if ($offset < 1) $offset = 1;
			$offset = $offset * 3600;
			$db->setQuery("DELETE FROM #__rsmembership_transactions WHERE `status`='pending' AND `date` < '".(time() - $offset)."'");
			$db->query();
			
			// Limit 10 so we don't overload the server
			$db->setQuery("SELECT mu.id, m.gid_enable, m.gid_expire, m.disable_expired_account, mu.user_id FROM #__rsmembership_membership_users mu LEFT JOIN #__rsmembership_memberships m ON (mu.membership_id=m.id) WHERE  mu.`status`='0' AND mu.`membership_end` > 0 AND mu.`membership_end` < '".time()."' LIMIT 10");
			$updates = $db->loadObjectList();
			$to_update = array();
			foreach ($updates as $update)
			{
				$to_update[] = $update->id;
				if ($update->gid_enable)
					RSMembership::updateGid($update->user_id, $update->gid_expire);
				if ($update->disable_expired_account)
					RSMembership::disableUser($update->user_id);
			}
			
			if (!empty($to_update))
			{
				$db->setQuery("UPDATE #__rsmembership_membership_users SET `status`='2' WHERE `id` IN (".implode(',', $to_update).")");
				$db->query();
			}
			
			RSMembershipHelper::checkShared();
			RSMembershipHelper::sendExpirationEmails();
		}
		elseif ($task == 'dispatch')
		{
			if (RSMembershipHelper::getConfig('disable_registration'))
			{
				$j_option = JRequest::getVar('option');
				$j_view = JRequest::getVar('view');
				$j_task = JRequest::getVar('task');
				if ($j_option == 'com_user' && ($j_task == 'register' || $j_view == 'register'))
				{
					$url = JRoute::_('index.php?option=com_rsmembership', false);
					$custom_url = RSMembershipHelper::getConfig('registration_page');
					if (!empty($custom_url))
						$url = $custom_url;
						
					$mainframe->redirect($url);
				}
			}
			
			RSMembershipHelper::checkShared();
		}
		elseif ($task == 'route')
		{
			RSMembershipHelper::checkShared();
		}
		elseif ($task == 'render')
		{
			if ($mainframe->isAdmin()) return;
			
			$body = JResponse::getBody();
			if (JString::strpos($body, '{rsmembership-subscribe') === false) return;
			
			$pattern = '#\{rsmembership-subscribe ([0-9]+)\}#i';
			preg_match_all($pattern, $body, $matches);
			$db = JFactory::getDBO();
			foreach ($matches[1] as $i => $membership_id)
			{
				$db->setQuery("SELECT `id`, `name` FROM #__rsmembership_memberships WHERE `id`='".(int) $membership_id."'");
				$membership = $db->loadObject();
				if (empty($membership)) continue;
				
				$find[] = $matches[0][$i];
				$replace[] = JRoute::_('index.php?option=com_rsmembership&task=subscribe&cid='.$membership_id.':'.JFilterOutput::stringURLSafe($membership->name));
			}
			$body = str_replace($find, $replace, $body);
			
			JResponse::setBody($body);
		}
	}
	
	function checkShared()
	{
		$mainframe =& JFactory::getApplication();
		$option = JRequest::getVar('option');
		if (!$option)
			global $option;
		if (!$option)
			return;
		
		// Get the language
		$lang = JFactory::getLanguage();
		$lang->load('com_rsmembership');
		$msg = JText::_('RSM_MEMBERSHIP_NEED_SUBSCRIPTION');
		
		// Get the database object
		$db = JFactory::getDBO();

		// Get the logged in user
		$user = JFactory::getUser();
		
		// Get his subscribed memberships
		$memberships = array();
		$extras = array();
		if (!$user->get('guest'))
		{
			$db->setQuery("SELECT `membership_id` FROM #__rsmembership_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
			$memberships = $db->loadResultArray();
			
			$db->setQuery("SELECT `extras` FROM #__rsmembership_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
			$extras_array = $db->loadResultArray();
			if (is_array($extras_array))
			foreach ($extras_array as $extra)
			{
				if (empty($extra)) continue;
				
				$extra = explode(',', $extra);
				$extras = array_merge($extras, $extra);
			}
		}
		
		$has_access = false;
		$found_shared = false;
		
		// Check the articles, categories and sections
		if ($option == 'com_content')
		{
			$db->setQuery("SELECT `membership_id`, `params`, `type` FROM #__rsmembership_membership_shared WHERE `type` IN ('article', 'category', 'section') AND `published`='1'");
			$shared = $db->loadObjectList();
			
			$db->setQuery("SELECT `extra_value_id`, `params`, `type` FROM #__rsmembership_extra_value_shared WHERE `type` IN ('article', 'category', 'section') AND `published`='1'");
			$shared2 = $db->loadObjectList();
			if (!empty($shared2))
				$shared = array_merge($shared, $shared2);
			
			$view = JRequest::getVar('view');
			$id = JRequest::getInt('id');
			
			if ($view == 'article')
			{
				foreach ($shared as $share)
					if ($share->type == 'article' && $id == $share->params)
					{
						$found_shared = true;
						
						$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
						$where = isset($share->membership_id) ? $memberships : $extras;
						$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';
						$shared_table = isset($share->membership_id) ? '#__rsmembership_membership_shared' : '#__rsmembership_extra_value_shared';
						
						// found a membership that shares this article
						if (!empty($where) && in_array($share->{$what}, $where))
						{
							$has_access = true;
							break;
						}
						else
						{
							// Get the redirect page
							$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
							$redirect = $db->loadResult();
						}
					}
				
				// must check if it belongs to a category or section that we are sharing
				$db->setQuery("SELECT `sectionid`, `catid` FROM #__content WHERE `id`='".$id."'");
				$content = $db->loadObject();
				
				// check memberships
				$db->setQuery("SELECT `membership_id` FROM #__rsmembership_membership_shared WHERE ((`type`='section' AND `params`='".$db->getEscaped($content->sectionid)."') OR (`type`='category' AND `params`='".$db->getEscaped($content->catid)."')) AND `published`='1'");
				$results = $db->loadResultArray();
				if (!empty($results))
				{
					$found_shared = true;
				
					foreach ($results as $membership_id)
						if (in_array($membership_id, $memberships))
						{
							$has_access = true;
							break 1;
						}
						else
						{
							// Get the redirect page
							$db->setQuery("SELECT `share_redirect` FROM #__rsmembership_memberships WHERE `id`='".$membership_id."'");
							$redirect = $db->loadResult();
						}
				}
				
				// check extras
				$db->setQuery("SELECT `extra_value_id` FROM #__rsmembership_extra_value_shared WHERE ((`type`='section' AND `params`='".$db->getEscaped($content->sectionid)."') OR (`type`='category' AND `params`='".$db->getEscaped($content->catid)."')) AND `published`='1'");
				$results = $db->loadResultArray();
				if (!empty($results))
				{
					$found_shared = true;
				
					foreach ($results as $extra_value_id)
						if (in_array($extra_value_id, $extras))
						{
							$has_access = true;
							break 1;
						}
						else
						{
							// Get the redirect page
							$db->setQuery("SELECT `share_redirect` FROM #__rsmembership_extra_values WHERE `id`='".$extra_value_id."'");
							$redirect = $db->loadResult();
						}
				}
			}
			elseif ($view == 'category')
			{
				foreach ($shared as $share)
					if ($share->type == 'category' && $id == $share->params)
					{
						$found_shared = true;
						
						$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
						$where = isset($share->membership_id) ? $memberships : $extras;
						$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';
						$shared_table = isset($share->membership_id) ? '#__rsmembership_membership_shared' : '#__rsmembership_extra_value_shared';
						
						// found a membership that shares this article
						if (!empty($where) && in_array($share->{$what}, $where))
						{
							$has_access = true;
							break;
						}
						else
						{
							// Get the redirect page
							$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
							$redirect = $db->loadResult();
						}
					}
					
				$db->setQuery("SELECT `section` FROM #__categories WHERE `id`='".$id."'");
				$sectionid = $db->loadResult();
				
				// check memberships
				$db->setQuery("SELECT `membership_id` FROM #__rsmembership_membership_shared WHERE `type`='section' AND `params`='".$db->getEscaped($sectionid)."' AND `published`='1'");
				$results = $db->loadResultArray();
				if (!empty($results))
				{
					$found_shared = true;
				
					foreach ($results as $membership_id)
						if (in_array($membership_id, $memberships))
						{
							$has_access = true;
							break 1;
						}
						else
						{
							// Get the redirect page
							$db->setQuery("SELECT `share_redirect` FROM #__rsmembership_memberships WHERE `id`='".$membership_id."'");
							$redirect = $db->loadResult();
						}
				}
				
				// check extras
				$db->setQuery("SELECT `extra_value_id` FROM #__rsmembership_extra_value_shared WHERE `type`='section' AND `params`='".$db->getEscaped($sectionid)."' AND `published`='1'");
				$results = $db->loadResultArray();
				if (!empty($results))
				{
					$found_shared = true;
				
					foreach ($results as $extra_value_id)
						if (in_array($extra_value_id, $extras))
						{
							$has_access = true;
							break 1;
						}
						else
						{
							// Get the redirect page
							$db->setQuery("SELECT `share_redirect` FROM #__rsmembership_extra_values WHERE `id`='".$extra_value_id."'");
							$redirect = $db->loadResult();
						}
				}
			}
			elseif ($view == 'section')
			{
				foreach ($shared as $share)
					if ($share->type == 'section' && $id == $share->params)
					{
						$found_shared = true;
						
						$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
						$where = isset($share->membership_id) ? $memberships : $extras;
						$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';
						$shared_table = isset($share->membership_id) ? '#__rsmembership_membership_shared' : '#__rsmembership_extra_value_shared';
						
						// found a membership that shares this article
						if (!empty($where) && in_array($share->{$what}, $where))
						{
							$has_access = true;
							break;
						}
						else
						{
							// Get the redirect page
							$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
							$redirect = $db->loadResult();
						}
					}
			}
		}
		
		$type = $mainframe->isAdmin() ? 'backendurl' : 'frontendurl';
		$db->setQuery("SELECT `membership_id`, `params`, `type` FROM #__rsmembership_membership_shared WHERE `type` = '".$type."' AND `params` LIKE '".$db->getEscaped($option)."%' AND `published`='1'");
		$shared = $db->loadObjectList();
		
		$db->setQuery("SELECT `extra_value_id`, `params`, `type` FROM #__rsmembership_extra_value_shared WHERE `type` = '".$type."' AND `params` LIKE '".$db->getEscaped($option)."%' AND `published`='1'");
		$shared2 = $db->loadObjectList();
		if (!empty($shared2))
			$shared = array_merge($shared, $shared2);
		
		if (!empty($shared))
			foreach ($shared as $share)
			{
				$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';
				$shared_table = isset($share->membership_id) ? '#__rsmembership_membership_shared' : '#__rsmembership_extra_value_shared';
						
				$query = RSMembershipHelper::parseQuery($share->params);
					
				$current_query = array();
				foreach ($query as $q => $value)
				{
					$var = JRequest::getVar($q, false, 'request', 'none', JREQUEST_ALLOWRAW);
					if ($var !== false)
						$current_query[] = $q.'='.$var;
				}
				$current_query = $option.(!empty($current_query) ? '&'.implode('&', $current_query) : '');
				
				if ($current_query == $share->params || RSMembershipHelper::_is_match($current_query, $share->params))
				{
					$found_shared = true;
					
					if (in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
			
		// Menu - Itemid
		$Itemid = JRequest::getInt('Itemid');
		$db->setQuery("SELECT `membership_id`, `params`, `type` FROM #__rsmembership_membership_shared WHERE `type` = 'menu' AND `params`='".$Itemid."' AND `published`='1'");
		$shared = $db->loadObjectList();
		
		$db->setQuery("SELECT `extra_value_id`, `params`, `type` FROM #__rsmembership_extra_value_shared WHERE `type` = '".$type."' AND `params`='".$Itemid."' AND `published`='1'");
		$shared2 = $db->loadObjectList();
		if (!empty($shared2))
			$shared = array_merge($shared, $shared2);
		
		if (!empty($shared))
			foreach ($shared as $share)
			{
				$what = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__rsmembership_memberships' : '#__rsmembership_extra_values';
				
				if ($share->params == $Itemid)
				{
					$found_shared = true;
					if (in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
		
		if (!$found_shared)
			$has_access = true;
		
		if (!$has_access)
		{
			$redirect = empty($redirect) ? JURI::root() : $redirect;
			$mainframe->redirect($redirect, $msg);
		}
	}
	
	function _is_match($url, $pattern)
	{
		$pattern = RSMembershipHelper::_transform_string($pattern);	
		preg_match_all($pattern, $url, $matches);
		
		return (!empty($matches[0]));
	}

	function _transform_string($string)
	{
		$string = preg_quote($string, '/');
		$string = str_replace(preg_quote('{*}', '/'), '(.*)', $string);	
		
		$pattern = '#\\\{(\\\\\?){1,}\\\}#';
		preg_match_all($pattern, $string, $matches);
		if (count($matches[0]) > 0)
			foreach ($matches[0] as $match)
			{
				$count = count(explode('\?', $match)) - 1;
				$string = str_replace($match, '(.){'.$count.'}', $string);
			}
		
		return '#'.$string.'#';
	}
	
	function parseQuery($query)
	{
		$return = array();
		
		$query = explode('&', $query);
		unset($query[0]);
		foreach ($query as $q)
		{
			$new = explode('=', $q);
			$return[$new[0]] = @$new[1];
		}
		
		return $return;
	}
	
	function getComponents()
	{
		$query = "SELECT DISTINCT(`option`) FROM #__components WHERE `option`!='' ORDER BY `option` ASC";
		$components = $this->_getList($query);
		$tmps = array('com_admin', 'com_frontpage', 'com_trash', 'com_sections', 'com_categories', 'com_checkin');
		foreach ($tmps as $tmp)
		{
			$new = new stdClass();
			$new->option = $tmp;
			$components[] = $new;
		}
		
		return $components;
	}
	
	function showCustomField($field, $selected=array(), $editable=true, $show_required=true)
	{
		if (empty($field) || empty($field->type)) return false;
		
		$return = array();
		$return[0] = JText::_($field->label);
		$return[1] = '';
		
		switch ($field->type)
		{
			case 'freetext':
				$field->values = RSMembershipHelper::isCode($field->values);
				$return[1] = $field->values;
			break;
			
			case 'textbox':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = RSMembershipHelper::isCode($field->values);
				
				$name = 'rsm_fields['.$field->name.']';
				
				$return[1] = '<input type="text" class="rsm_textbox" name="'.$name.'" id="rsm_'.$field->name.'" value="'.htmlspecialchars($field->values).'" size="40" '.$field->additional.' />';
				
				if (!$editable)
					$return[1] = htmlspecialchars($field->values);
			break;
			
			case 'textarea':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = RSMembershipHelper::isCode($field->values);
				
				$name = 'rsm_fields['.$field->name.']';
				
				$return[1] = '<textarea class="textarea rsm_textarea" name="'.$name.'" id="rsm_'.$field->name.'" '.$field->additional.'>'.htmlspecialchars($field->values).'</textarea>';
				
				if (!$editable)
					$return[1] = nl2br(htmlspecialchars($field->values));
			break;
			
			case 'select':
			case 'multipleselect':
				$field->values = RSMembershipHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				$multiple = $field->type == 'multipleselect' ? 'multiple="multiple"' : '';
				
				$name = 'rsm_fields['.$field->name.'][]';
				
				$return[1] = '<select '.$multiple.' class="rsm_select" name="'.$name.'" id="rsm_'.$field->name.'" '.$field->additional.'>';
					foreach ($field->values as $value)
					{
						$found_checked = false;
						if (preg_match('/\[c\]/',$value))
						{
							$value = str_replace('[c]', '', $value);
							$found_checked = true;
						}
						
						$checked = '';
						if (isset($selected[$field->name]) && in_array($value, $selected[$field->name]))
							$checked = 'selected="selected"';
						elseif (!isset($selected[$field->name]) && $found_checked)
							$checked = 'selected="selected"';
						
						$return[1] .= '<option '.$checked.' value="'.htmlspecialchars($value).'">'.htmlspecialchars($value).'</option>';
					}
				$return[1] .= '</select>';
				
				if (!$editable)
				{
					$return[1] = '';
					if (isset($selected[$field->name]))
					{
						if (is_array($selected[$field->name]))
							$return[1] = nl2br(htmlspecialchars(implode("\n", $selected[$field->name])));
						else
							$return[1] = htmlspecialchars($selected[$field->name]);
					}
				}
			break;
			
			case 'checkbox':
				$field->values = RSMembershipHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				foreach ($field->values as $i => $value)
				{
					$found_checked = false;
					if (preg_match('/\[c\]/',$value))
					{
						$value = str_replace('[c]', '', $value);
						$found_checked = true;
					}
					
					$checked = '';
					if (isset($selected[$field->name]) && in_array($value, $selected[$field->name]))
						$checked = 'checked="checked"';
					elseif (!isset($selected[$field->name]) && $found_checked)
						$checked = 'selected="selected"';
					
					$name = 'rsm_fields['.$field->name.'][]';
					
					$return[1] .= '<input '.$checked.' type="checkbox" name="'.$name.'" value="'.htmlspecialchars($value).'" id="rsm_field_'.$field->id.'_'.$i.'" '.$field->additional.' /> <label for="rsm_field_'.$field->id.'_'.$i.'">'.$value.'</label>';
				}
				
				if (!$editable)
				{
					$return[1] = '';
					if (isset($selected[$field->name]))
					{
						if (is_array($selected[$field->name]))
							$return[1] = nl2br(htmlspecialchars(implode("\n", $selected[$field->name])));
						else
							$return[1] = htmlspecialchars($selected[$field->name]);
					}
				}
			break;
			
			case 'radio':
				$field->values = RSMembershipHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				foreach ($field->values as $i => $value)
				{
					$found_checked = false;
					if (preg_match('/\[c\]/',$value))
					{
						$value = str_replace('[c]', '', $value);
						$found_checked = true;
					}
					
					$checked = '';
					if (isset($selected[$field->name]) && $selected[$field->name] == $value)
						$checked = 'checked="checked"';
					elseif (!isset($selected[$field->name]) && $found_checked)
						$checked = 'checked="checked"';
					
					$name = 'rsm_fields['.$field->name.']';
					
					$return[1] .= '<input '.$checked.' type="radio" name="'.$name.'" value="'.htmlspecialchars($value).'" id="rsm_field_'.$field->id.'_'.$i.'" '.$field->additional.' /> <label for="rsm_field_'.$field->id.'_'.$i.'">'.$value.'</label>';
				}
				
				if (!$editable)
					$return[1] = htmlspecialchars(@$selected[$field->name]);
			break;
			
			case 'calendar':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = RSMembershipHelper::isCode($field->values);
					
				$name = 'rsm_fields['.$field->name.']';
				
				$format = RSMembershipHelper::getConfig('date_format');
				$format = RSMembershipHelper::getCalendarFormat($format);
				
				if ($editable)
					$return[1] = JHTML::_('calendar', $field->values, $name, 'rsm_'.$field->name, $format, $field->additional); 
				else
					$return[1] = htmlspecialchars($field->values);
			break;
		}
		
		if ($field->required && $editable && $show_required)
			$return[1] .= ' '.JText::_('RSM_REQUIRED');
		
		return $return;
	}
	
	function isCode($value)
	{
		if (preg_match('/\/\/<code>/',$value))
			return eval($value);
		else
			return $value;
	}
	
	function getCalendarFormat($format)
	{
		$php = array( '%',  'D',  'l',  'M',  'F',  'd',  'j',  'H',  'h',  'z',  'G',  'g',  'm',  'i', "\n",  'A',  'a',  's',  'U', "\t",  'W',  'N',  'w',  'y',  'Y');
		$js  = array('%%', '%a', '%A', '%b', '%B', '%d', '%e', '%H', '%I', '%j', '%k', '%l', '%m', '%M', '%n', '%p', '%P', '%S', '%s', '%t', '%U', '%u', '%w', '%y', '%Y');
		
		return str_replace($php, $js, $format);
	}
	
	function getUserFields($user_id)
	{
		$return = array();
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__rsmembership_users WHERE user_id='".(int) $user_id."'");
		$result = $db->loadObject();
		
		$db->setQuery("SELECT id, name FROM #__rsmembership_fields WHERE published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			$field_id = 'f'.$field->id;
			$return[$field->name] = $result->{$field_id};
		}
		
		return $return;
	}
	
	function getFields($editable=true, $user_id=false, $show_required=true)
	{
		$return = array();
		
		$db = JFactory::getDBO();
		if ($user_id)
		{
			$user = JFactory::getUser($user_id);
			$guest = false;
		}
		else
		{
			$user = JFactory::getUser();
			$guest = $user->get('guest');
		}
			
		$post = JRequest::getVar('rsm_fields', array(), 'post');
		
		$db->setQuery("SELECT * FROM #__rsmembership_fields WHERE published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		
		if (!$post && !$guest)
		{
			$db->setQuery("SELECT * FROM #__rsmembership_users WHERE user_id='".$user->get('id')."'");
			$data = $db->loadObject();
			
			if ($data)
			{
				unset($data->user_id);
				
				foreach ($fields as $field)
				{
					$field_id = 'f'.$field->id;
					if (!isset($data->$field_id))
						continue;
						
					if (in_array($field->type, array('select', 'multipleselect', 'checkbox')))
						$post[$field->name] = explode("\n", $data->$field_id);
					else
						$post[$field->name] = $data->$field_id;
				}
			}
		}
		
		foreach ($fields as $field)
			$return[] = RSMembershipHelper::showCustomField($field, $post, $editable, $show_required);		
		
		return $return;
	}
	
	function getFieldsValidation()
	{
		$return = array();
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__rsmembership_fields WHERE published='1' AND required = '1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			$js = '';
			
			switch ($field->type)
			{
				case 'select':
				case 'multipleselect':
				case 'textarea':
				case 'textbox':
				case 'calendar':
					$js .= "if (document.getElementById('rsm_".$field->name."').value.length == 0)"."\n";
				break;
				
				case 'checkbox':
				case 'radio':
					$field->values = RSMembershipHelper::isCode($field->values);
					$field->values = str_replace("\r\n", "\n", $field->values);
					$field->values = explode("\n", $field->values);

					$ids = array();
					foreach ($field->values as $i => $value)
						$ids[] = "!document.getElementById('rsm_field_".$field->id."_".$i."').checked";
					
					$js .= "if (".implode(" && ", $ids).")"."\n";
				break;
			}
			
			$validation_message = JText::_($field->validation);
			if (empty($validation_message))
				$validation_message = JText::sprintf('RSM_VALIDATION_DEFAULT_ERROR', JText::_($field->label));
			$js .= "msg.push('".JText::_($validation_message, true)."');"."\n";
			
			$return[] = $js;
		}
		
		return $return;
	}
}

class RSMembership
{
	var $_plugins = array();
	
	function RSMembership()
	{
		return true;
	}
	
	function addTransaction()
	{
		$user = JFactory::getUser();
		$user_id = $user->id;
		$date = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		
		return true;
	}
	
	function addPlugin($name, $filename)
	{
		$instance =& RSMembership::getInstance();
		
		$instance->_plugins[$filename] = $name;
	}
	
	function getPlugins()
	{
		$instance =& RSMembership::getInstance();
		
		return $instance->_plugins;
	}
	
	function getPlugin($name)
	{
		$instance =& RSMembership::getInstance();
		
		if (!empty($instance->_plugins[$name]))
			return $instance->_plugins[$name];
		else
			return false;
	}
	
	function processPluginResult($array)
	{
		if (is_array($array))
		{
			foreach ($array as $item)
				if ($item !== false)
					return $item;
		}
		else
			return $array;
	}
	
	function &getInstance()
	{
		static $instance;

		if (!is_object($instance))
			$instance = new RSMembership();

		return $instance;
	}
	
	function finalize($transaction_id)
	{
		global $mainframe, $option;
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		// get transaction details
		$transaction =& JTable::getInstance('RSMembership_Transactions','Table');
		$transaction->load($transaction_id);
		
		// get user details
		$user_data = unserialize($transaction->user_data);
		$user_email = $transaction->user_email;
		
		// get membership details
		$params = RSMembershipHelper::parseParams($transaction->params);
		
		$membership =& JTable::getInstance('RSMembership_Memberships','Table');
		$extras = '';
		
		switch ($transaction->type)
		{
			case 'new':
				$transaction->membership_id = $params['membership_id'];
				
				$membership->load($transaction->membership_id);
				$message = $membership->user_email_new_text;
				$subject = $membership->user_email_new_subject;
			break;
			
			case 'upgrade':
				$transaction->membership_id = $params['to_id'];
				
				$membership->load($transaction->membership_id);
				$message = $membership->user_email_upgrade_text;
				$subject = $membership->user_email_upgrade_subject;
			break;
			
			case 'addextra':
				$transaction->membership_id = $params['membership_id'];
				
				$membership->load($transaction->membership_id);
				$message = $membership->user_email_addextra_text;
				$subject = $membership->user_email_addextra_subject;
			break;
			
			case 'renew':
				$transaction->membership_id = $params['membership_id'];
				
				$membership->load($transaction->membership_id);
				$message = $membership->user_email_renew_text;
				$subject = $membership->user_email_renew_subject;
			break;
		}
		
		if (!empty($params['extras']))
		{
			$db->setQuery("SELECT `name` FROM #__rsmembership_extra_values WHERE `id` IN (".implode(',', $params['extras']).")");
			$extras = implode(', ', $db->loadResultArray());
		}
		
		$replace = array('{membership}', '{extras}', '{email}', '{name}', '{username}', '{continue}');
		$with = array($membership->name, $extras, $user_email, $user_data->name, (isset($user_data->username) ? $user_data->username : ''),'<input class="button" type="button" onclick="location.href=\''.(!empty($membership->redirect) ? $membership->redirect : JRoute::_('index.php?option=com_rsmembership')).'\'" value="'.JText::_('RSM_CONTINUE').'" />');
		
		$db->setQuery("SELECT * FROM #__rsmembership_fields WHERE published='1'");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			$name = $field->name;
			$replace[] = '{'.$name.'}';
			if (isset($user_data->fields[$name]))
				$with[] = is_array($user_data->fields[$name]) ? implode("\n", $user_data->fields[$name]) : $user_data->fields[$name];
			else
				$with[] = '';
		}
		
		// start sending emails
		// user emails
		if (!empty($membership->user_email_from_addr))
		{
			$message = str_replace($replace, $with, $message);
			// from address
			$from = $membership->user_email_from_addr;
			// from name
			$fromName = $membership->user_email_from;
			// recipient
			$recipient = $user_email; // user email
			// subject
			$subject = str_replace($replace, $with, $subject);
			// body
			$body = $message;
			// mode
			$mode = $membership->user_email_mode; 
			// cc
			$cc = null;
			// bcc
			$bcc = null;
			// attachments
			$db->setQuery("SELECT `path` FROM #__rsmembership_membership_attachments WHERE `membership_id`='".$transaction->membership_id."' AND `published`='1' ORDER BY `ordering`");
			$attachment = $db->loadResultArray();
			// reply to
			$replyto = $from;
			// reply to name
			$replytoname = $fromName;
			// send to user
			if ($subject != '')
				JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
		}
		
		// admin emails
		if (!empty($membership->admin_email_to_addr))
		{
			$message = $membership->admin_email_text;
			$message = str_replace($replace, $with, $message);
			// from address
			$from = $user_email;
			// from name
			$fromName = $user_data->name;
			// recipient
			$recipient = $membership->admin_email_to_addr;
			// subject
			$subject = $membership->admin_email_subject;
			// body
			$body = $message;
			// mode
			$mode = $membership->admin_email_mode;
			// cc
			$cc = null;
			// bcc
			$bcc = null;
			// attachments
			$attachment = null;
			// send to admin
			if ($subject != '')
				JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
		}
		
		// run php code
		eval($membership->custom_code);
		
		$session = JFactory::getSession();
		// set the action
		$session->set($option.'.subscribe.action', $membership->action);
		
		// show thank you message
		$thankyou = str_replace($replace, $with, $membership->thankyou);
		$session->set($option.'.subscribe.thankyou', $thankyou);
		
		// show url
		$redirect = str_replace($replace, $with, $membership->redirect);
		$session->set($option.'.subscribe.redirect', $redirect);
	}
	
	function approve($transaction_id, $force=false)
	{
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__rsmembership_transactions WHERE `id`='".(int) $transaction_id."'".($force ? "" : " AND `status`='pending'"));
		$transaction = $db->loadObject();
		if (empty($transaction->id)) return false;
		
		$params = RSMembershipHelper::parseParams($transaction->params);
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		$row =& JTable::getInstance('RSMembership_Membership_Users','Table');
		$row->user_id = $transaction->user_id;
		if (!RSMembershipHelper::getConfig('create_user_instantly') || ($row->user_id == 0))
		{
			$row->user_id = RSMembership::createUser($transaction->user_email, unserialize($transaction->user_data));
			$db->setQuery("UPDATE #__rsmembership_transactions SET `user_id`='".$row->user_id."' WHERE `id`='".$transaction->id."'");
			$db->query();
		}
		$row->price = $transaction->price;
		$row->currency = $transaction->currency;
		
		$update_gid = false;
		switch ($transaction->type)
		{
			case 'new':
				$row->membership_id = $params['membership_id'];
				$db->setQuery("SELECT * FROM #__rsmembership_memberships WHERE `id`='".(int) $row->membership_id."'");
				$membership = $db->loadObject();
				
				if (empty($membership))
				{
					JError::raiseWarning(500, JText::_('RSM_COULD_NOT_APPROVE_TRANSACTION'));
					return false;
				}
				
				if ($membership->gid_enable)
					$update_gid = true;
				
				$row->membership_start = time();
				
				if ($membership->use_trial_period)
				{
					$membership->period = $membership->trial_period;
					$membership->period_type = $membership->trial_period_type;
				}
				
				if ($membership->period > 0)
				{
					switch ($membership->period_type)
					{
						case 'h': $offset = $membership->period * 3600; break;
						case 'd': $offset = $membership->period * 86400; break;
						case 'm': $offset = strtotime('+'.$membership->period.' months') - $row->membership_start; break;
						case 'y': $offset = strtotime('+'.$membership->period.' years') - $row->membership_start; break;
					}
					$row->membership_end = time() + $offset;
				}
				else
					$row->membership_end = 0;
				
				if (!empty($params['extras']))
					$row->extras = implode(',', $params['extras']);
				$row->status = 0;
				
				$row->from_transaction_id = $transaction->id;
				$row->last_transaction_id = $transaction->id;
				
				$row->store();
				$return = $row->id;
			break;
			
			case 'addextra':
				$db->setQuery("SELECT `extras` FROM #__rsmembership_membership_users WHERE `id`='".(int) $params['id']."'");
				$extras = $db->loadResult();
				$extras = explode(',', $extras);
				if (empty($extras[0]))
					$extras = $params['extras'];
				else
					$extras = array_merge($extras, $params['extras']);
				$db->setQuery("UPDATE #__rsmembership_membership_users SET `extras`='".implode(',', $extras)."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				$return = $params['id'];
				return $return;
			break;
			
			case 'upgrade':
				// Get the upgraded membership
				$db->setQuery("SELECT * FROM #__rsmembership_memberships WHERE `id`='".(int) $params['to_id']."'");
				$membership = $db->loadObject();
				
				// Get the current membership
				$db->setQuery("SELECT * FROM #__rsmembership_membership_users WHERE `id`='".(int) $params['id']."'");
				$current = $db->loadObject();
				$db->setQuery("SELECT * FROM #__rsmembership_memberships WHERE `id`='".$current->membership_id."'");				
				$old_membership = $db->loadObject();
				
				$db->setQuery("UPDATE #__rsmembership_membership_users SET `membership_id`='".(int) $params['to_id']."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				$new_price = '';
				$db->setQuery("SELECT price FROM #__rsmembership_membership_upgrades WHERE `membership_from_id`='".(int) $old_membership->id."' AND `membership_to_id`='".(int) $membership->id."' AND `published`='1'");
				$upgrade = $db->loadResult();				
				if ($upgrade)
					$new_price = ", `price`='".$db->getEscaped($current->price + $upgrade)."'";
				
				if ($membership->period == 0)
				{
					$db->setQuery("UPDATE #__rsmembership_membership_users SET `membership_end`='0', `status`='0', `notified`='0' $new_price WHERE `id`='".(int) $params['id']."'");
					$db->query();
				}
				elseif ($membership->period > 0)
				{
					switch ($membership->period_type)
					{
						case 'h': $offset = $membership->period * 3600; break;
						case 'd': $offset = $membership->period * 86400; break;
						case 'm': $offset = strtotime('+'.$membership->period.' months') - $current->membership_start; break;
						case 'y': $offset = strtotime('+'.$membership->period.' years') - $current->membership_start; break;
					}
					$membership_end = $current->membership_start + $offset;
					$status = '';
					if ($membership_end > time())
						$status = ", `status`='0', `notified`='0'";
						
					$db->setQuery("UPDATE #__rsmembership_membership_users SET membership_end = '".$membership_end."' $status $new_price WHERE `id`='".(int) $params['id']."'");
					$db->query();
				}
				
				// the last transaction
				$db->setQuery("UPDATE #__rsmembership_membership_users SET `last_transaction_id`='".$transaction->id."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				if ($membership->gid_enable)
					$update_gid = true;
				
				$return = $params['id'];
			break;
			
			case 'renew':
				$row->membership_id = $params['membership_id'];
				$db->setQuery("SELECT * FROM #__rsmembership_memberships WHERE `id`='".(int) $row->membership_id."'");
				$membership = $db->loadObject();
				
				$membership_start = time();
				if ($membership->period > 0)
				{
					switch ($membership->period_type)
					{
						case 'h': $offset = $membership->period * 3600; break;
						case 'd': $offset = $membership->period * 86400; break;
						case 'm': $offset = strtotime('+'.$membership->period.' months') - $membership_start; break;
						case 'y': $offset = strtotime('+'.$membership->period.' years') - $membership_start; break;
					}
					$membership_end = time() + $offset;
				}
				else
					$membership_end = 0;
				
				$db->setQuery("UPDATE #__rsmembership_membership_users SET `membership_start`='".$membership_start."', `membership_end`='".$membership_end."',`status`='0', `notified`='0' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				// the last transaction
				$db->setQuery("UPDATE #__rsmembership_membership_users SET `last_transaction_id`='".$transaction->id."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				if ($membership->gid_enable)
					$update_gid = true;
				
				$return = $params['id'];
			break;
		}
		
		if ($update_gid)
			RSMembership::updateGid($row->user_id, $membership->gid_subscribe, $transaction->type == 'new' ? false : true);
		
		$db->setQuery("UPDATE #__rsmembership_transactions SET `status`='completed' WHERE `id`='".$transaction->id."'");
		$db->query();
		
		if (!empty($membership->user_email_from_addr) && $membership->user_email_approved_subject != '')
		{
			$user_data = unserialize($transaction->user_data);
			$user_email = $transaction->user_email;
			$replace = array('{membership}', '{email}', '{username}', '{name}');
			$with = array($membership->name, $user_email, (isset($user_data->username) ? $user_data->username : ''), $user_data->name);
			
			$db->setQuery("SELECT * FROM #__rsmembership_fields WHERE published='1'");
			$fields = $db->loadObjectList();
			foreach ($fields as $field)
			{
				$name = $field->name;
				$replace[] = '{'.$name.'}';
				if (isset($user_data->fields[$name]))
					$with[] = is_array($user_data->fields[$name]) ? implode("\n", $user_data->fields[$name]) : $user_data->fields[$name];
				else
					$with[] = '';
			}
			
			// start sending emails
			// from address
			$from = $membership->user_email_from_addr;
			// from name
			$fromName = $membership->user_email_from;
			// recipient
			$recipient = $user_email; // user email
			// subject
			$subject = str_replace($replace, $with, $membership->user_email_approved_subject);
			// body
			$body = str_replace($replace, $with, $membership->user_email_approved_text);
			// mode
			$mode = $membership->user_email_mode; 
			// cc
			$cc = null;
			// bcc
			$bcc = null;
			// reply to
			$replyto = $from;
			// reply to name
			$replytoname = $fromName;
			// send to user
			JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, null, $replyto, $replytoname);
		}
		
		// process stock
		if ($membership->stock > 0)
		{
			// decrease stock
			if ($membership->stock > 1)
				$this->_db->setQuery("UPDATE #__rsmembership_memberships SET `stock`=`stock`-1 WHERE `id`='".$membership->id."'");
			// or set it to unavailable (-1 instead of 0, which actually means unlimited)
			else
				$this->_db->setQuery("UPDATE #__rsmembership_memberships SET `stock`='-1' WHERE `id`='".$membership->id."'");
			$this->_db->query();
		}
		
		// should return the newly created/updated membership id
		return $return;
	}
	
	function createUser($email, $data)
	{
		if (empty($email) || empty($data)) return false;
		
		$lang =& JFactory::getLanguage();
		$lang->load('com_user', JPATH_SITE, null, true);
		$lang->load('com_user', JPATH_ADMINISTRATOR, null, true);
		$lang->load('com_users', JPATH_ADMINISTRATOR, null, true);
		
		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT `id` FROM #__users WHERE `email`='".$db->getEscaped($email)."' LIMIT 1");
		if ($user_id = $db->loadResult())
			return $user_id;
			
		jimport('joomla.user.helper');
		// Get required system objects
		$user = clone(JFactory::getUser(0));
		$authorize	=& JFactory::getACL();

		// Initialize new usertype setting
		$usersConfig =& JComponentHelper::getParams('com_users');
		$newUsertype = $usersConfig->get('new_usertype');
		if (!$newUsertype)
			$newUsertype = 'Registered';
		
		@list($username, $domain) = explode('@', $email);
		
		if (RSMembershipHelper::getConfig('choose_username') && !empty($data->username))
			$username = $data->username;
		
		$db->setQuery("SELECT `id` FROM #__users WHERE `username` LIKE '".$db->getEscaped($username)."' LIMIT 1");
		while ($db->loadResult())
		{
			$username .= mt_rand(0,9);
			$db->setQuery("SELECT `id` FROM #__users WHERE `username` LIKE '".$db->getEscaped($username)."' LIMIT 1");
		}
		
		// Bind the post array to the user object
		$post = array();
		$post['name'] = $data->name;
		$post['email'] = $email;
		$post['username'] = $username;
		$post['password']  = JUserHelper::genRandomPassword(8);
		$original = $post['password'];
		$post['password2'] = $post['password'];
		if (!$user->bind($post, 'usertype'))
			JError::raiseError(500, $user->getError());

		// Set some initial user values
		$user->set('id', 0);
		$user->set('usertype', '');
		$user->set('gid', $authorize->get_group_id('', $newUsertype, 'ARO'));

		$date =& JFactory::getDate();
		$user->set('registerDate', $date->toMySQL());

		// If user activation is turned on, we need to set the activation information
		$useractivation = $usersConfig->get('useractivation');
		if ($useractivation == '1')
		{
			$user->set('activation', JUtility::getHash($post['password']));
			$user->set('block', '1');
		}
		$user->set('lastvisitDate', '0000-00-00 00:00:00');

		// If there was an error with registration, set the message
		if (!$user->save())
		{
			return false;
			JError::raiseWarning('', JText::_($user->getError()));
		}
		
		// Hack for community builder - approve the user so that he can login
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_comprofiler'.DS.'comprofiler.php'))
		{
		   $db->setQuery("INSERT INTO #__comprofiler SET approved = 1 , user_id = ".$user->get('id')." , id = ".$user->get('id')." , confirmed = 1");
		   $db->query();
		}
		
		// Send registration confirmation mail
		$password = $original;
		// Disallow control chars in the email
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password);
		
		RSMembership::sendUserEmail($user, $password);
		RSMembership::createUserData($user->get('id'), $data->fields);
		
		return $user->get('id');
	}
	
	function sendUserEmail(&$user, $password)
	{
		global $mainframe;
		
		$lang = JFactory::getLanguage();
		$lang->load('com_rsmembership', JPATH_SITE);

		$db =& JFactory::getDBO();

		$name 		= $user->get('name');
		$email 		= $user->get('email');
		$username 	= $user->get('username');

		$usersConfig 	= &JComponentHelper::getParams('com_users');
		$sitename 		= $mainframe->getCfg('sitename');
		$useractivation = $usersConfig->get('useractivation');
		$mailfrom 		= $mainframe->getCfg('mailfrom');
		$fromname 		= $mainframe->getCfg('fromname');
		$siteURL		= JURI::base();
		if (JPATH_BASE == JPATH_ADMINISTRATOR && strpos($siteURL, '/administrator') !== false)
			$siteURL = substr($siteURL, 0, -14);

		$subject = sprintf(JText::_('RSM_NEW_EMAIL_SUBJECT'), $name, $sitename);
		$subject = html_entity_decode($subject, ENT_QUOTES);

		if ($useractivation == 1)
			$message = sprintf(JText::_('RSM_NEW_EMAIL_ACTIVATE'), $name, $sitename, $siteURL.'index.php?option=com_user&task=activate&activation='.$user->get('activation'), $siteURL, $username, $password);
		else
			$message = sprintf(JText::_('RSM_NEW_EMAIL'), $name, $sitename, $siteURL, $username, $password);
		$message = html_entity_decode($message, ENT_QUOTES);

		// Get all Super Administrators
		$db->setQuery("SELECT name, email, sendEmail FROM #__users WHERE LOWER(usertype) = 'super administrator'");
		$rows = $db->loadObjectList();

		// Send email to user
		if (!$mailfrom || !$fromname)
		{
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}
		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);

		$lang->load('com_user', JPATH_SITE);
		
		// Send notification to all Administrators
		$subject2 = sprintf(JText::_('Account details for'), $name, $sitename);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);
		foreach ($rows as $row)
			if ($row->sendEmail)
			{
				$message2 = sprintf(JText::_('SEND_MSG_ADMIN'), $row->name, $sitename, $name, $email, $username);
				$message2 = html_entity_decode($message2, ENT_QUOTES);
				JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
			}
	}
	
	function createUserData($user_id, $post)
	{
		$user_id = (int) $user_id;
		
		$this->_db->setQuery("SELECT `user_id` FROM #__rsmembership_users WHERE `user_id`='".$user_id."'");
		if (!$this->_db->loadResult())
		{
			$this->_db->setQuery("INSERT INTO #__rsmembership_users SET `user_id`='".$user_id."'");
			$this->_db->query();
		}
		
		$columns = array();
		
		$this->_db->setQuery("SELECT * FROM #__rsmembership_fields WHERE published='1' ORDER BY ordering");
		$fields = $this->_db->loadObjectList();
		foreach ($fields as $field)
		{
			if (!isset($post[$field->name]))
				continue;
			
			if (is_array($post[$field->name]))
				$post[$field->name] = implode("\n", $post[$field->name]);
			
			$columns[] = "`f".$field->id."`='".$this->_db->getEscaped($post[$field->name])."'";
		}
		
		$this->_db->setQuery("UPDATE #__rsmembership_users SET ".implode(", ", $columns)." WHERE user_id='".$user_id."' LIMIT 1");		
		$this->_db->query();
	}
	
	function updateGid($user_id, $gid, $unblock=false)
	{
		$db 	 	 =& JFactory::getDBO();
		$user_id 	 = (int) $user_id;
		$gid	 	 = (int) $gid;
		$unblock_sql = $unblock ? ", `block`='0'" : "";
		
		$db->setQuery("SELECT id FROM #__core_acl_aro WHERE `value`='".$user_id."' AND `section_value`='users'");
		$db->setQuery("UPDATE #__core_acl_groups_aro_map SET `group_id`='".$gid."' WHERE `aro_id`='".$db->loadResult()."'");
		$db->query();
		$db->setQuery("SELECT `name` FROM #__core_acl_aro_groups WHERE `id`='".$gid."'");
		$db->setQuery("UPDATE #__users SET `gid`='".$gid."', `usertype`='".$db->getEscaped($db->loadResult())."' $unblock_sql WHERE `id`='".$user_id."'");
		$db->query();
	}
	
	function disableUser($user_id)
	{
		$db =& JFactory::getDBO();
		$db->setQuery("UPDATE #__users SET `block`='1' WHERE `id`='".(int) $user_id."'");
		$db->query();
	}
}
?>