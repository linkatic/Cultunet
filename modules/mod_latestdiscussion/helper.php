<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

if(!class_exists('modLatestDiscussionHelper'))
{
	class modLatestDiscussionHelper
	{
		var $db;
		var $count;
		
		function modLatestDiscussionHelper( & $params )
		{
			$this->db		=& JFactory::getDBO();
			$this->count	= $params->get( 'count' , '5' );
		}
		
		function getLatestDiscussion($showPrivateDiscussion = TRUE)
		{				
			$privacyCondition = ' ';
			if(empty($showPrivateDiscussion))
			{
				$privacyCondition = ' AND g.approvals = ' . $this->db->quote('0') . ' ';
			}
			
			$sql		= 'SELECT a. * , count( b.id ) AS counter, c.name AS username '
						. 'FROM ' . $this->db->nameQuote( '#__community_groups_discuss' ) . ' AS a '
						. 'INNER JOIN ' . $this->db->nameQuote( '#__users' ) . ' AS c ON a.creator = c.id '
						. 'LEFT JOIN ' . $this->db->nameQuote( '#__community_wall' ) . ' AS b ON b.contentid = a.id '
						. 'AND b.type = ' . $this->db->Quote('discussions') . ' '
						. 'AND a.parentid = ' . $this->db->Quote('0'). ' '
						. 'INNER JOIN ' . $this->db->nameQuote('#__community_groups') . 'AS g ON g.id = a.groupid '
						. 'WHERE g.published = ' . $this->db->quote('1') . ' '
						. $privacyCondition
						. 'GROUP BY a.id '
						. 'ORDER BY a.created DESC '
						. 'LIMIT ' . $this->count;
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObjectList();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }

		    return $row;
		}
		
		function getGroupName($id)
		{
			$sql ='SELECT name '
				. 'FROM ' . $this->db->nameQuote( '#__community_groups' ) . ' '
				. 'WHERE ' . $this->db->nameQuote( 'id' ) . ' = ' . $this->db->quote( $id );
			
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    return $row->name;
		}			
	}
}