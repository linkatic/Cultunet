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

jimport('joomla.html.html');

/**
 * LyftenBloggie Framework Entry class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieEntry extends JObject
{
	var $_db	= array();
	var $_id	= null;
	var $_entry	= null;

	function BloggieEntry($id = 0)
	{
		$this->_db 	= & JFactory::getDBO();
		$this->_id	= ($id) ? $id : JRequest::getVar('id', 0, '', 'int');
	}

	/**
	 * Overridden get method to get properties from the entry
	 **/
	function get($property, $default=null)
	{
		if ($this->_loadData()) {
			if(isset($this->_entry->$property)) {
				return $this->_entry->$property;
			}
		}
		return $default;
	}

	/**
	 * Returns a reference to a global Settings object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$settings = & BloggieEntry::getInstance();
	 *
	 * @access	public
	 * @return	BloggieEntry object.
	 */
	function &getInstance($sig=0, $id=0)
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$sig]))
		{
			$instance[$sig] = new BloggieEntry($id);
		}

		return $instance[$sig];
	}

	/**
	 * Tests if entry is checked out
	 **/
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_entry->checked_out && $this->_entry->checked_out != $uid);
			} else {
				return $this->_entry->checked_out;
			}
		} elseif ($this->_id < 1) {
			return false;
		} else {
			JError::raiseWarning( 0, 'Unable to Load Data');
			return false;
		}
	}

	/**
	 * Method to checkin/unlock the entry
	 **/
	function checkin()
	{
		if ($this->_id)
		{
			$entry = & JTable::getInstance('entries', 'Table');
			return $entry->checkin($this->_id);
		}
		return false;
	}

	/**
	 * Method to checkout/lock the entry
	 **/
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the entry with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$entry = & JTable::getInstance('entries', 'Table');
			return $entry->checkout($uid, $this->_id);
		}
		return false;
	}

	/**
	 * Method to get data for the entry
	 **/
	function &getEntry()
	{
		global $mainframe;
		
		/*
		* Load the entry data
		*/
		if ($this->_loadData())
		{
			global $mainframe;
			$user	= & JFactory::getUser();

			//Check to see if the user is allowed
			$gid = (!$user->get('gid')) ? 1 : $user->get('gid');
			if ($this->_entry->access > $gid && $this->_entry->created_by != $user->get('id'))
			{
				JError::raiseWarning( 'SOME_ERROR_CODE', JText::_("ALERTNOTAUTH"));
				if($mainframe->_clientId != 3)
				{
					$mainframe->redirect( JRoute::_('index.php?option=com_lyftenbloggie', false) );
				}else{
					return false;
				}
			}
			if(!$mainframe->isAdmin())
				$this->prepareEntry();
		}
		else
		{
			$this->newEntry();
		}
		
		return $this->_entry;
	}

	/**
	 * Method to create a new entry
	 **/
	function newEntry()
	{
		global $mainframe;

		$user 		= &JFactory::getUser();
		$settings 	= &BloggieSettings::getInstance();
		$entry 		= &JTable::getInstance('entries', 'Table');
		$now 		= &JFactory::getDate('now', $mainframe->getCfg('offset'));

		$entry->id					= 0;
		$entry->author				= null;
		$entry->created_by			= $user->get('id');
		$entry->created_nf			= $now->toMySQL(); 
		$entry->text				= '';
		$entry->title				= null;
		$entry->meta_description	= '';
		$entry->meta_keywords		= '';
		$entry->modified 			= $this->_db->getNullDate();
		$entry->attribs				= "allow_comments=".($settings->get('typeComments') ? '1' : '0')."\n";
		$this->_entry				= $entry;
	}

	/**
	 * Method to load required data
	 **/
	function _loadData()
	{
		if($this->_id == '0') {
			return false;
		}

		if (empty($this->_entry))
		{
			$catView = JRequest::getVar('category');

			$query = 'SELECT e.*, u.name AS author, u.usertype, c.title as cattitle,'
				. ' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,'
				. ' CASE WHEN CHAR_LENGTH(c.slug) THEN c.slug ELSE 0 END as catslug'
				. ' FROM #__bloggies_entries AS e'
				. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
				. ' LEFT JOIN #__users AS u ON u.id = e.created_by'
				. ' WHERE e.id = '.$this->_id
				;
			$this->_db->setQuery($query);
			$this->_entry = $this->_db->loadObject();

			$this->_entry->archive		= (!empty($this->_entry)) ? JHTML::_('date',  $this->_entry->created, '&year=%Y&month=%m&day=%d') : '';
			$this->_entry->archive		= ($catView) ? '&category='.$catView : $this->_entry->archive;
			
			return (isset($this->_entry->id));
		}
		return true;
	}

	/**
	 * Method to get all Categories
	 **/
	function getCategories()
	{
		if(!$this->_entry->catid)
		{
			$query = 'SELECT id' .
					' FROM #__bloggies_categories' .
					' WHERE `default` = 1';
			$this->_db->setQuery($query);
			$this->_entry->catid = $this->_db->loadResult();
		}
		
		$query = 'SELECT id, title' .
				' FROM #__bloggies_categories' .
				' ORDER BY ordering';
		$this->_db->setQuery($query);

		$category[] 	= JHTML::_('select.option', '0', JText::_('UNCATEGORIZED'), 'id', 'title');
		$categories 	= array_merge($this->_db->loadObjectList(), $category);
		$categories 	= JHTML::_('select.genericlist',  $categories, 'catid', 'class="inputbox" size="1"', 'id', 'title', intval($this->_entry->catid));	
		
		return $categories;
	}

	/**
	 * Method to get all Sections and Categories
	 **/	
	function getTagsList()
	{
		$alltags 	= $this->gettags();
		$used 		= $this->getusedtags($this->_entry->id);

		if(!is_array($used)){
			$used = array();
		}

		$tags = '';
		$n = count($alltags);
		for( $i = 0, $n; $i < $n; $i++ ){
			$tag = $alltags[$i];
			$tags .=  '<li><label><input id="tag[]" name="tag[]" type="checkbox" value="'.$tag->id.'"' . (in_array($tag->id, $used) ? 'checked="checked"' : '') . ' />'.$tag->name.'</label></li>';
		}

		return $tags;
	}
	
	/**
	 * Method to get all tags
	 **/	
	function getTags(){
		$query = 'SELECT *'
				.' FROM #__bloggies_tags';
		$this->_db->setQuery($query);
		$tags = $this->_db->loadObjectList();
		return $tags;
	}
	
	/**
	 * Method to get all used tags
	 **/	
	function getUsedTags(){
		
		$used = '';
		$query = 'SELECT DISTINCT tag'
				.' FROM #__bloggies_relations'
				.' WHERE entry='.$this->_id;
		$this->_db->setQuery($query);
		$used = $this->_db->loadResultArray();
		return $used;
	}

	/**
	 * Method to set an entry's category
	 **/	
	function setEntryCategory($catid)
	{
		$query = 'UPDATE #__bloggies_entries AS e'
				.' SET e.catid = \''.(int)$catid.'\''
				.' WHERE e.id = \''.$this->_entry->id.'\'';
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			$this->setError(JText::_('AN ERROR HAS OCCURRED'));
		}

		return true;
	}

	/**
	 * Method to set an entry's category
	 **/	
	function setEntryState($state)
	{
		$query = 'UPDATE #__bloggies_entries AS e'
				.' SET e.state = \''.(int)$state.'\''
				.' WHERE e.id = \''.$this->_entry->id.'\'';
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			$this->setError(JText::_('AN ERROR HAS OCCURRED'));
		}

		return true;
	}

	/**
	 * Method to store the entry
	 **/
	function store($data, $xmlrpc=false)
	{
		global $mainframe;

		$this->_entry 	= & JTable::getInstance('entries', 'Table');
		$user			= & JFactory::getUser(); 
		$settings 		= & BloggieSettings::getInstance();
		$sendEmail		= false;
		$tags 			= JRequest::getVar( 'tag', array(), 'post', 'array');

		// Bind the form fields to the table
		if (!$this->_entry->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if($xmlrpc)
		{
			$this->_entry->created = (is_array($data['created'])) ? $data['created']['year'].'-'.$data['created']['month'].'-'.$data['created']['day'].' '.$data['created']['hour'].':'.$data['created']['minute'].':00' : gmdate('Y-m-d H:i:s');
		}else if($mainframe->isAdmin()){ //Small Fix for Backend date
			$this->_entry->bind($data['details']);
			unset($data['details']);
		}else{
			$this->_entry->created = $data['aa'].'-'.$data['mm'].'-'.$data['jj'].' '.$data['hh'].':'.$data['mn'].':00';
		}

		//Date & Time Corrections
		if ($this->_entry->created && strlen(trim( $this->_entry->created )) <= 10) {
			$this->_entry->created 	.= ' '.date('H:i:s');
		}

		// sanitise id field
		$this->_entry->id = (int) $this->_entry->id;

		$isNew = ($this->_entry->id < 1);

		//upload display images
		if (isset( $_FILES['upload'] ) and !$_FILES['upload']['error'] )
		{
			if($image = $this->uploadImage($_FILES['upload']))
			{
				$this->_entry->image = $image;
			}
		}

		if ($isNew)
		{
			$this->_entry->created_by 	= $user->get('id');
			$sendEmail					= (BloggieFactory::allowAccess('author.can_publish')) ? 'new_entry' : 'new_pending_entry';
		}
		else
		{
			$this->_entry->modified 	= gmdate('Y-m-d H:i:s');
			$this->_entry->modified_by = $user->get('id');

			$query = 'SELECT hits, created_by, version' .
				' FROM #__bloggies_entries' .
				' WHERE id = '.(int) $this->_entry->id;

			$this->_db->setQuery($query);
			$result = $this->_db->loadObject();

			$this->_entry->hits 		= $result->hits;
			$this->_entry->created_by 	= $result->created_by;
			$this->_entry->version 		= $result->version;
			$this->_entry->version++;
		}

		// Set to pending if user is not allowed to publish
		if (!BloggieFactory::allowAccess('author.can_publish'))
		{
			$this->_entry->state = ($this->_entry->state == 0 || $this->_entry->state == 1) ? 2 : $this->_entry->state;
			$sendEmail	= (!$sendEmail) ? 'pending_entry' : $sendEmail;
		}

		// Get parameter variables from the request
		$params	= JRequest::getVar( 'params', null, 'post', 'array' );		
		
		// Build parameter INI string
		if (is_array($params))
		{
			$txt = array ();
			foreach ($params as $k => $v) {
				$txt[] = "$k=$v";
			}
			$this->_entry->attribs = implode("\n", $txt);
		}

		// Get metadata string
		$metadata = JRequest::getVar( 'meta', null, 'post', 'array');
		if (is_array($metadata))
		{
			$txt = array();
			foreach ($metadata as $k => $v)
			{
				if ($k == 'description') {
					$this->_entry->metadesc = $v;
				} elseif ($k == 'keywords') {
					$this->_entry->metakey = $v;
				} else {
					$txt[] = "$k=$v";
				}
			}
			$this->_entry->metadata = implode("\n", $txt);
		}

		// Get submitted text from the request variables
		$text = (!$xmlrpc) ? JRequest::getVar( 'text', '', 'post', 'string', JREQUEST_ALLOWRAW ) : $data['text'];

		// Correct Image URL (Relative to Absolute)
		if ($settings->get('correctImageUrl'))
		{
			//Needed for Image URL correction
			BloggieFactory::import('media', 'helpers');

			preg_match_all('/<img[^>]+>/i',$text, $result);
			if(!empty($result))
			{
				foreach( $result[0] as $img_tag )
				{
					preg_match_all('/src="([^"]*)"/i',$img_tag, $image);
					if(isset($image[1][0]))
					{
						$fixed_image = MediaHelper::toAbsolute($image[1][0]);
						$text = str_replace($image[1][0],$fixed_image,$text);
					}
				}
			}
		}

		// Search for the {readmore} tag and split the text up accordingly.
		$text = str_replace('<br>', '<br />', $text);
		$text = str_replace('<hr id="system-readmore">', '<hr id="system-readmore" />', $text);

		$pattern = ($xmlrpc) ? '#<!--more-->#i' : '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$tagPos	= preg_match($pattern, $text);

		if ($tagPos == 0)	{
			$this->_entry->introtext = $text;
		} else 	{
			list($this->_entry->introtext, $this->_entry->fulltext) = preg_split($pattern, $text, 2);
		}

		// Make sure the data is valid
		if (!$this->_entry->check()) {
			$this->setError($this->_entry->getError());
			return false;
		}

		//Do that Pinginginginginging
		if ($data['trackbacks'] != "")
		{
			//get ping class
			BloggieFactory::import('ping');

			// Bind the trackback link
			$slug 					= $this->_entry->id.':'.$this->_entry->alias;
			$archive 				= JHTML::_('date',  $this->_entry->created, '&year=%Y&month=%m&day=%d');
			$this->_entry->pinged 	= BloggiePing::doTrackback($data['trackbacks'], $this->_entry, $archive, $slug);
		}
	
		// Ping Update services
		if ($settings->get('useUpdateServices'))
		{
			//get ping class
			BloggieFactory::import('ping');

			$services	= $settings->get('updateServices');
			BloggiePing::pingUpdate($services);
		}		
		
		// Store the article table to the database
		if (!$this->_entry->store())
		{
			$this->setError($this->_db->getErrorMsg());
			$this->_entry = null;
			return false;
		}

		if ($isNew)
		{
			$this->_id = $this->_entry->_db->insertId();
		}

		// Add new Tags
		if($tagname = JRequest::getVar( 'tagname'))
		{
			$newTags 	= explode(",", $tagname);
			$newTagIDs	= array();
			unset($tagname);

			foreach($newTags as $tag)
			{
				$query = 'SELECT t.id FROM #__bloggies_tags AS t WHERE t.name = \''.$tag.'\'';
				$this->_db->setQuery($query);
				if(!$this->_db->loadResult())
				{
					$tagTbl  			=& JTable::getInstance('tags', 'Table');
					$tagData			= array();
					$tagData['name'] 	= $tag;
					$tagData['slug'] 	= JFilterOutput::stringURLSafe($tag);
					
					// bind it to the table
					if (!$tagTbl->bind($tagData)) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					// Store it in the db
					if (!$tagTbl->store()) {
						$this->setError( $this->_db->getErrorMsg() );
						return false;
					}
					$newTagIDs[] = $this->_db->insertId();
				}
			}
			if(!empty($newTagIDs))
				$tags 	= array_merge($tags, $newTagIDs);
		}

		// Set Tags
		$query = 'DELETE FROM #__bloggies_relations WHERE entry = '.$this->_entry->id;
		$this->_db->setQuery($query);
		$this->_db->query();

		foreach($tags as $tag)
		{
			if($tag)
			{
				$query = 'INSERT INTO #__bloggies_relations (`entry`, `tag`) VALUES('.$this->_entry->id.', '.$tag.')';
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}

		//Make the Slugs
		$this->_entry->slug		= $this->_entry->id.':'.$this->_entry->alias;
		$this->_entry->archive	= JHTML::_('date',  $this->_entry->created, '&year=%Y&month=%m&day=%d');

		//Send Notification
		if($sendEmail)
		{
			$mailer = BloggieFactory::getMailer();
			$mailer->setTemplate($sendEmail);
			if($mailer->emailWho())
			{
				$mailer->assign('entry', array('author'=>$user->get('name'), 'title'=>$this->_entry->title, 'url'=>(($mainframe->_clientId != 3) ? JRoute::_(JURI::base().'index.php?option=com_lyftenbloggie&view=entry'.$this->_entry->archive.'&id='.$this->_entry->slug) : JURI::base().'index.php?option=com_lyftenbloggie&view=entry'.$this->_entry->archive.'&id='.$this->_entry->slug)));
				$mailer->assign('admin', array('pending'=>(($mainframe->_clientId != 3) ? JRoute::_(JURI::base().'index.php?option=com_lyftenbloggie&view=author&mode=pending') : 'index.php?option=com_lyftenbloggie&view=author&mode=pending')));
				$mailer->sendMail(JText::_('ENTRY_'.strtoupper($sendEmail)));
			}
		}
		return true;
	}

	function uploadImage(&$file)
	{
		//Get Default Height & Width
		$settings 	= & BloggieSettings::getInstance();
		$width		= $settings->get('display_img_h', '200');
		$height		= $settings->get('display_img_w', '200');

		//Get Author Paths	
		$author_folders = BloggieFactory::getMyFolders();
		$filepath 		= $author_folders['base'].DS.'display';

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
				if($this->_entry->image && $this->_entry->image != $handle->file_dst_name)
				{
					if(file_exists($filepath.DS.$this->_entry->image)) {
						unlink($filepath.DS.$this->_entry->image);
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

	/**
	 * Method to remove
	 **/
	function delete()
	{		
		$user	= & JFactory::getUser(); 
		$admin 	= BloggieFactory::allowAccess('admin.admin_access');
		$action = array();

		if(!BloggieFactory::allowAccess('author.author_access') && !$admin) {
			$action['task'] = 'error';
			$action['msg']	= JText::_('YOU MUST BE AN AUTHOR TO VIEW THIS RESOURCE');
			return $action;
		}
		
		$where = (!$admin) ? ' e.created_by = \''.$user->get('id').'\' AND' : '';

		$query = 'SELECT e.id, e.title, e.created,'
			. ' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,'
			. ' CASE WHEN CHAR_LENGTH(c.slug) THEN c.slug ELSE 0 END as catslug'
			. ' FROM #__bloggies_entries AS e'
			. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
			. ' WHERE'.$where.' e.id = \''.$this->_id.'\'';
		$this->_db->setQuery($query);
		$entry = $this->_db->loadObject();

		//Entry Found?
		if(empty($entry)) {
			$action['task'] = 'error';
			$action['msg']	= JText::_('YOU ARE NOT THE AUTHOR OF THIS ENTRY');
			return $action;
		}	

		//Send Notification for user's who can't delete
		if(!BloggieFactory::allowAccess('author.can_delete'))
		{
			$mailer = BloggieFactory::getMailer();
			$mailer->setTemplate('pending_delete');
			if($mailer->emailWho())
			{
				$entry->archive	= (!empty($entry)) ? JHTML::_('date',  $entry->created, '&year=%Y&month=%m&day=%d') : '';
				$mailer->assign('entry', array('author'=>$user->get('name'), 'title'=>$entry->title, 'url'=>(($mainframe->_clientId != 3) ? JRoute::_(JURI::base().'index.php?option=com_lyftenbloggie&view=entry'.$entry->archive.'&id='.$entry->slug) : JURI::base().'index.php?option=com_lyftenbloggie&view=entry'.$entry->archive.'&id='.$entry->slug)));
				$mailer->assign('admin', array('pending'=>(($mainframe->_clientId != 3) ? JRoute::_(JURI::base().'index.php?option=com_lyftenbloggie&view=author&mode=pending') : JURI::base().'index.php?option=com_lyftenbloggie&view=author&mode=pending')));
				$mailer->sendMail(JText::_('ENTRY PENDING DELETION'));
			}
			
			$query = 'UPDATE #__bloggies_entries'
					. ' SET state = \'3\''
					. ' WHERE id = \''.$this->_id.'\'';
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				$action['task'] = 'error';
				$action['msg']	= JText::_('UNABLE CHANGE STATE OF ENTRY');
				return $action;
			}
			
			$action['task'] = 'change';
			$action['msg']	= JText::_('ENTRY SET PENDING DELETION');
			return $action;			
		}

		//Delete Entry
		$query = 'DELETE FROM #__bloggies_entries'
				. ' WHERE id = \''. $this->_id.'\'';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$action['task'] = 'error';
			$action['msg']	= $this->_db->getErrorMsg();
			return $action;
		}

		//Delete Entry Tag relationships
		$query = 'DELETE FROM #__bloggies_relations'
				.' WHERE entry = \''. $this->_id.'\'';
		$this->_db->setQuery($query);
		if(!$this->_db->query()) {
			$action['task'] = 'error';
			$action['msg']	= $this->_db->getErrorMsg();
			return $action;
		}

		$action['task'] = 'remove';
		return $action;
	}
	
	/**
	 * Clean up all entries for display
	 **/
	function prepareEntry()
	{
		global $mainframe;

		//initialize variables
		$settings 		= & BloggieSettings::getInstance();
		$addThisPubId 	= $settings->get('addThisPubId');
		$dispatcher		=& JDispatcher::getInstance();
		$params 		= ($mainframe->_clientId == 3 || $mainframe->isAdmin()) ? JComponentHelper::getParams('com_lyftenbloggie') : $mainframe->getParams('com_lyftenbloggie');

		// Get the page/component configuration and entry parameters
		$this->_entry->params = clone($params);
		$aparams = new JParameter($this->_entry->attribs);

		// Merge article parameters into the page configuration
		$this->_entry->params->merge($aparams);
		
		// Get Comment Counts from plugins
		$comment_system = $settings->get('typeComments', 'default');
		$this->_entry->comcount = 0;
		if($comment_system)
		{
			$plugin = BloggieFactory::getPlugin('comment', $comment_system);
			$total = $plugin->getCount($this->_entry->id);
			$this->_entry->comcount = $total['approved'];
			unset($total);
		}

		// Change all relative url to absolute url
		$this->_entry->cattitle 		= ($this->_entry->cattitle)?$this->_entry->cattitle:JText::_('UNCATEGORIZED');
		$this->_entry->catslug 			= ($this->_entry->catslug)?$this->_entry->catslug:strtolower(JText::_('UNCATEGORIZED'));
		$this->_entry->author			= BloggieFactory::getProfile($this->_entry->created_by);
		$this->_entry->author_url		= ($mainframe->_clientId != 3) ? JRoute::_( 'index.php?option=com_lyftenbloggie&author='.$this->_entry->created_by) : '';
		$this->_entry->category	 		= ($mainframe->_clientId != 3) ? EntriesHelper::getCatLinks($this->_entry->catid) : '';
		$this->_entry->tags		 		= ($mainframe->_clientId != 3) ? EntriesHelper::getTagLinks($this->_entry->id) : '';
		$this->_entry->created_nf		= $this->_entry->created;
		$this->_entry->created			= JHTML::_('date',  $this->_entry->created, $settings->get('dateFormat', '%B %d, %Y') );
		$this->_entry->bookmarks		= ($mainframe->_clientId != 3) ? EntriesHelper::getBookmarks($this->_entry, $addThisPubId) : '';
		$this->_entry->allowComments	= ($settings->get('typeComments') && $this->_entry->params->get('allow_comments'));
		$this->_entry->CommentReport	= $settings->get('allowReport');
		$this->_entry->trackback		= (!$settings->get('allowTrackbacks', 1)) ? '' : BloggieFactory::getTrackbackLink(array('slug'=>$this->_entry->slug, 'archive'=>$this->_entry->archive));

		//check if entry is editable
		$this->_entry->editable = false;
		if(BloggieFactory::canEdit($this->_entry->id, $this->_entry->created_by))
		{
			$uri = & JFactory::getURI();
			$this->_entry->editable = ($mainframe->_clientId != 3) ? JRoute::_('index.php?option=com_lyftenbloggie&view=author&mode=edit&id='. $this->_entry->slug.'&return='.base64_encode($uri->toString())) : true;
			unset($uri);
		}

		//check if entry is (un)publishable
		$this->_entry->publishable = false;
		if(BloggieFactory::canPublish($this->_entry->id, $this->_entry->created_by))
		{
			$uri = & JFactory::getURI();
			$this->_entry->publishable = ($mainframe->_clientId != 3) ? '<a href="'.JRoute::_('index.php?option=com_lyftenbloggie&task=publish&publish='. (($this->_entry->state == 1) ? 0 : 1) .'&id='. $this->_entry->slug.'&return='.base64_encode($uri->toString())).'">'.(($this->_entry->state == 1) ? JText::_('UNPUBLISH') : JText::_('PUBLISH')).'</a>' : true;
			unset($uri);
		}

		//check if entry is publishup
		$this->_entry->publishup = false;
		if($this->_entry->editable && $this->_entry->state == 1)
		{
			$jnow =& JFactory::getDate();
			if($this->_entry->created_nf >= $jnow->toMySQL()) $this->_entry->publishup = JText::sprintf('PUBLISH ON', $this->_entry->created);
		}

		//check session if uservisit already recorded
		$session 	=& JFactory::getSession();
		$hitcheck = false;
		if ($session->has('hit', 'lyftenbloggie')) {
			$hitcheck 	= $session->get('hit', 0, 'lyftenbloggie');
			$hitcheck 	= in_array($this->_entry->id, $hitcheck);
		}
		if (!$hitcheck) {
			//record hit
			$this->hit();
			$stamp = array();
			$stamp[] = $this->_entry->id;
			$session->set('hit', $stamp, 'lyftenbloggie');
		}
			
		// Setup text
		$this->_entry->text = $this->_entry->introtext.$this->_entry->fulltext;	

		// Clean up the final text
		$this->_entry->text = str_replace(array('{mosimage}', '{mospagebreak}', '{readmore}'), '', $this->_entry->text);


		/*
		 * Process the prepare content plugins
		 */
		if($mainframe->_clientId != 3)
		{
			JPluginHelper::importPlugin('content');
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
			$results = $dispatcher->trigger('onPrepareContent', array (& $this->_entry, & $this->_entry->params, $limitstart));

			// Handle display events
			$this->_entry->event = new stdClass();
			$results = $dispatcher->trigger('onAfterDisplayTitle', array ($this->_entry, &$this->_entry->params, $limitstart));
			$this->_entry->event->afterDisplayTitle = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $this->_entry, & $this->_entry->params, $limitstart));
			$this->_entry->event->beforeDisplayContent = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onAfterDisplayContent', array (& $this->_entry, & $this->_entry->params, $limitstart));
			$this->_entry->event->afterDisplayContent = trim(implode("\n", $results));
		}
	}

	/**
	 * Method to increment the hit counter for the entry
	 **/
	function hit()
	{
		if ($this->_id)
		{
			$entry = & JTable::getInstance('entries', 'Table');
			$entry->hit($this->_id);
			return true;
		}
		return false;
	}

	/**
	 * Method to get the total
	 **/
	function getCreated()
	{
		$month[] = JHTML::_('select.option',  '01', JText::_( 'JANUARY' ) );
		$month[] = JHTML::_('select.option',  '02', JText::_( 'FEBRUARY' ) );
		$month[] = JHTML::_('select.option',  '03', JText::_( 'MARCH' ) );
		$month[] = JHTML::_('select.option',  '04', JText::_( 'APRIL' ) );
		$month[] = JHTML::_('select.option',  '05', JText::_( 'MAY' ) );
		$month[] = JHTML::_('select.option',  '06', JText::_( 'JUNE' ) );
		$month[] = JHTML::_('select.option',  '07', JText::_( 'JULY' ) );
		$month[] = JHTML::_('select.option',  '08', JText::_( 'AUGUST' ) );
		$month[] = JHTML::_('select.option',  '09', JText::_( 'SEPTEMBER' ) );
		$month[] = JHTML::_('select.option',  '10', JText::_( 'OCTOBER' ) );
		$month[] = JHTML::_('select.option',  '11', JText::_( 'NOVEMBER' ) );
		$month[] = JHTML::_('select.option',  '12', JText::_( 'DECEMBER' ) );
		$month 	 = JHTML::_('select.genericlist', $month, 'mm', 'class="inputbox" size="1"', 'value', 'text', date('m', strtotime($this->_entry->created_nf)) );

		$return =  $month.'<input id="jj" name="jj" value="'.date('d', strtotime($this->_entry->created_nf)).'" size="2" maxlength="2" tabindex="4" type="text">, <input id="aa" name="aa" value="'.date('Y', strtotime($this->_entry->created_nf)).'" size="4" maxlength="5" tabindex="4" type="text"> @ <input id="hh" name="hh" value="'.date('H', strtotime($this->_entry->created_nf)).'" size="2" maxlength="2" tabindex="4" autocomplete="off" type="text"> : <input id="mn" name="mn" value="'.date('i', strtotime($this->_entry->created_nf)).'" size="2" maxlength="2" tabindex="4" type="text">';
	
		return $return;
	}
}
?>