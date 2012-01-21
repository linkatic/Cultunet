<?php
/**
 * ComBuilder Avatar Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class ComBuilderAvatarPlugin extends BloggiePlugin
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
		//Ensure combuilder is installed
		if(!is_dir(JPATH_SITE.DS.'components'.DS.'com_comprofiler'))
			return BLOGGIE_ASSETS_URL.'/avatars/default.png';
				
		$query = 'SELECT a.avatar, a.avatarapproved '.
			'FROM #__comprofiler AS a ' .
			'WHERE a.user_id = '.(int)$userid ;
		$this->_db->setQuery($query);
		$data = ($data = $this->_db->loadObject()) ? $data : array();
		$avatar = JURI::base().'components/com_comprofiler/plugin/templates/default/images/avatar/nophoto_n.png';
		if( isset($data->avatarapproved) && $data->avatarapproved == 1 && isset($data->avatar)) {
			$avatar = JURI::base().'images/comprofiler/' . $data->avatar ;
		}
		return $avatar;
	}
}