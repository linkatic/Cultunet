<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * LyftenBloggie Framework Factory class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieFactory
{
	/**
	 *  Determines if the currently logged in user is has access
	 **/
	function allowAccess($permission, $redirect=false)
	{
		$access 	= BloggieAccess::getInstance();
		$allowed 	= $access->get($permission);

		//What to do
		if(!$allowed && $redirect)
		{
			global $mainframe;

			$view 	= JRequest::getVar('view');
			$mode 	= JRequest::getVar('mode');
			$msg 	= JText::_('HIGHER ACCESS RIGHTS');
			$url 	= JURI::base().'index.php';

			if($access->get('user.id')) //Deny if User Logged in
			{
				if($view == 'entry') {
					$url = JRoute::_('index.php?option=com_lyftenbloggie', false);
				}else if($view == 'author' && $mode == 'pending') {
					$msg = JText::_('YOU MUST BE AN AUTHOR TO VIEW THIS RESOURCE');
					$url = JRoute::_('index.php?option=com_lyftenbloggie&view=author', false);
				}else if($view == 'author') {
					$msg = JText::_('YOU MUST BE AN AUTHOR TO VIEW THIS RESOURCE');
					$url = JRoute::_('index.php?option=com_lyftenbloggie', false);
				}
			}else{ //Force Login
				$uri = & JFactory::getURI();
				$url = JRoute::_('index.php?option=com_user&view=login&return='.base64_encode($uri->toString()), false);
				$msg = JText::_('MUST BE LOGIN');
				unset($uri);
			}
			$mainframe->redirect($url, $msg );
		}

		return $allowed;
	}

	/**
	 * Determines if the currently logged in user can edit
	 **/
	function canEdit($entry_id, $created_by)
	{
		$access = BloggieAccess::getInstance();
		return ( ($entry_id > 0 && ($created_by == $access->get('user.id')) && $access->get('author.author_access')) || $access->get('admin.admin_access'));
	}

	/**
	 * Determines if the currently logged in user can edit
	 **/
	function canPublish($entry_id, $created_by)
	{
		$access = BloggieAccess::getInstance();
		return ( ($entry_id > 0 && ($created_by == $access->get('user.id')) && $access->get('author.can_publish')) || $access->get('admin.admin_access'));
	}

	/**
	 * Determines if the currently logged in user can edit
	 **/
	function getMailer($sig = 'default')
	{
		include_once(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'core'.DS.'email.php');

		$emailer = BloggieEmail::getInstance($sig);

		return $emailer;
	}

	/**
	 *  Determines if the currently logged in user is a super administrator
	 **/
	function isAdministrator($userid = null)
	{
		$my	=& JFactory::getUser($userid);		
		return ( $my->usertype == 'Super Administrator' || $my->usertype == 'Administrator' || $my->usertype == 'Manager' );
	}

	/**
	 * Method to get access level settings 
	 **/
	function getAccesslevel($id = '')
	{
		$access = BloggieAccess::getInstance($id);
		return $access;
	}

	/**
	* Build the select list for access level
	**/
	function getAccesslevels($id = '')
	{
		$db 			=& JFactory::getDBO();
		$guest_obj 		= new stdClass();
		$guest_array 	= array();

		//Get single Levels
		if($id)
		{
			if($id > 1)
			{
				$query = "SELECT g.id, g.name"
					. " FROM #__core_acl_aro_groups AS g"
					. " WHERE id = '".(int)$id."'";
				$db->setQuery( $query );
				return $db->loadObject();
			}else{
				$guest_obj->id = 1;
				$guest_obj->name = JText::_('GUEST');
				return $guest_obj;
			}
		}
		
		//Get All Levels
		$query = "SELECT g.id AS value, g.name AS text"
			. " FROM #__core_acl_aro_groups AS g"
			. " WHERE LOWER(g.name) NOT IN ('public backend','public frontend','users','root')"
			;
		$db->setQuery( $query );
		if(!$access = $db->loadObjectList())
		{
			$query = 'SELECT id AS value, name AS text'
				. ' FROM #__groups'
				. ' WHERE LOWER(name) <> \'public\''
				. ' ORDER BY id'
				;
			$db->setQuery( $query );
			$access = $db->loadObjectList();
		}
		
		//Create Guest Value
		$guest_obj->value = 1;
		$guest_obj->text = JText::_('GUEST');
		$guest_array[] = $guest_obj;

		//Merge Guest with others
		$access = array_merge($guest_array, $access);

		return $access;
	}

	/**
	 * Returns the SEF URL
	 **/ 
	function getSEFLink($url='')
	{
		global $mainframe;

		if(!$url) return;

		$uri 		=& JURI::getInstance();
		$baseURL 	= $uri->toString( array('scheme', 'host', 'port'));

        // Do not run plugin if SEF is disabled
        $config =& JFactory::getConfig();
        if ($config->getValue('sef'))
		{
			// check if joomsef is enabled
			$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sef'.DS.'classes'.DS.'config.php';
			if(file_exists($path)) {
				require_once( $path );
				require_once( JPATH_ROOT.DS.'components'.DS.'com_sef'.DS.'sef.router.php' );
				$sefConfig =& SEFConfig::getConfig();
				if ($sefConfig->enabled) {
					$router = new JRouterJoomsef();
					$newurl	= $router->build($url);
					$url 	= $newurl->_uri;
				}else{
					$router = JSite::getRouter();
					$newurl	= $router->build($url);
					$url 	= $baseURL.'/'.trim($newurl->_path, '/');
					if($mainframe->isAdmin()) {
						$url 	= str_replace( '/administrator', '', $url );
					}
				}
			}else{
				$router = JSite::getRouter();
				$newurl	= $router->build($url);
				$url 	= $baseURL.'/'.trim($newurl->_path, '/');
				if($mainframe->isAdmin()) {
					$url 	= str_replace( '/administrator', '', $url );
				}
			}
		}else{
			$url 	= $baseURL.JURI::base(true).'/'.$url;
		}

		return $url;
	}

	/**
	 * Return the object
	 **/	 	
	function getProfile($id = null )
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$id]))
		{
			$author_obj = &BloggieAuthor::getInstance($id);
			$instance[$id] = $author_obj->getProfile();
			unset($author_obj);
		}

		return $instance[$id];
	}

	/**
	 * Return the Author's Avatar
	 **/	 	
	function getAvatar($userid = null )
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$userid]))
		{
			$author_obj = &BloggieAuthor::getInstance($userid);
			$instance[$userid] = $author_obj->getAvatarURL($userid);
			unset($author_obj);
		}

		return $instance[$userid];
	}

	/**
	 * Return the object
	 **/	 	
	function getPlugin( $type, $plugin = 'default' )
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$type.$plugin]))
		{
			$plugin_obj = new BloggiePlugin();
			$instance[$type.$plugin] = $plugin_obj->getPlugin($type, $plugin);
		}

		return $instance[$type.$plugin];
	}

	/**
	 * Return the Entry Data
	 **/	 	
	function getEntry( $id=0, $sig=0 )
	{
		$entry_obj = &BloggieEntry::getInstance($sig, $id);
		return $entry_obj->getEntry();
	}

	/**
	 * Return the entry's display image
	 **/	 	
	function getTrackbackLink($options = array())
	{
		// Get Comment Counts from plugins
		$settings 		= & BloggieSettings::getInstance();
		$comment_system = $settings->get('typeComments', 'default');
		
		if ( !$comment_system )
			return '';

		//Get Comment Plugin
		$plugin = BloggieFactory::getPlugin('comment', $comment_system);

		if ( !method_exists($plugin, 'getTrackbackLink') )
			return '';

		//Get Trackback
		return $plugin->getTrackbackLink($options);
	}

	/**
	 * Return the entry's display image
	 **/	 	
	function getEntryImage($image = null, $created_by = null, $modified_by = null )
	{
		$theme = BloggieTemplate::getInstance();

		//Get Default
		$return = BLOGGIE_SITE_URL.'/addons/themes/system/images/default_entry.png';
		if(file_exists($theme->_template_path.DS.'images'.DS.'default_entry.png'))
		{
			$return = $theme->_template_url.'/images/default_entry.png';
		}

		//Get Display Image
		if($image)
		{
			if(file_exists(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$created_by.DS.'display'.DS.$image))
			{
				$return = JURI::root().'images/lyftenbloggie/'.$created_by.'/display/'.$image;
			}else if($modified_by && file_exists(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$modified_by.DS.'display'.DS.$image)){
				$return = JURI::root().'images/lyftenbloggie/'.$modified_by.'/display/'.$image;
			}
		}
		return $return;
	}

	/**
	 * Return the object
	 **/	 	
	function getEditor( $plugin = 'default' )
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		// Double Check
		if(!file_exists(BLOGGIE_SITE_PATH.DS.'addons'.DS.'plugins'.DS.'editor'.DS.'default.php'))
		{
			$plugin = 'joomla';
		}

		if (!isset($instance[$plugin]))
		{
			if( $plugin == 'joomla' )
			{
				$instance[$plugin] =& JFactory::getEditor();
			}else{
				$plugin_obj = new BloggiePlugin();
				$instance[$plugin] = $plugin_obj->getPlugin('editor', $plugin);
			}
		}

		return $instance[$plugin];
	}

	/**
	 * Return the object
	 **/	 	
	function getFeed( $name='default' )
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$name]))
		{
			if(file_exists(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'output'.DS.'rss.php'))
			{
				include_once(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'output'.DS.'rss.php');
				$instance[$name] = new BloggieRSS();
			}else{
				return;
			}
		}

		return $instance[$name];
	}

	function getIP()
	{
		if ( isset($_SERVER["HTTP_CLIENT_IP"]) )
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		else if( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else if( isset($_SERVER["REMOTE_ADDR"]) )
			$ip = $_SERVER["REMOTE_ADDR"];
		else
			$ip = '';
		return $ip;
	}

	function prepareEntry($entry, $image = 'none')
	{
		global $mainframe;

		//initialize variables
		$settings 		= & BloggieSettings::getInstance();
		
		// Get Comment Counts from plugins
		$comment_system = $settings->get('typeComments', 'default');
		$entry->comcount = 0;
		if($comment_system)
		{
			$plugin = BloggieFactory::getPlugin('comment', $comment_system);
			$total = $plugin->getCount($entry->id);
			$entry->comcount = $total['approved'];
			unset($total);
		}

		if(isset($entry->cattitle))
			$entry->cattitle 	= ($entry->cattitle)?$entry->cattitle:JText::_('UNCATEGORIZED');

		if(isset($entry->catslug))
			$entry->catslug 	= ($entry->catslug)?$entry->catslug:strtolower(JText::_('UNCATEGORIZED'));

		if(isset($entry->author) && isset($entry->created_by))
			$entry->author		= BloggieFactory::getProfile($entry->created_by);

		if(isset($entry->author_url) && isset($entry->created_by))
			$entry->author_url	= ($mainframe->_clientId != 3) ? JRoute::_( 'index.php?option=com_lyftenbloggie&author='.$entry->created_by) : '';

		if(isset($entry->category) && isset($entry->catid))
			$entry->category	 = ($mainframe->_clientId != 3) ? EntriesHelper::getCatLinks($entry->catid) : '';

		if(isset($entry->tags))
			$entry->tags		 = ($mainframe->_clientId != 3) ? EntriesHelper::getTagLinks($entry->id) : '';

		if(isset($entry->created)) {
			$entry->created_nf	= $entry->created;
			$entry->created		= JHTML::_('date',  $entry->created, $settings->get('dateFormat', '%B %d, %Y'));
		}

		if(isset($entry->text)) {
			$entry->text = $entry->introtext.$entry->fulltext;	
			$entry->text = str_replace(array('{mosimage}', '{mospagebreak}', '{readmore}'), '', $entry->text);
		}

		//Get Display Image
		if($image == 'image' && isset($entry->created_by) && isset($entry->image))
		{
			//Check for Default Image
			$img = BLOGGIE_SITE_URL.'/addons/themes/system/images/default_entry.png';

			//Entry Has Image
			if($entry->image)
			{
				if(file_exists(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$entry->created_by.DS.'display'.DS.$entry->image))
				{
					$img = JURI::root().'images/lyftenbloggie/'.$entry->created_by.'/display/'.$entry->image;
				}else if(isset($entry->modified_by) && file_exists(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$entry->modified_by.DS.'display'.DS.$entry->image)){
					$img = JURI::root().'images/lyftenbloggie/'.$entry->modified_by.'/display/'.$entry->image;
				}
			}

			$entry->mainImage = '<img src="'.$img.'" alt="'.$entry->title.'" />';

		}else if($image == 'avatar' && isset($entry->created_by)) {
			$avatar = BloggieFactory::getAvatar($entry->created_by);
			$entry->mainImage = '<img src="'.$avatar.'" alt="'.$entry->author->username.'" />';
			unset($avatar);
		}

		return $entry;
	}

	/**
	 * Return the object
	 **/	 	
	function load( $name, $type = 'core' )
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$name]))
		{
			if(file_exists(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.strtolower($type).DS.strtolower($name).'.php'))
			{
				include_once(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.strtolower($type).DS.strtolower($name).'.php');
				$ClassName = 'Bloggie'.$name;
				if(class_exists($ClassName))
				{
					$instance[$name] = new $ClassName();
				}else{
					$instance[$name] = true;
				}
			}else{
				return;
			}
		}

		return $instance[$name];
	}

	/**
	 * Simple import
	 *
	 * I got really tired of typing this stuff
	 * so I made a class...lazy
	 **/	 	
	function import( $name, $type = 'core' )
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$name.$type]))
		{
			if(file_exists(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.strtolower($type).DS.strtolower($name).'.php'))
			{
				include_once(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.strtolower($type).DS.strtolower($name).'.php');
				$instance[$name.$type] = true;
			}else{
				return;
			}
		}

		return $instance[$name.$type];
	}
}