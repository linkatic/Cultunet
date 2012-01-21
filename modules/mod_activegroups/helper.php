<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' . DS . 'string.php' );

class modActiveGroupsHelper
{
	function getGroupsData( &$params )
	{
		$model	=& CFactory::getModel( 'groups' );
		
		$db =& JFactory::getDBO();

		$count 		= $params->get('count', '5');
		$sql = " SELECT
						".$db->nameQuote('cid').",
						COUNT(".$db->nameQuote('cid').") AS ".$db->nameQuote('count')." 
				   FROM
						".$db->nameQuote('#__community_activities')." a 
			 INNER JOIN	".$db->nameQuote('#__community_groups')." b ON a.".$db->nameQuote('cid')." = b.".$db->nameQuote('id')." 
				  WHERE
						a.".$db->nameQuote('app')." = ".$db->quote('groups')." AND
						b.".$db->nameQuote('published')." = ".$db->quote('1')." AND
						a.".$db->nameQuote('archived')." = ".$db->quote('0')." AND
						a.".$db->nameQuote('cid')." != ".$db->quote('0')."
			   GROUP BY a.".$db->nameQuote('cid')."
			   ORDER BY ".$db->nameQuote('count')." DESC
			   LIMIT ".$count;

		$query = $db->setQuery($sql);
		$row = $db->loadObjectList();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
	    }

		$_groups     = array();

		if ( !empty( $row ) ) {
			foreach ( $row as $data )
			{

				$group = $model->getGroup($data->cid);
				if ( $group->id )
				{ 
				    $groupAvatar=& JTable::getInstance( 'group', 'CTable' );
				    $groupAvatar->load($group->id);
				    
					$_obj		= new stdClass();
				    $_obj->id    		= $group->id;
                    $_obj->name      	= $group->name;
					$_obj->avatar    	= $groupAvatar->getThumbAvatar(); 
					$_obj->totalMembers	= $model->getMembersCount($group->id);
					
					$_groups[]	= $_obj;
				}
			}
		}
		return $_groups;
	}
}