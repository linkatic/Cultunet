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
 * LyftenBloggie Framework Author class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieAuthor extends JObject
{
	var $_settings	= array();
	var $_user		= null;
	var $_db		= null;

	function BloggieAuthor($id=null)
	{
		$this->_db 		= &JFactory::getDBO();
		$this->_user	= new stdClass();
	
		//Set User Data
		$this->_loadData($id);
	}

	/**
	 * Returns a reference to a global access object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$access = & BloggieAuthor::getInstance();
	 *
	 * @param string Group ID to be passed to the access class
	 * @return	BloggieAuthor object.
	 **/
	function &getInstance($id=null)
	{
		static $instances;

		if (!isset( $instances )) {
			$instances = array();
		}

		if (empty($instances[$id]))
		{
			$instance[$id] = new BloggieAuthor($id);
		}

		return $instance[$id];
	}

	/**
	 * Get Author value
	 *
	 * @param	string	user variable
	 * @param	string	default value
	 * @return	string
	 **/
	function get($variable, $default=null)
	{
		$parts = explode( '.', $variable );
		if(isset($parts[1]) && $parts[0] == 'attrib')
		{
			return $this->_user->attribs->get($parts[1], $default);
		}else{
			return isset($this->_user->$variable) ? $this->_user->$variable : $default;
		}
	}

	/**
	 * method check author's folders
	 *
	 * @access	public
	 * @return	viod
	 */
	function checkFolders()
	{
		// Set the path definitions
		$media 			= array();
		$media['base']	= JPath::clean(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$this->_user->user_id);
		$media['url'] 	= 'images/lyftenbloggie/'.$this->_user->user_id;

		//Check Author's Upload folder
		if (!is_dir($media['base'])){
			@mkdir($media['base'], 0755);
		}
		if (!file_exists($media['base'].DS.'index.html')) {
			chmod($media['base'], 0777);
			$handle = fopen($media['base'].DS.'index.html', 'x+');
			fclose($handle);
		}

		//Check Author's entry display folder
		if (!is_dir($media['base'].DS.'display')){
			@mkdir($media['base'].DS.'display', 0755);
		}
		if (!file_exists($media['base'].DS.'display'.DS.'index.html')) {
			chmod($media['base'], 0777);
			$handle = fopen($media['base'].DS.'display'.DS.'index.html', 'x+');
			fclose($handle);
		}
		return $media;
	}
	/**
	 * Method to get Author data
	 **/
	function _loadData($id)
	{
		global $mainframe;

		if(!isset($this->_user->attribs))
		{
			//Set Joomla's user data
			$user 					= &JFactory::getUser($id);
			$this->_user->user_id	= $user->get('id');
			$this->_user->username 	= $user->get('name');
			$this->_user->email 	= $user->get('email');
			unset($user);

			//Load Author data
			$query = 'SELECT a.*'
					.' FROM #__bloggies_authors AS a'
					.' WHERE a.user_id = \''.(int)$this->_user->user_id.'\'';
			$this->_db->setQuery($query);
			if($data = $this->_db->loadObject())
			{
				$this->_user->id			= $data->id;
				$this->_user->url			= $data->url;
				$this->_user->about			= $data->about;
				$this->_user->feeds 		= '';
				$this->_user->avatar_img 	= $data->avatar;
				$this->_user->avatar 		= ($mainframe->_clientId != 3) ? $this->getAvatarURL($this->_user->user_id) : null;
				$this->_user->attribs 		= new JParameter( $data->attribs );
				unset($data);
			}else{
				$this->_user->id			= 0;
				$this->_user->url			= '';
				$this->_user->about			= null;
				$this->_user->feeds 		= '';
				$this->_user->avatar_img 	= '';
				$this->_user->avatar		= null;
				$this->_user->attribs		= new JParameter('');
			}
		}
	}

	/**
	 * Method to return Author Profile data
	 **/
	function getProfile()
	{
		global $mainframe;
		if(!$this->_user->feeds && !$mainframe->isAdmin() && ($mainframe->_clientId != 3))
		{
			//Get location of images
			$template = BloggieTemplate::getInstance();
			if(is_dir($template->_template_path.DS.'images'.DS.'social'))
			{
				$assets = $template->_template_url.'/images/social/';
			}else{
				$assets = BLOGGIE_SITE_URL.'/addons/themes/system/images/social/';
			}

			//Get Feeds
			$this->_user->feeds 	= ($this->_user->attribs->get('facebookURL'))?'<a href="'.$this->_user->attribs->get('facebookURL').'" target="_blank"><img src="'.$assets.'facebook.png" border="0"></a>':'';
			$this->_user->feeds 	.= ($this->_user->attribs->get('diggURL'))?'<a href="'.$this->_user->attribs->get('diggURL').'" target="_blank"><img src="'.$assets.'digg.png" border="0"></a>':'';
			$this->_user->feeds 	.= ($this->_user->attribs->get('deliciousURL'))?'<a href="'.$this->_user->attribs->get('deliciousURL').'" target="_blank"><img src="'.$assets.'delicious.png" border="0"></a>':'';
			$this->_user->feeds 	.= ($this->_user->attribs->get('technoratiURL'))?'<a href="'.$this->_user->attribs->get('technoratiURL').'" target="_blank"><img src="'.$assets.'technorati.png" border="0"></a>':'';
			$this->_user->feeds 	.= ($this->_user->attribs->get('twitterURL'))?'<a href="'.$this->_user->attribs->get('twitterURL').'" target="_blank"><img src="'.$assets.'twitter.png" border="0"></a>':'';
			$this->_user->feeds 	.= ($this->_user->attribs->get('flickrURL'))?'<a href="'.$this->_user->attribs->get('flickrURL').'" target="_blank"><img src="'.$assets.'flickr.png" border="0"></a>':'';
			$this->_user->feeds 	.= ($this->_user->attribs->get('mybloglogURL'))?'<a href="'.$this->_user->attribs->get('mybloglogURL').'" target="_blank"><img src="'.$assets.'mybloglog.png" border="0"></a>':'';
			$this->_user->feeds 	.= ($this->_user->attribs->get('ffindURL'))?'<a href="'.$this->_user->attribs->get('ffindURL').'" target="_blank"><img src="'.$assets.'friendfeed.png" border="0"></a>':'';
		}

		return $this->_user;
	}

	/**
	 * Creates the Avatar URL
	 **/
	function getAvatarURL( $userid=false )
	{
		$settings = & BloggieSettings::getInstance();
		$avatarUsed = $settings->get('avatarUsed', 'default');

		//Call Plugin and get the avatar
		$avatar_obj = BloggieFactory::getPlugin('avatar', $avatarUsed );
		$avatar = $avatar_obj->getAvatar($userid);

		return ($avatar) ? $avatar : BLOGGIE_ASSETS_URL.'/avatars/default.png';
	}

	/**
	 * Method to store the author
	 **/
	function store($data)
	{
		$author  =& JTable::getInstance('profiles', 'Table');

		//Load Old Data
		$author->_tbl_key = 'user_id';
		$author->load($this->_user->user_id);

		//Is New
		if(!$author->id){
			$author  =& JTable::getInstance('profiles', 'Table');
		}

		// bind it to the table
		if (!$author->bind($data)) {
			$this->setError(500, $this->_db->getErrorMsg() );
			unset($author);
			return false;
		}

		//set user ID
		$author->user_id = $this->_user->user_id;

		//upload author avatar
		if (isset( $_FILES['avatar'] ) and !$_FILES['avatar']['error'] )
		{
			if($avatar = $this->createAvatar($_FILES['avatar']))
			{
				$author->avatar = $avatar;
			}
		}

		//Save User's Name if changed
		if (isset($data['name']) && $this->_user->username != $data['name'])
		{
			$user = & JFactory::getUser();
			$user->name = $data['name'];
			if (!$user->save()) {
				$this->setError( $user->getError() );
				unset($author);
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
			unset($author);
			return false;
		}

		// Store it in the db
		if (!$author->store()) {
			$this->setError(500, $this->_db->getErrorMsg() );
			return false;
		}

		unset($author);
	
		return true;
	}

	/*
	* Create Avatar
	*/
	function createAvatar(&$file)
	{
		//Get Default Height & Width
		$settings 	= & BloggieSettings::getInstance();
		$width		= $settings->get('maxAvatarWidth', '80');
		$height		= $settings->get('maxAvatarHeight', '80');

		//Get avatar path
		$filepath 	= BLOGGIE_SITE_PATH.DS.'assets'.DS.'avatars';
	
		//get image class
		BloggieFactory::import('image');
		$handle = new BloggieImage($file);

		if ($handle->uploaded)
		{
			//Adjust Height & Width
			$width 	= ($width < $handle->image_src_x)?$width:$handle->image_src_x;
			$height = ($height < $handle->image_src_y)?$height:$handle->image_src_y;

			if ($width != 0 && $height != 0) {
				$handle->image_resize = true;
				$handle->image_x = $width;
				$handle->image_y = $height;
			}

			$handle->Process($filepath);
			if ($handle->processed)
			{
				//Delete the old image
				if($this->_user->avatar_img && $this->_user->avatar_img != $handle->file_dst_name)
				{
					if(file_exists($filepath.DS.$this->_user->avatar_img)) {
						unlink($filepath.DS.$this->_user->avatar_img);
					}
				}
				return $handle->file_dst_name;
				
			} else {
				$this->setError($handle->error);
				return false;
			}

		} else {
			$this->setError($handle->error);
			return false;
		}
	}
}
?>