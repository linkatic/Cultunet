<?php
/**
 * JomComment Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class jomcommentCommentPlugin extends BloggiePlugin
{
	/**
	 * Constructor
	 */
	function jomcommentCommentPlugin()
	{
		parent::__construct();
	}

	//display comments
	function display($id, $title)
	{
		$output = '';
		if (file_exists(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php'))
		{
			require_once(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php');
			$output = '<a name="comment"></a>';
			$output .= jomcomment($id, 'com_lyftenbloggie');
		}
		return $output;
	}

	//Gets the Entry's Comment Count
	function getCount($id)
	{
		$output = array();

		//Get Total
		$query = "SELECT COUNT(*) FROM #__jomcomment WHERE `contentid`='{$id}' AND (`option`='com_lyftenbloggie')";
		$this->_db->setQuery( $query );
		$output['total'] = $this->_db->loadResult();

		//Get Approved
		$query = "SELECT COUNT(*) FROM #__jomcomment WHERE `contentid`='{$id}' AND (`option`='com_lyftenbloggie') AND `published`='1'";
		$this->_db->setQuery( $query );
		$output['approved'] = $this->_db->loadResult();

		//Get Flagged
		$query = "SELECT COUNT(*) FROM #__jomcomment WHERE `contentid`='{$id}' AND (`option`='com_lyftenbloggie') AND `published`='0'";
		$this->_db->setQuery( $query );
		$output['moderated'] = $this->_db->loadResult();

		$output['spam'] 	= '';
		return $output;
	}

	//Gets the Entry's Comments
	function getComments($args = array())
	{
		$where = array();

		if ($args['status'])
		{
			if ( 'hold' == $args['status'] )
				$args['status'] = 0;
			else if ( 'approve' == $args['status'] )
				$args['status'] = 1;
			else if ( 'flagged' == $args['status'] )
				$args['status'] = 0;
			else if ( 'spam' == $args['status'] )
				$args['status'] = 0;

			$where[] = 'c.published = \''.$args['status'].'\'';
		}

		if ($args['post_id'])
		{
			$where[] = 'c.contentid = \''.$args['post_id'].'\'';
		}

		$where[] = 'c.option = \'com_lyftenbloggie\'';

		$where 	= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$query = 'SELECT c.id FROM #__jomcomment AS c'.$where;
		$comments = $this->_getList($query, $args['offset'], $args['number']);

		return $comments;
	}

	//Gets the Entry's Comment
	function getComment($id)
	{
		$query = 'SELECT c.id, c.date, c.published, c.contentid AS entry_id, c.comment AS content,'
				. ' e.title AS entry_title, c.name AS author_name, c.website AS author_url,'
				. ' c.email AS author_email, c.ip AS author_ip, c.referer AS link, c.parentid AS parent,'
				. ' CASE WHEN CHAR_LENGTH(u.email) THEN u.id ELSE \'0\' END as user_id'
				. ' FROM #__jomcomment AS c'
				. ' LEFT JOIN #__bloggies_entries AS e ON e.id = c.contentid'
				. ' LEFT JOIN #__users AS u ON u.email = c.email'
				. ' WHERE c.option = \'com_lyftenbloggie\''
				. ' AND c.id = \''.$id.'\'';
		$this->_db->setQuery( $query );
		$comment = $this->_db->loadObject();
		
		if ( isset($comment->id) )
		{
			$comment->type = JText::_( 'COMMENT' );
			if ( 0 == $comment->published )
				$comment->state = 'hold';
			else if ( 1 == $comment->published )
				$comment->state = 'approve';
			else
				$comment->state = $comment->published;
		}

		return $comment;
	}

	//Delete an Entry's Comment
	function deleteComment($id)
	{
		//Remove Comment
		$query = 'DELETE FROM #__jomcomment WHERE `id` = \''.$id.'\' AND `option` = \'com_lyftenbloggie\'';
		$this->_db->setQuery($query);
		if(!$this->_db->query())
		{
			return JText::_('UNABLE TO DELETE COMMENT');
		}

		//Remove Comment Reported
		$query = 'DELETE FROM #__jomcomment_reported WHERE `commentid` = \''.$id.'\'';
		$this->_db->setQuery($query);
		$this->_db->query();

		//Remove Comment Reports
		$query = 'DELETE FROM #__jomcomment_reports WHERE `commentid` = \''.$id.'\'';
		$this->_db->setQuery($query);
		$this->_db->query();

		//Remove Comment Votes
		$query = 'DELETE FROM #__jomcomment_votes WHERE `commentid` = \''.$id.'\'';
		$this->_db->setQuery($query);
		$this->_db->query();

		return false;
	}

	//Edit an Entry's Comment
	function editComment($data)
	{
		if(!is_array($data) || !@$data['id'])
			return JText::_('AN ERROR HAS OCCURRED');

		$sets = array();
		$sets[] = 'c.date = \''.$data['date'].'\'';

		if ($data['content'])
		{
			$sets[] = 'c.comment = \''.$data['content'].'\'';
		}else{
			return JText::_('PLEASE ENTER A COMMENT');
		}

		if ($data['status'])
		{
			if ( 'hold' == $data['status'] )
				$data['status'] = 0;
			else if ( 'approve' == $data['status'] )
				$data['status'] = 1;
			else if ( 'flagged' == $data['status'] )
				$data['status'] = 0;
			else if ( 'spam' == $data['status'] )
				$data['status'] = 0;

			$sets[] = 'c.published = \''.$data['status'].'\'';
		}

		if ($data['author'])
		{
			$sets[] = 'c.name = \''.$data['author'].'\'';
		}else{
			return JText::_('COMMENT MUST HAVE AN AUTHOR');
		}

		if (isset($data['author_url']))
			$sets[] = 'c.website = \''.$data['author_url'].'\'';

		if (isset($data['author_email']))
			$sets[] = 'c.email = \''.$data['author_email'].'\'';

		$sets 	= ( count( $sets ) ? ' SET ' . implode( ', ', $sets ) : '' );
		$query = 'UPDATE #__jomcomment AS c'
			. $sets
			. ' WHERE c.id = \''.$data['id'].'\'';
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			return JText::_('AN ERROR HAS OCCURRED');
		}

		return false;
	}

	//Create a new Entry Comment
	function newComment($data)
	{
		global $mainframe;

		if(!is_array($data) || !@$data['entry_id'])
			return JText::_('AN ERROR HAS OCCURRED');

		$datenow 			= & JFactory::getDate();
		$data['date'] 		= $datenow->toMySQL();
		$data['status'] 	= 1;
		$data['referer'] 	= ($mainframe->_clientId != 3) ? JRoute::_($this->_siteUrl.'index.php?option=com_lyftenbloggie&view=entryid='.$data['entry_id'], false) : $this->_siteUrl.'index.php?option=com_lyftenbloggie&view=entryid='.$data['entry_id'];

		$query = "INSERT IGNORE INTO `#__jomcomment`"
				. " (`contentid`,`name`,`comment`,`date`,`email`,`website`,`published`,`user_id`,`option`,`referer`) VALUES"
				. " ('".$data['entry_id']."', '".$data['author']."', '".$data['content']."', '".$data['date']."',"
				. " '".$data['author_email']."', '".$data['author_url']."', '".$data['status']."',"
				. " '".$data['author_id']."', 'com_lyftenbloggie', '".$data['referer']."' )";
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			return JText::_('AN ERROR HAS OCCURRED');
		}

		return false;
	}
}