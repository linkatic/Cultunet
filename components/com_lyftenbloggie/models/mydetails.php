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

class LyftenBloggieModelMyDetails extends JModel
{
	var $_id 		= null;
	var $_author 	= null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		$settings 			= & BloggieSettings::getInstance();
		$this->_adminLevel 	= $settings->get('adminLevel', '0');

		//set the identifier
		$user				=& JFactory::getUser();
		$this->_id 			= $user->get('id');
		$this->_author 		= null;
	}

	/**
	 * Method to store the author
	 **/
	function store($data)
	{
		$author  =& $this->getTable('authors', 'Table');
		
		//Load Old Data
		$author->_tbl_key = 'user_id';
		$author->load($this->_id);

		//Is New
		if(!$author->id){
			$author  =& $this->getTable('authors', 'Table');
		}

		// bind it to the table
		if (!$author->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}
	
		//set user ID
		$user 				= & JFactory::getUser();
		$author->user_id 	= $user->get('id');

		if (isset( $_FILES['avatar']) and !$_FILES['avatar']['error'] ) {
			//Get the blog author helper
			require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helper.php' );
			$author->avatar = BlogAuthor::createAvatar($_FILES['avatar']['tmp_name'], $user->get('id')."_avatar.jpg", '', $_FILES['avatar']['name']);
		}
			
		//Save User's Name if changed
		$name		= $user->get('name');
		$userData	= JRequest::getVar( 'user', null, 'post', 'array' );	

		if ($name != $userData['name'])
		{
			$user->name = $userData['name'];
			if (!$user->save()) {
				$this->setError( $user->getError() );
				return false;
			}
		}

		// Get a state and parameter variables from the request
		$params	= JRequest::getVar( 'params', null, 'post', 'array' );		

		// Build parameter INI string
		if (is_array($params))
		{
			$txt = array ();
			foreach ($params as $k => $v) {
				$txt[] = "$k=$v";
			}
			$author->attribs = implode("\n", $txt);
		}
		
		// Make sure the data is valid
		if (!$author->check()) {
			$this->setError($author->getError());
			return false;
		}

		// Store it in the db
		if (!$author->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		$this->_author	=& $author;
		
		return true;
	}

	/**
	 * Method to get author data
	 **/
	function &getAuthor()
	{
		if ($this->_loadAuthor())
		{

		}
		else  $this->_initAuthor();

		return $this->_author;
	}

	/**
	 * Method to load author data
	 **/
	function _loadAuthor()
	{
		// Lets load the author if it doesn't already exist
		if (empty($this->_author))
		{
			$query = 'SELECT a.*, u.name'
					. ' FROM #__bloggies_authors as a'
					. ' LEFT JOIN #__users AS u ON u.id = a.user_id'	
					. ' WHERE a.user_id = '.$this->_id
					;
			$this->_db->setQuery($query);
			$this->_author = $this->_db->loadObject();
			
			if(!empty($this->_author))
				$this->_author->attribs = new JParameter( $this->_author->attribs );

			return (boolean) $this->_author;
		}
		return true;
	}

	/**
	 * Method to initialise the author data
	 **/
	function _initAuthor()
	{
		// Lets load the author if it doesn't already exist
		if (empty($this->_author))
		{
			$query = 'SELECT u.id, u.name, u.gid'
					. ' FROM #__users as u'	
					. ' WHERE u.id = '.$this->_id
					;
			$this->_db->setQuery($query);
			$user = $this->_db->loadObject();

			$author = new stdClass();
			$author->id					= 0;
			$author->user_id			= (isset($user->id)) ? $user->id : 0;
			$author->name				= (isset($user->name)) ? $user->name : null;
			$author->admin				= (isset($user->gid) && $this->_adminLevel == $user->gid) ? 1 : 0;
			$author->about				= null;
			$author->permissions		= null;
			$author->avatar				= null;
			$author->attribs			= new JParameter('');
			$author->published			= 1;
			$this->_author				= $author;
			return (boolean) $this->_author;
		}
		return true;
	}
}
?>