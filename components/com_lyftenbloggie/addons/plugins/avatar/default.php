<?php
/**
 * Default Avatar Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class DefaultAvatarPlugin extends BloggiePlugin
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
		$query = 'SELECT c.avatar'
			. ' FROM #__bloggies_authors AS c'
			. ' WHERE c.user_id = '. (int)$userid
			;
		$this->_db->setQuery($query);
		$avatar = $this->_db->loadResult();
		$avatar = ($avatar) ? BLOGGIE_ASSETS_URL.'/avatars/'.$avatar : BLOGGIE_ASSETS_URL.'/avatars/default.png';
		return $avatar;
	}
}