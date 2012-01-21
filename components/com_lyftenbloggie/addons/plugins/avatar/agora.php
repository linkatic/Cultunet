<?php
/**
 * Agora Avatar Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class AgoraAvatarPlugin extends BloggiePlugin
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
		//Get Config
		$agora_cfg = JPATH_ROOT.DS.'components'.DS.'com_agora'.DS.'cache'.DS.'cache_config.php';
		if (is_file($agora_cfg)) {
			include_once($agora_cgf);
			$avatars_dir = $agora_config['o_avatars_dir'];
		} else {
			$query = 'SELECT c.conf_value'.
				' FROM #__agora_config AS c'.
				' WHERE c.conf_name = c.o_avatars_dir';
			$this->_db->setQuery( $query );
			$avatars_dir = $this->_db->loadResult();
		}

		//Get Avatar
		$query = 'SELECT a.id'.
			' FROM #__users u, #__agora_users a'.
			' WHERE u.id = a.jos_id'.
			' WHERE u.id = a.jos_id'.
			' AND u.id = \''.$userid.'\'';
		$this->_db->setQuery( $query );
		$avatar = $this->_db->loadResult();
		$avatar = ($avatar) ? $avatars_dir.'/'.$avatar_sig : $avatars_dir.'/noavatar_sm.gif';

		return $avatar;
	}
}