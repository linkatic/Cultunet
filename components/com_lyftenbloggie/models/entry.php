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

jimport('joomla.application.component.model');

class LyftenBloggieModelEntry extends JModel
{

	var $_entry 		= null;
	var $_tags 			= null;
	var $_id 			= null;
	var $_author		= null;
	var $_position 		= null;
	
	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();
		
		global $mainframe;
		
		$settings 	= & BloggieSettings::getInstance();
		$limitstart	= JRequest::getInt('limitstart');
		$limit 		= $settings->get('commentEntryLimit', 5);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		$this->_id	= JRequest::getVar('id', 0, '', 'int');
	}

	/**
	 * Method to get data
	 **/
	function getComments()
	{
		global $mainframe;
		
		// Lets load the files if it doesn't already exist
		if (empty($this->_data))
		{
			$settings 	= & BloggieSettings::getInstance();

			//Call Plugin
			$avatar_obj = BloggieFactory::getPlugin('avatar', $settings->get('avatarUsed', 'default') );

			$query = $this->_buildCommentQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

			$n = count($this->_data);
			for($i=0; $i<$n; $i++)
			{
				//Checks comment type (1=user, 2=trackback)
				if($this->_data[$i]->type == 2) {
					$this->_data[$i]->avatar = BLOGGIE_ASSETS_URL.'/avatars/default.png';
				}else{
					//get avatar from plugin
					$this->_data[$i]->avatar = $avatar_obj->getAvatar($this->_data[$i]->user_id);
					$this->_data[$i]->avatar = ($this->_data[$i]->avatar) ? $this->_data[$i]->avatar : BLOGGIE_ASSETS_URL.'/avatars/default.png';
				}
			}
		}
			
		return $this->_data;
	}
	
	/**
	 * Method to get the total
	 **/
	function getTotal()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildCommentQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}
	
	/**
	 * Method to get a pagination object
	 **/
	function getPagination()
	{
		// Lets load the files if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to build the query
	 **/
	function _buildCommentQuery()
	{
		$query = 'SELECT c.*,'
				. ' CASE WHEN CHAR_LENGTH(c.author) THEN c.author ELSE u.name END as poster'
				. ' FROM #__bloggies_comments AS c'
				. ' LEFT JOIN #__users AS u ON u.id = c.user_id'
				. ' WHERE c.entry_id = \''.(int)$this->_id.'\''
				. ' AND state = 1'
				. ' ORDER BY c.date DESC'
				;
		return $query;
	}
}
?>