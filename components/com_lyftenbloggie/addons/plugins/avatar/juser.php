<?php
/**
 * JUser Avatar Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class JUserAvatarPlugin extends BloggiePlugin
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Gets Avatar's URL
	 */
	function getAvatar($userid)
	{
		//Ensure JUser is installed
		if(!is_dir(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_juser'))
			return BLOGGIE_ASSETS_URL.'/avatars/default.png';

		//Get Avatar
		$query = 'SELECT avatar'.
			' FROM #__juser_users_additional_data'.
			' WHERE `user_id` = '.(int)$userid;
		$this->_db->setQuery( $query );
		$data = $this->_db->loadResult();

		$avatar = JURI::base().'administrator/components/com_juser/img/default_avatar.jpg';
		if($data){
			$query = "SELECT `selected` FROM #__je_config WHERE `section` = 'general' AND name = 'uploaded_avatar_directory'";
			$this->_db->setQuery( $query );
			$path = $this->_db->loadResult();
		
			//Create avatar
			$path		= str_replace('\\','/',$path);
			$path		= str_replace($mosConfig_absolute_path,$mosConfig_live_site,$path);
			$avatar 	= $path.'/user_'.(int)$userid.strtolower(strrchr($data,'.'));
		}
		return $avatar;
	}
}