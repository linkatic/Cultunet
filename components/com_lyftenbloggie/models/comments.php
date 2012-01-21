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

class LyftenBloggieModelComments extends JModel
{
	var $_entry 		= null;
	var $_tags 			= null;
	var $_id 			= null;
	var $_position 		= null;
	var $_return 		= null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		global $mainframe;

		// Get the page/component configuration
		$params = &$mainframe->getParams('com_lyftenbloggie');

		// Request variables
		$limit		= $params->get('display_num', $mainframe->getCfg('list_limit'));
		$limitstart	= JRequest::getInt('limitstart');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	}

	/**
	 * Method to set the faq id
	 **/
	function setId($id)
	{
		// Set new faq ID
		$this->_id			= $id;
		$this->_entry		= null;
	}

	/**
	 * Overridden get method to get properties from the entry
	 **/
	function get($property, $default=null)
	{
		if ($this->_loadEntry()) {
			if(isset($this->_entry->$property)) {
				return $this->_entry->$property;
			}
		}
		return $default;
	}
	
	/**
	 * Overridden set method to pass properties on to the entry
	 **/
	function set( $property, $value=null )
	{
		if ($this->_loadEntry()) {
			$this->_entry->$property = $value;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Method to get data for the entry view
	 **/
	function &getEntry( )
	{
		/*
		* Load the entry data
		*/
		if ($this->_loadEntry())
		{
		}

		return $this->_entry;
	}

	/**
	 * Method to load required data
	 **/
	function _loadEntry()
	{
		if($this->_id == '0') {
			return false;
		}

		if (empty($this->_entry))
		{
			global $mainframe;

			$user		=& JFactory::getUser();
			$settings 	= & BloggieSettings::getInstance();

			$query = 'SELECT e.id, e.title, e.created, e.created_by, e.created_by, e.attribs,'
				. ' e.metadesc, e.metakey, e.metadata, e.metadata, e.access,'
				. ' u.name AS author, u.usertype, c.title as cattitle,'
				. ' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,'
				. ' CASE WHEN CHAR_LENGTH(c.slug) THEN c.slug ELSE 0 END as catslug'
				. ' FROM #__bloggies_entries AS e'
				. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
				. ' LEFT JOIN #__users AS u ON u.id = e.created_by'
				. ' WHERE e.id = '.$this->_id
				;
			$this->_db->setQuery($query);
			$this->_entry = $this->_db->loadObject();
	
			$pparams						= new JParameter($this->_entry->attribs);
			$this->_entry->cattitle 		= ($this->_entry->cattitle)?$this->_entry->cattitle:JText::_('UNCATEGORIZED');
			$this->_entry->catslug 			= ($this->_entry->catslug)?$this->_entry->catslug:strtolower(JText::_('UNCATEGORIZED'));
			$this->_entry->allowComments	= $pparams->get('allow_comments');
			$this->_entry->archive			= (!empty($this->_entry)) ? JHTML::_('date',  $this->_entry->created, '&year=%Y&month=%m&day=%d') : '';
			$this->_entry->CommentReport	= $settings->get('allowReport');
	
			return (boolean) $this->_entry;
		}
		return true;
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
			$query 			= $this->_buildCommentQuery();
			$this->_data 	= $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			$n = count($this->_data);
			for($i=0; $i<$n; $i++)
			{
				//Checks comment type (1=user, 2=trackback)
				if($this->_data[$i]->type == 2) {
					$this->_data[$i]->avatar = BLOGGIE_ASSETS_URL.'/avatars/default.png';
				}else{
					$this->_data[$i]->avatar = BloggieAuthor::getAvatarURL($this->_data[$i]->user_id);
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
					. ' WHERE c.entry_id = \''.(int)$this->_entry->id.'\''
					. ' AND state = 1'
					. ' ORDER BY c.date DESC'
					;
		return $query;
	}

	/**
	 * Method to store comment
	 **/
	function store($data)
	{
		global $mainframe;

		//initialize variables
		$settings 	= & BloggieSettings::getInstance();
		$user		= & JFactory::getUser();	
		$userid		= $user->get('id');	
		$entryid 	= (int)$data['id'];	
		$datenow 	= & JFactory::getDate();
		$date		= $datenow->toMySQL();
		$error		= false;

		//Verify Post Data
		$comment 		= (isset($data['comment'])) ? $data['comment'] 	: '';
		$website 		= (isset($data['website'])) ? $data['website'] 	: '';
		$email 			= (isset($data['email'])) 	? $data['email'] 	: '';
		$name 			= (isset($data['name'])) 	? $data['name'] 	: $user->get('name');	

		// Handle Captcha
		if($settings->get('enableCaptcha')){
			BloggieFactory::import('captcha', 'helpers');
			if(!$captcha = BloggieCaptcha::checkCaptcha()) {
				JError::raiseNotice('SOME_ERROR_CODE', JText::_('CAPTCHA CODE IS INCORRECT'));
				$error = true;
			}
		}

		if($user->guest && !$settings->get('allowAnon')){
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('YOU NEED TO BE LOGGED IN TO COMMENT'));
			$error = true;
		}
		if(!$name && $settings->get('allowAnon')){
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('PLEASE ENTER A NAME'));
			$error = true;
		}
		if(!$email && $settings->get('allowAnon')){
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('PLEASE ENTER AN EMAIL ADDRESS'));
			$error = true;
		}
		if(!$entryid){
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('AN ERROR HAS OCCURRED'));
			$error = true;
		}
		if(!$comment){
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('PLEASE ENTER A COMMENT'));
			$error = true;
		}

		//Return if errors
		if($error == true) {
			$this->_return = '&comment='.base64_encode($comment).'&website='.base64_encode($website).'&email='.base64_encode($email).'&name='.base64_encode($name);
			return false;
		}

		//filter comment if enabled
		if($settings->get( 'enableBadWord' ))
		{
			// Initialize variables
			$words			= $settings->get( 'theBadWords' );
			$replace_str	= $settings->get( 'replaceBadWords', "@#$*!" );

			//Return if there are no words
			if($words)
			{
				$bad_words = explode(',', $words);

				for ($x=0; $x < count($bad_words); $x++)
				{
					$fix = isset($bad_words[$x]) ? $bad_words[$x] : '';
					$_replace_str = $replace_str;
					if (strlen($replace_str)==1)
					{
						$_replace_str = str_pad($_replace_str, strlen($fix), $replace_str);
					}

					$comment = preg_replace('/'.$fix.'/i', $_replace_str, $comment);
				}
			}
		}

		//Get User IP
		$author_ip	= BloggieFactory::getIP();
		$state		= 1;

		//filter comment if enabled
		if($settings->get( 'spamCheck' ))
		{
			$akismet = BloggieFactory::load('spam', 'libraries');
			if ($akismet->verify())
			{
				if ( $akismet->isSpam($author_ip, $name, $email, $website, $comment) == true)
					$state = 0;
			}
		}

		$query = "INSERT INTO `#__bloggies_comments` SET `entry_id`='$entryid',`author`=".$this->_db->Quote( $this->_db->getEscaped( $name, true ), false ).",`user_id`='$userid',`author_email`=".$this->_db->Quote( $this->_db->getEscaped( $email, true ), false ).",`author_url`=".$this->_db->Quote( $this->_db->getEscaped( $website, true ), false ).",`author_ip`='$author_ip',`content`=".$this->_db->Quote( $this->_db->getEscaped( $comment, true ), false ).",`type`='1',`date`='$date',`state`='$state'";
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			//return false;
		}

		//Send Notification
		$mailer = BloggieFactory::getMailer();
		$mailer->setTemplate('new_comment');
		if($mailer->emailWho($entryid))
		{
			$name = ($userid) ? $name : $name.' ['.JText::_('GUEST').']';
			$mailer->assign('comment', array('author'=>$name, 'text'=>$comment));
			$mailer->sendMail(JText::_('NEW COMMENT'));
		}

		return true;
	}
}
?>