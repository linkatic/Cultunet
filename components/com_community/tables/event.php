<?php
/**
 * @category	Tables
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

class CTableEvent extends JTable
implements  CGeolocationInterface
{

	var $id 			= null;
	var $catid			= null;
	var $contentid 		= null;
	var $type 			= null;
	var $title 			= null;
	var $description	= null;
	var $location		= null;
	var $creator 		= null;
	var $startdate 		= null;
	var $enddate 		= null;
  	var $permission		= null;
  	var $avatar			= null;
  	var $thumb			= null;
  	var $invitedcount	= null;
  	var $confirmedcount	= null;
  	var $declinedcount	= null;
  	var $maybecount		= null;
  	var $created		= null;
  	var $hits			= null;
  	var $published		= null;
  	var $wallcount		= null;
  	var $ticket			= null;
  	var $allowinvite	= null;
  	
  	/* Implement geolocation */
  	var $latitude	= null;
  	var $longitude	= null;

	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__community_events', 'id', $db );
	}

	/**
	 * Binds an array into this object's property
	 *
	 * @access	public
	 * @param	$data	mixed	An associative array or object
	 **/
	public function bind($data)
	{
		return parent::bind($data);
	}
	
	public function check()
	{
		// Santinise data
		$safeHtmlFilter		= CFactory::getInputFilter();
		$this->title		= $safeHtmlFilter->clean($this->title);
		
		// Allow html tags
		$config = CFactory::getConfig();
		$safeHtmlFilter		= CFactory::getInputFilter( $config->get('allowhtml') );
		$this->description 	= $safeHtmlFilter->clean($this->description);
		
		return true;
	}
	
	public function store()
	{
		if (!$this->check()) {
			return false;
		}
		
		$this->resolveLocation($this->location);
		return parent::store();
	}
	
	/**
	 * Make sure hits are user and session sensitive
	 */	 	
	public function hit()
	{
		$session = JFactory::getSession();
		if( $session->get('view-event-'. $this->id, false) == false ) {
			parent::hit();
		}
		$session->set('view-event-'. $this->id, true);
	}
	
	public function getStartTime()
	{
		$edate  = new JDate($this->startdate);
		return $edate->toFormat('%H:%M');
	}
	
	public function getEndTime()
	{
		$edate  = new JDate($this->enddate);
		return $edate->toFormat('%H:%M');
	}
	
	public function getStartDate()
	{
		$edate  = new JDate($this->startdate);
		return $edate->toFormat('%Y-%m-%d');
	}
	
	public function getEndDate()
	{
		$edate  = new JDate($this->enddate);
		return $edate->toFormat('%Y-%m-%d');
	}

	/**
	 * Return the full URL path for the specific image
	 *
	 * @param	string	$type	The type of avatar to look for 'thumb' or 'avatar'
	 * @return string	The category name
	 **/
	public function getAvatar()
	{
		// Get the avatar path. Some maintance/cleaning work: We no longer store
		// the default avatar in db. If the default avatar is found, we reset it
		// to empty. In next release, we'll rewrite this portion accordingly.
		// We allow the default avatar to be template specific.
		if ($this->avatar == 'components/com_community/assets/event.png')
		{
			$this->avatar = '';
			$this->store();
		}
		CFactory::load('helpers', 'url');
		$avatar	= CUrlHelper::avatarURI($this->avatar, 'eventAvatar.png');
		
		return $avatar;
	}
	
	/**
	 * Return full uri path of the thumbnail
	 */	 	
	public function getThumbAvatar()
	{
		if ($this->thumb == 'components/com_community/assets/event_thumb.png')
		{
			$this->thumb = '';
			$this->store();
		}
		CFactory::load('helpers', 'url');
		$thumb	= CUrlHelper::avatarURI($this->thumb, 'eventThumbAvatar.png');
		
		return $thumb;
	}

	/**
	 *	Set the avatar for for specific group
	 *
	 * @param	appType		Application type. ( users , groups )
	 * @param	path		The relative path to the avatars.
	 * @param	type		The type of Image, thumb or avatar.
	 *
	 **/
	public function setImage(  $path , $type = 'thumb' )
	{
		CError::assert( $path , '' , '!empty' , __FILE__ , __LINE__ );

		$db			=& $this->getDBO();

		// Fix the back quotes
		$path		= JString::str_ireplace( '\\' , '/' , $path );
		$type		= JString::strtolower( $type );

		// Test if the record exists.
		$oldFile	= $this->$type;

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
	    }

	    if( $oldFile )
		{
			// File exists, try to remove old files first.
			$oldFile	= JString::str_ireplace( '/' , DS , $oldFile );

			// If old file is default_thumb or default, we should not remove it.
			// Need proper way to test it
			if(!JString::stristr( $oldFile , 'event.png' ) && !JString::stristr( $oldFile , 'event_thumb.png' ) )
			{
				jimport( 'joomla.filesystem.file' );
				JFile::delete($oldFile);
			}
		}
		$this->$type   = $path;
		$this->store();

	}
	
	public function setConfirmedCount($addCount = 1)
	{
		$this->confirmedcount = $this->confirmedcount + $addCount;
		$this->store();
	}

	public function deleteAllMembers()
	{
		$db =& JFactory::getDBO();

		$sql = "DELETE

				FROM
						".$db->nameQuote("#__community_events_members")."
				WHERE
						".$db->nameQuote("eventid")."=".$db->quote($this->id);

		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}

		return true;
	}

	/**
	 * Delete group's wall
	 *
	 * param	string	id The id of the group.
	 *
	 **/
	public function deleteWalls()
	{
		$db =& JFactory::getDBO();

		$sql = "DELETE

				FROM
						".$db->nameQuote("#__community_wall")."
				WHERE
						".$db->nameQuote("contentid")." = ".$db->quote($this->id)." AND
						".$db->nameQuote("type")." = ".$db->quote('events');
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}

		return true;
	}

	public function getCreator()
	{
		$user		= CFactory::getUser( $this->creator );
		return $user;
	}
	
	public function getCategoryName()
	{
		$category	=& JTable::getInstance( 'EventCategory' , 'CTable' );
		$category->load( $this->catid );

		return $category->name;
	}

	public function getCreatorName()
	{
		$user		= CFactory::getUser( $this->creator );
		return $user->getDisplayName();
	}

	/**
	 * Returns the members list for the specific groups
	 *
	 * @access public
	 * @param	string Category Id
	 * @returns string	Category name
	 **/
	public function getAdmins( $limit = 0 , $randomize = false )
	{
		$mainframe = JFactory::getApplication();
		$limit	= ($limit == 0) ? $mainframe->getCfg('list_limit') : $limit;

// 		$query	= 'SELECT a.memberid AS id, b.name as name FROM '
// 				. $db->nameQuote('#__community_events_members') . ' AS a '
// 				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
// 				. 'WHERE b.id=a.memberid '
// 				. 'AND a.eventid=' . $db->Quote( $this->id ) . ' '
// 				. 'AND a.permission IN (1,2)';
		$query	= 'SELECT memberid AS id, status AS statusCode FROM '
				. $this->_db->nameQuote('#__community_events_members') . ' '
				. 'WHERE eventid = ' . $this->_db->Quote($this->id) . ' '
				. 'AND permission IN (1,2)';

		if($randomize)
		{
			$query	.= ' ORDER BY RAND() ';
		}

		if( !is_null($limit) )
		{
			$query	.= ' LIMIT ' . $limit;
		}
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}

		return $result;
	}

	public function getAdminsCount()
	{
		$query	= 'SELECT count(a.memberid) FROM '
				. $this->_db->nameQuote('#__community_events_members') . ' AS a '
				. 'INNER JOIN ' . $this->_db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.eventid=' . $this->_db->Quote( $this->id ) . ' '
				. 'AND a.permission IN (1,2)';

		$this->_db->setQuery( $query );
		$result	= $this->_db->loadResult();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}

		return $result;
	}

	public function getMembers( $status, $limit = 0, $randomize = false, $pendingApproval = false )
	{
		$mainframe	= JFactory::getApplication();
		$limit	= ($limit == 0) ? $mainframe->getCfg('list_limit') : $limit;

// 		$query	= 'SELECT a.memberid AS id FROM '
// 				. $db->nameQuote('#__community_events_members') . ' AS a '
// 				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
// 				. 'WHERE b.id=a.memberid '
// 				. 'AND a.eventid=' . $db->Quote( $this->id ) . ' '
// 				. 'AND a.permission = 3';
		$query	= 'SELECT memberid AS id, status AS statusCode FROM '
				. $this->_db->nameQuote('#__community_events_members') . ' '
				. 'WHERE eventid = ' . $this->_db->Quote($this->id);

		CFactory::load('helpers', 'event');
		//$statusCode	= CEventHelper::getStatusCode($status);
		$query	.= ' AND status = ' . $this->_db->Quote($status);

		if($randomize)
		{
			$query	.= ' ORDER BY RAND() ';
		}

		if( !is_null($limit) )
		{
			$query	.= ' LIMIT ' . $limit;
		}
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}

		return $result;
	}
	
	// for open invite, no invite request
	public function inviteRequestCount()
	{
		$query	= 'SELECT count(a.memberid) FROM '
				. $this->_db->nameQuote('#__community_events_members') . ' AS a '
				. 'INNER JOIN ' . $this->_db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.eventid=' . $this->_db->Quote( $this->id ) . ' '
				. 'AND a.status=' . $this->_db->Quote( COMMUNITY_EVENT_STATUS_REQUESTINVITE ) . ' ';
	
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadResult();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}

		return $result;
	}
	
	public function getMembersCount( $status , $type = 'all', $pendingApproval = false )
	{
		$query	= 'SELECT count(a.memberid) FROM '
				. $this->_db->nameQuote('#__community_events_members') . ' AS a '
				. 'INNER JOIN ' . $this->_db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.eventid=' . $this->_db->Quote( $this->id ) . ' ';
        
		/*
		if($type != 'all')
        {
          if($type == 'join')
              $query  .= 'AND a.invited_by = ' . $this->_db->Quote('0');
          else if($type == 'invite')
              $query  .= 'AND a.invited_by != ' . $this->_db->Quote('0');
        }
		
		/*
        if($pendingApproval)
            $query  .= 'AND a.`approval` = ' . $this->_db->Quote('1');
		*/
		
		CFactory::load('helpers', 'event');
		//$statusCode	= CEventHelper::getStatusCode($status);
		$query	.= ' AND a.status = ' . $this->_db->Quote($status);

		$this->_db->setQuery( $query );
		$result	= $this->_db->loadResult();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}

		return $result;
	}
	
	public function getMemberStatus($userid)
	{
		if($userid == 0) return false;
		$member	=& JTable::getInstance( 'EventMembers' , 'CTable' );
		$member->load($userid, $this->id);
		
		return $member->status;
	}

	public function isExpired()
	{
		$date		= new JDate( $this->enddate );
		$current	=& JFactory::getDate();
		
		return $current->toUnix( true ) > $date->toUnix( true );
	}
	
	public function isAdmin($userid)
	{
		if($userid == 0) return false;

		$member	=& JTable::getInstance( 'EventMembers' , 'CTable' );
		$member->load($userid, $this->id);

		return ($member->permission == '1' || $member->permission == '2');
	}

	public function isCreator( $userId )
	{
		return ($this->creator == $userId);
	}
	
	/**
	 * Return the status of this user related to this event
	 * 0: invited
	 * 1: attend
	 * 2: won't attend
	 * 3: maybe
	 * 4: blocked from attending
	 * 5: requesting invite
	 * 6: no relation	 	 	 	 	 	 	 
	 */	 	
	public function getUserStatus($userid)
	{
		$member	=& JTable::getInstance( 'EventMembers' , 'CTable' );
		$member->load($userid, $this->id);
		
		// No relation
		if($member->id == 0){
			return COMMUNITY_EVENT_STATUS_NOTINVITED;
		}
		
		return $member->status;
	}

	/**
	 * Check if the given user is a member of the event
	 * @param	string	userid
	 */
	public function isMember($userid)
	{
		
		if($userid == 0)
		{
			return false;
		}


		$member	=& JTable::getInstance( 'EventMembers' , 'CTable' );
		$member->load($userid, $this->id);
		
		if($member->id == '0')
		{
            return false;
        }
        
        return true;
	}

	/**
	 * Check if the given user was pending approval
	 * @param	string	userid
	 */
	public function isPendingApproval($userid)
	{
		// guest is not a member of any group
		if($userid == 0)
			return false;

		$member	=& JTable::getInstance( 'EventMembers' , 'CTable' );
		$member->load($userid, $this->id);

        if($member->id == 0)
        {
            return false;
        }
        else
        {
		    return ($member->status == COMMUNITY_EVENT_STATUS_REQUESTINVITE);
        }
	}


	public function addWallCount()
	{
		$query	= 'UPDATE ' . $this->_db->nameQuote( '#__community_events' ) . ' '
				. 'SET wallcount = ( wallcount + 1 ) '
				. 'WHERE '. $this->_db->nameQuote( 'id' ) .'='. $this->_db->Quote($this->id);
		$this->_db->setQuery( $query );
		$this->_db->query();
		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}
		$this->wallcount++;
	}

	public function substractWallCount()
	{
		$query	= 'UPDATE ' . $this->_db->nameQuote( '#__community_events' ) . ' '
				. 'SET wallcount = ( wallcount - 1 ) '
				. 'WHERE '. $this->_db->nameQuote( 'id' ) .'='. $this->_db->Quote($this->id);
		$this->_db->setQuery( $query );
		$this->_db->query();
		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}
		$this->wallcount--;
	}
	
	// Recalculate event guest stats
	public function updateGuestStats()
	{
		$countFields = array(
					'confirmedcount' => COMMUNITY_EVENT_STATUS_ATTEND,
					'declinedcount' => COMMUNITY_EVENT_STATUS_WONTATTEND,
					'maybecount' => COMMUNITY_EVENT_STATUS_MAYBE,
					'invitedcount' => COMMUNITY_EVENT_STATUS_INVITED	);
		
		// update all 4 count fields
		foreach($countFields as $key => $value)
		{
			$query	= 'SELECT count(*) FROM ' . $this->_db->nameQuote( '#__community_events_members' ) . ' '
					. ' WHERE ' 
					. 		$this->_db->nameQuote( 'status' ) . '=' . $this->_db->Quote( $value )
					. ' AND '
					. 		$this->_db->nameQuote( 'eventid' ) . '=' . $this->_db->Quote( $this->id );
					
			$this->_db->setQuery( $query );
			$this->$key = $this->_db->loadResult();
		}
	}
	
	/** Interface fucntions **/
	
	
	public function resolveLocation($address)
	{
		CFactory::load('libraries', 'mapping');
		$data = CMapping::getAddressData($address);
		
		// reset it to null;
		$this->latitude 	= COMMUNITY_LOCATION_NULL;
		$this->longitude	= COMMUNITY_LOCATION_NULL;
		
		if($data){
			if($data->status == 'OK')
			{
				$this->latitude  = $data->results[0]->geometry->location->lat;
				$this->longitude = $data->results[0]->geometry->location->lng; 
			}
		}
	}

	/**
	 * Remove guest from events
	 *
	 **/
	public function removeGuest($guestId, $eventId){
		$db		=&	JFactory::getDBO();

		$query	=	"DELETE FROM " . $db->nameQuote("#__community_events_members")
					. " WHERE " . $db->nameQuote("memberid") . "=" . $db->quote($guestId)
					. " AND " . $db->nameQuote("eventid") . "=" . $db->quote($eventId) ;

		$db->setQuery($query);

		$db->Query();

		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}

		return true;
	}
}