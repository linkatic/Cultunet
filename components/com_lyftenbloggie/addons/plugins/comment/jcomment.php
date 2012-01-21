<?php
/**
 * JComment Plugin
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

class jcommentCommentPlugin extends BloggiePlugin
{
	/**
	 * Constructor
	 */
	function jcommentCommentPlugin()
	{
		parent::__construct();
	}

	//display comments
	function display($id, $title)
	{
		$output = '';
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php'))
		{
			require_once(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php');
			$output = '<a name="comment"></a>';
			$output .= JComments::show($id, 'com_lyftenbloggie', htmlspecialchars($title));
		}
		return $output;
	}

	//Gets the Entry's Comment Count
	function getCount($id)
	{
		$output = array();

		//Get Total
		$query = "SELECT COUNT(*) FROM #__jcomments WHERE `object_id`='{$id}' AND `object_group`='com_lyftenbloggie'";
		$this->_db->setQuery( $query );
		$output['total'] = $this->_db->loadResult();

		//Get Approved
		$query = "SELECT COUNT(*) FROM #__jcomments WHERE `object_id`='{$id}' AND `object_group`='com_lyftenbloggie' AND `published`='1'";
		$this->_db->setQuery( $query );
		$output['approved'] = $this->_db->loadResult();

		//Get Flagged
		$query = "SELECT COUNT(*) FROM #__jcomments WHERE `object_id`='{$id}' AND `object_group`='com_lyftenbloggie' AND `published`='0'";
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
			$where[] = 'c.object_id = \''.$args['post_id'].'\'';
		}

		$where[] = 'c.object_group = \'com_lyftenbloggie\'';

		$where 	= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		$query = 'SELECT c.id FROM #__jcomments AS c'.$where;
		$comments = $this->_getList($query, $args['offset'], $args['number']);

		return $comments;
	}

	//Gets the Entry's Comment
	function getComment($id)
	{
		$query = 'SELECT c.id, c.date, c.published, c.parent, c.object_id AS entry_id, c.comment AS content,'
				. ' e.title AS entry_title, c.name AS author_name, c.homepage AS author_url,'
				. ' c.email AS author_email, c.ip AS author_ip,'
				. ' CASE WHEN CHAR_LENGTH(c.userid) THEN c.userid ELSE \'0\' END as user_id'
				. ' FROM #__jcomments AS c'
				. ' LEFT JOIN #__bloggies_entries AS e ON e.id = c.object_id'
				. ' WHERE c.object_group = \'com_lyftenbloggie\''
				. ' AND c.id = \''.$id.'\'';
		$this->_db->setQuery( $query );
		$comment = $this->_db->loadObject();

		if ( isset($comment->id) )
		{
			require_once(JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php');

			$comment->link = trim($this->_siteUrl, '/').JCommentsObjectHelper::getLink($comment->entry_id, 'com_lyftenbloggie');
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
		$query = 'DELETE FROM #__jcomments WHERE `id` = \''.$id.'\' AND `object_group` = \'com_lyftenbloggie\'';
		$this->_db->setQuery($query);
		if(!$this->_db->query())
		{
			return JText::_('UNABLE TO DELETE COMMENT');
		}

		//Remove Comment Votes
		$query = 'DELETE FROM #__jcomments_votes WHERE `commentid` = \''.$id.'\'';
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
			$sets[] = 'c.homepage = \''.$data['author_url'].'\'';

		if (isset($data['author_email']))
			$sets[] = 'c.email = \''.$data['author_email'].'\'';

		$sets 	= ( count( $sets ) ? ' SET ' . implode( ', ', $sets ) : '' );
		$query = 'UPDATE #__jcomments AS c'
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

		$query = "INSERT IGNORE INTO `#__jcomments`"
				. " (`object_id`,`object_group`,`userid`,`name`,`email`,`homepage`,`comment`,`date`,`published`) VALUES"
				. " ('".$data['entry_id']."', 'com_lyftenbloggie', '".$data['author_id']."', '".$data['author']."', '".$data['author_email']."',"
				. " '".$data['author_url']."', '".$data['content']."', '".$data['date']."', '".$data['status']."' )";
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			return JText::_('AN ERROR HAS OCCURRED');
		}

		return false;
	}
}