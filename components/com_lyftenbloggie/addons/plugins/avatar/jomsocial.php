<?php
/**
 * jomSocial Avatar Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class jomSocialAvatarPlugin extends BloggiePlugin
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
		//Ensure jomSocial is installed
		if(!is_dir(JPATH_SITE.DS.'components'.DS.'com_community'))
			return BLOGGIE_ASSETS_URL.'/avatars/default.png';

		$query = 'SELECT a.avatar FROM #__community_users AS a WHERE a.userid = '.(int)$userid;
		$this->_db->setQuery($query);
		$data = ($data = $this->_db->loadObject()) ? $data : array();
		$avatar =  JURI::base().'components/com_community/assets/default.jpg';
		if( isset($data->avatar) ) {
			$avatar = $data->avatar ;
		}
		return $avatar;
	}
}