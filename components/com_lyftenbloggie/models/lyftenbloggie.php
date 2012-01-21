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

class LyftenBloggieModelLyftenBloggie extends JModel
{
	var $_data 			= null;
	var $_total 		= null;
	var $_searchby		= null;
	var $_addThisPubId	= null;
	var $_imageLocation	= null;
	var $_tag			= null;
	var $_category		= null;
	var $_author		= null;

	/**
	 * Constructor
	 **/
	function __construct()
	{
		parent::__construct();

		//set data
		$this->_tag			= JRequest::getVar( 'tag' );
		$this->_category	= JRequest::getVar( 'category' );
		$this->_author    	= JRequest::getInt( 'author' );

		if ($this->_category != '') {
				$category = strval(urldecode($this->_category));
				$category = str_replace("+", " ", $category);
				$this->_searchby['category'] = $category;
		}

		//Get Year/Month/Day
		$year 		= JRequest::getInt('year');
		$month 		= JRequest::getInt('month');
		if ($year && $month) {
			$this->_searchby['archive']['year']		= $year;
			$this->_searchby['archive']['month']	= $month;
			$this->_searchby['archive']['day']		= JRequest::getInt('day');
		}
	}

	/**
	 * Method to get Data
	 **/
	function getData()
	{
		// Lets load the categories if it doesn't already exist
		if (empty($this->_data))
		{
			// Get the pagination request variables
			$limit		= JRequest::getVar('limit', 0, '', 'int');
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

			$this->_data = $this->_getList( $this->_buildQuery(), $limitstart, $limit );
			
			for( $i=0; $i < count($this->_data); $i++ )
			{
				$this->_data[$i] = $this->_prepareEntry($this->_data[$i]);
			}
		}

		return $this->_data;
	}

	/**
	 * Method to build the Categories query
	 **/
	function _buildQuery()
	{
		// Get the WHERE, and ORDER BY clauses for the query
		$tags				= $this->_buildEntriesTags();
		$where				= $this->_buildEntriesWhere();

		$query = 'SELECT e.*, '
				.' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,'
				.' CASE WHEN CHAR_LENGTH(cat.slug) THEN cat.slug ELSE 0 END as catslug'
				.' FROM #__bloggies_entries AS e'
				.' LEFT JOIN #__bloggies_categories AS cat ON cat.id = e.catid'
				.$tags
				.$where
				.' GROUP BY e.id'	
				.' ORDER BY e.created DESC'
				;
		return $query;
	}

	/**
	 * Method to build the where clause of the query
	 **/
	function _buildEntriesTags()
	{
		return ($this->_tag)?' LEFT JOIN #__bloggies_tags AS t ON t.slug = '.$this->_db->Quote( $this->_db->getEscaped( $this->_tag, true ), false ).' LEFT JOIN #__bloggies_relations AS r ON r.tag = t.id':'';
	}

	/**
	 * Method to build the where clause of the query
	 **/
	function _buildEntriesWhere()
	{
		global $mainframe, $option;

		$user		= & JFactory::getUser();
		$gid 		= (!$user->get('gid')) ? 1 : $user->get('gid');
		$authorid 	= isset($this->_searchby['authorid']) 	 ? $this->_db->getEscaped($this->_searchby['authorid']): "";
		$search 	= isset($this->_searchby['search']) 	 ? $this->_db->getEscaped($this->_searchby['search']): "";
		$archive 	= isset($this->_searchby['archive']) 	 ? $this->_searchby['archive']: "";
		$nullDate	= $this->_db->getNullDate();
		$jnow		=& JFactory::getDate();
		$now		= $jnow->toMySQL();

		//Set category
		if(isset($this->_searchby['category']) && $this->_searchby['category'] && ($this->_searchby['category'] != strtolower(JText::_('UNCATEGORIZED'))) ) 
		{
			$category 	= $this->_db->getEscaped($this->_searchby['category']);
			$where[] = 'e.catid = cat.id';
			$where[] = 'cat.slug = \''.$category.'\'';
		}elseIf(isset($this->_searchby['category']) && $this->_searchby['category'] == strtolower(JText::_('UNCATEGORIZED'))){
			$category 	= strtolower(JText::_('UNCATEGORIZED'));
			$where[] 	= 'e.catid = 0';
		}

		//Allow Easy Controls
		$access = BloggieAccess::getInstance();
		if(!$access->get('author.can_publish') && !$access->get('admin.admin_access'))
		{
			$where[] = "(e.state=1 OR e.created_by = '".$user->get('id')."')";
		}
		if(!$access->get('author.author_access') && !$access->get('admin.admin_access'))
		{
			$where[] = "( e.created = ".$this->_db->Quote($nullDate)." OR e.created <= ".$this->_db->Quote($now)." )";
		}
		unset($access);

		$where[] = "(e.access <= '".$gid."' OR e.created_by = '".$user->get('id')."')";

		if (!empty ($authorid) or $authorid == "0") {
			$where[] = 'e.created_by IN (' . $authorid . ')';
		}

		if (!empty ($search)) {
			$where[] = 'match (e.title,e.fulltext,e.introtext) against (\'' . $search . '\' in BOOLEAN MODE)\'';
		}

		if ($this->_tag) {
			$where[] = 'r.entry = e.id';
		}

		if ($this->_author) {
			$where[] = 'e.created_by = '.$this->_author;
		}

		if (!empty ($archive)) {
			if($archive['day']){
				$arDate = date("Y-m-d", strtotime($archive['year'].'-'.$archive['month'].'-'.$archive['day']));
				$where[] = 'DATE( e.created ) = ' . $this->_db->Quote( $arDate );
			}else{
				$where[] 	= 'e.created BETWEEN ' . $this->_db->Quote(date("Ym", strtotime($archive['year'].'-'.$archive['month'])).'00000000').' AND '.$this->_db->Quote(date("Ym", strtotime($archive['year'].'-'.$archive['month'].'+1 month')).'00000000');
			}
		}
		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	/**
	 * Total nr of Categories
	 **/
	function getTotal()
	{
		// Lets load the total nr if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Get the feed data
	 **/
	function getFeed()
	{
		global $mainframe;

		$limit 	= $mainframe->getCfg('feed_limit');

		$query = 'SELECT DISTINCT e.*, c.title AS cattitle, COUNT(com.entry_id) AS comcount, u.name as author, c.slug as catslug,'
			. ' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(\':\', e.id, e.alias) ELSE e.id END as slug'
			. ' FROM #__bloggies_entries AS e'
			. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
			. ' LEFT JOIN #__users AS u ON u.id = e.created_by'
			. ' LEFT JOIN #__bloggies_comments AS com ON com.entry_id = e.id'
			. ' WHERE e.state=1'
			. ' GROUP BY e.created'	
			. ' ORDER BY e.created DESC'
			. ' LIMIT '. (int)$limit
			;

		$this->_db->setQuery($query);
		$feed = $this->_db->loadObjectList();

		return $feed;
	}

	/**
	 * Get the feed data
	 **/
	function getCategoryInfo()
	{
		if(!$this->_category) return;

		if (strtolower($this->_category) == strtolower(JText::_('UNCATEGORIZED'))) return JText::_('UNCATEGORIZED');

		$query = 'SELECT title'
		. ' FROM #__bloggies_categories'
		. ' WHERE slug = '.$this->_db->Quote( $this->_db->getEscaped( $this->_category, true ), false )
		;
		$this->_db->setQuery($query);
		$category = $this->_db->loadResult();

		return $category;
	}

	/**
	 * Get the feed data
	 **/
	function getTagInfo()
	{
		if(!$this->_tag) return;

		$query = 'SELECT name'
			. ' FROM #__bloggies_tags'
			. ' WHERE slug = '.$this->_db->Quote( $this->_db->getEscaped( $this->_tag, true ), false )
			;
		$this->_db->setQuery($query);
		$tag = $this->_db->loadResult();
		return $tag;
	}

	/**
	 * Clean up all entries for tge display
	 **/
	function _prepareEntry($row)
	{
		global $mainframe;

		//initialize variables
		$settings 		= & BloggieSettings::getInstance();
		$addThisPubId 	= $settings->get('addThisPubId');
		$params 		= $mainframe->getParams('com_lyftenbloggie');
		$dispatcher		= & JDispatcher::getInstance();
		$theme 			= BloggieTemplate::getInstance();
		$image 			= $theme->get('left_object', 'image');
		$image_width	= ($image_width = $theme->get('leftobj_width')) ? 'width:'.$image_width.';' : $image_width;
		$image_height	= ($image_height = $theme->get('leftobj_height')) ? 'height:'.$image_height.';' : $image_height;

		//Small Tweaks
		$row->text 	= $row->introtext;
		$created 	= $row->created;

		// Get the page/component configuration and entry parameters
		$row->params = clone($params);
		$aparams = new JParameter($row->attribs);

		// Merge article parameters into the page configuration
		$row->params->merge($aparams);

		// Get Comment Counts from plugins
		$comment_system = $settings->get('typeComments', 'default');
		$row->comcount = 0;
		if($comment_system)
		{
			$plugin = BloggieFactory::getPlugin('comment', $comment_system);
			$total = $plugin->getCount($row->id);
			$row->comcount = $total['approved'];
			unset($total);
		}

		// Fix it ALL!
		$created_by			= &JFactory::getUser($row->created_by);
		$archive 			= JHTML::_('date',  $created, '&year=%Y&month=%m&day=%d');

		$row->author		= $created_by->get('name');
		$row->author_url	= JRoute::_( 'index.php?option=com_community&view=profile&userid='.$row->created_by);
		$row->category	 	= EntriesHelper::getCatLinks($row->catid, true);
		$row->tags		 	= EntriesHelper::getTagLinks($row->id, true);
		$row->created_m		= JHTML::_('date',  $created, '%b');
		$row->created_d		= JHTML::_('date',  $created, '%d');
		$row->created		= JHTML::_('date',  $created, $settings->get('dateFormat', '%B %d, %Y'));
		$row->archive		= ($this->_category) ? '&category='.$this->_category : $archive;
		$row->trackback		= (!$settings->get('allowTrackbacks', 1)) ? '' : BloggieFactory::getTrackbackLink(array('slug'=>$row->slug, 'archive'=>$archive));

		$row->bookmarks		= EntriesHelper::getBookmarks($row, $addThisPubId);
		$row->allowComments	= ($comment_system && $row->params->get('allow_comments'));
		$row->readmore_link	= JRoute::_( 'index.php?option=com_lyftenbloggie&view=entry'.$row->archive.'&id='. $row->slug );

		//check if entry is editable
		$row->editable = false;
		if(BloggieFactory::canEdit($row->id, $row->created_by))
		{
			$uri = & JFactory::getURI();
			$row->editable = JRoute::_('index.php?option=com_lyftenbloggie&view=author&mode=edit&id='. $row->slug.'&return='.base64_encode($uri->toString()));
			unset($uri);
		}

		//check if entry is (un)publishable
		$row->publishable = false;
		if(BloggieFactory::canPublish($row->id, $row->created_by))
		{
			$uri = & JFactory::getURI();
			$row->publishable = '<a href="'.JRoute::_('index.php?option=com_lyftenbloggie&task=publish&publish='. (($row->state == 1) ? '-1' : '1') .'&id='. $row->slug.'&return='.base64_encode($uri->toString())).'">'.(($row->state == 1) ? JText::_('UNPUBLISH') : JText::_('PUBLISH')).'</a>';
			unset($uri);
		}

		//check if entry is publishup
		$row->publishup = false;
		if($row->editable && $row->state == 1)
		{
			$jnow =& JFactory::getDate();
			if($created >= $jnow->toMySQL()) $row->publishup = JText::sprintf('PUBLISH ON', $row->created);
		}
		unset($created);

		// Process the content preparation plugins
		JPluginHelper::importPlugin('content');
		$results = $dispatcher->trigger('onPrepareContent', array (& $row, & $row->params, 0));

		// Add readmore link if necessary
		$row->readmore	= '1';
		if($settings->get('necessaryReadmore') == '1')
		{
			if($row->introtext && empty($row->fulltext) )
			{
				$position = -1;
				$count	  = 0;
				while( ( $position = strpos($row->introtext , '</p>' , $position + 1) ) !== false )
				{
					$count++;
				}

				if( $count <= $settings->get('autoReadmorePCount') )
				{
					$row->readmore = '0';
				}
			}
			else
			{
				$position = -1;
				$count	  = 0;
				while( ( $position = strpos($row->fulltext , '</p>' , $position + 1) ) !== false )
				{
					$count++;
				}	
				if( $count <= $settings->get('autoReadmorePCount') )
				{
					$row->readmore = '0';
				}
			}
		}
	
		if($settings->get('useIntrotext')){
			if(empty($row->fulltext))
			{
				$ending = strpos($row->introtext, '</p>');
				$pos=-1;
				$pos_array = array();
				while (($pos=strpos($row->introtext,'</p>',$pos+1))!==false) 
					$pos_array[]=$pos;
	
				$pNum = $settings->get('autoReadmorePCount');
				if (count($pos_array) <= $pNum) {
				   $row->text 		= $row->introtext;
				} else {
					$ending 		= $pos_array[$pNum-1];
					$row->introtext = substr($row->introtext, 0, $ending + 4);
					$row->introtext = EntriesHelper::cleanHTML(preg_replace('#\s*<[^>]+>?\s*$#','',$row->introtext));
				}
			}
			else if( !empty($row->fulltext) && empty($row->introtext) )
			{
				// Strip x paragraphs
				$ending = strpos($row->fulltext, '</p>');
	
				$pos=-1;
				$pos_array = array();
				while (($pos=strpos($row->fulltext,'</p>',$pos+1))!==false) 
				$pos_array[]=$pos;
	
				$pNum = $settings->get('autoReadmorePCount');
				if (count($pos_array) <= $pNum) {
					$row->text 		= $row->fulltext;
				} else {
					$ending 		= $pos_array[$pNum-1];
					$row->fulltext 	= substr($row->fulltext, 0, $ending + 4);
					$row->fulltext 	= EntriesHelper::cleanHTML(preg_replace('#\s*<[^>]+>?\s*$#','',$row->fulltext));
				}
			}

			$row->text =( empty($row->introtext) )?$row->fulltext:$row->introtext;

		} else{

			$row->text = (empty($row->introtext))?$row->fulltext:$row->introtext;
		}

		// Clean up the final text
		unset($row->fulltext);
		$row->text 	= str_replace(array('{mosimage}', '{mospagebreak}', '{readmore}'), '', $row->text);

		//Add bookmarks badge
		if(isset($row->bookmarks['badge'])){
			$isPara = substr($row->text,0,3);
			if(strtolower($isPara) == "<p>") {
				$row->text = substr($row->text, 3);
			}

			$row->text = '<p><span class="bookmarkbadge">'.$row->bookmarks['badge'].'</span>'.$row->text;
		}

		//Get Main Image
		if($settings->get('stripObjects', 0))
		{
			$row->text = preg_replace('/<img[^>]+\>/i', '', $row->text);
			$row->text = preg_replace('/<object[0-9 a-z_?*=\":\-\/\.#\,<>\\n\\r\\t]+<\/object>/smi', '', $row->text);
		}
		
		//Mod by Vicente Gimeno (vgimeno@linkatic.com)
		//Limpiamos el html en las entradas del listado del blog
		$row->text = strip_tags($row->text, '<p><ul><li><b>');

		//Get Display Image
		if($image == 'image')
		{
			//Check for Default Image
			$img = BLOGGIE_SITE_URL.'/addons/themes/system/images/default_entry.png';
			if(file_exists($theme->_template_path.DS.'images'.DS.'default_entry.png'))
			{
				$img = $theme->_template_url.'/images/default_entry.png';
			}

			//Entry Has Image
			if($row->image)
			{
				if(file_exists(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$row->created_by.DS.'display'.DS.$row->image))
				{
					$img = JURI::root().'images/lyftenbloggie/'.$row->created_by.'/display/'.$row->image;
				}else if($row->modified_by && file_exists(JPATH_ROOT.DS.'images'.DS.'lyftenbloggie'.DS.$row->modified_by.DS.'display'.DS.$row->image)){
					$img = JURI::root().'images/lyftenbloggie/'.$row->modified_by.'/display/'.$row->image;
				}
			}

			$row->mainImage = '<img src="'.$img.'" alt="'.$row->title.'" style="'.$image_width.$image_height.'" class="left_obj">';

		}else if($image == 'avatar') {
			$avatar = BloggieFactory::getAvatar($row->created_by);
			$row->mainImage = '<img src="'.$avatar.'" alt="'.$row->author.'" style="'.$image_width.$image_height.'" class="left_obj">';
			unset($avatar);
		}

		// Handle display events
		$row->event = new stdClass();
		$results = $dispatcher->trigger('onAfterDisplayTitle', array ($row, &$params, $this->getState('limitstart')));
		$row->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onBeforeDisplayContent', array (& $row, & $params, $this->getState('limitstart')));
		$row->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onAfterDisplayContent', array (& $row, & $params, $this->getState('limitstart')));
		$row->event->afterDisplayContent = trim(implode("\n", $results));
		
		return $row;
	}
}
?>