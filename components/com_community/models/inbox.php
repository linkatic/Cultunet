<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Messaging
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'models' . DS . 'models.php' );

// Deprecated since 1.8.x to support older modules / plugins
CFactory::load( 'tables' , 'message' );

class CommunityModelInbox extends JCCModel
{

	var $_data = null;
	var $_pagination = null;	
	
	/**
	 *  Constructor to set the limit
	 */
	
	function CommunityModelInbox(){
		parent::JCCModel();
 	 	global $option;
 	 	$mainframe =& JFactory::getApplication();
 	 	
 	 	// Get pagination request variables
 	 	$limit		= ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
	    $limitstart	= JRequest::getVar('limitstart', 0, 'REQUEST');
 	 	
 	 	// In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
 	 	$this->setState('limit',$limit);
 	 	$this->setState('limitstart',$limitstart);
	}		
	
	/**
	 * Return the conversation list
	 */	 	
	function &getInbox()
	{
	    jimport('joomla.html.pagination');
		$my =& JFactory::getUser();
		$to = $my->id;
		
		if (empty($this->_data))
		{		    		
			$this->_data = array();

			$db =& $this->getDBO();
			
			// Select all recent message to the user				
// 			$sql = "SELECT b.* "
// 				." FROM #__community_msg_recepient as a, "
// 				." #__community_msg as b "
// 				." WHERE "
// 				." a.`to` = {$to} AND "
// 				." b.`id` = a.`msg_id` AND"
// 				." a.`deleted`=0 "
// 				." ORDER BY b.`id` ASC, b.`parent`";

			$sql = "SELECT MAX(b.`id`) AS `bid`";
			$sql .= " FROM #__community_msg_recepient as a, #__community_msg as b"; 
			$sql .= " WHERE a.`to` = {$to}"; 
			$sql .= " AND b.`id` = a.`msg_id`"; 
			$sql .= " AND a.`deleted`=0"; 
			$sql .= " GROUP BY b.`parent`";
			$db->setQuery($sql);
			$tmpResult = $db->loadObjectList();			
			
			$strId = '';
			foreach ($tmpResult as $tmp)
			{
				if (empty($strId)) $strId = $tmp->bid;
				else $strId = $strId . ',' . $tmp->bid;
			}
			
			$result	= null;
			if( ! empty($strId) )
			{	
				$sql = "SELECT b.`id`, b.`from`, b.`parent`, b.`from_name`, b.`posted_on`, b.`subject`";
				$sql .= " FROM #__community_msg as b"; 
				$sql .= " WHERE b.`id` in (".$strId.")"; 			
				$sql .= " ORDER BY b.`posted_on` DESC";
					
				$db->setQuery($sql);
				$result = $db->loadObjectList();
				if($db->getErrorNum()) {
					JError::raiseError( 500, $db->stderr());
			    }
		    }
			
			// For each message, find the parent+from, group them together 
			$inboxResult =  array();
			if(!empty($result)){
				foreach($result as $row) {
					$inboxResult[$row->parent] = $row;
				}
			}
			
		    $limit 		= $this->getState('limit');
		    $limitstart	= $this->getState('limitstart');
			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($inboxResult), $limitstart, $limit );
				$inboxResult = array_slice($inboxResult, $limitstart, $limit);
			}
			
			return $inboxResult;
		}				
		
		return null;
	}
	
	/**
	 * get pagination
	 */	 	
	function &getPagination()
	{
	 	return $this->_pagination;
	}
	
	/**
	 * Return list of sent items
	 */	 	
	function &getSent()
	{
	    jimport('joomla.html.pagination');
	    $my =& JFactory::getUser();
		$from = $my->id;
		
		if (empty($this->_data))
		{		    		
			$this->_data = array();

			$db =& $this->getDBO();

			// Select all recent message to the user
// 			$sql = "SELECT b.* "
// 				." FROM #__community_msg_recepient as a, "
// 				." #__community_msg as b "
// 				." WHERE "
// 				." a.`msg_from` = {$from} AND "
// 				." b.`id` = a.`msg_id` AND"
// 				." a.`deleted`=0 "
// 				." ORDER BY b.`parent` DESC, b.`id` ";
				
			$sql = "SELECT b.*, a.`to`, c.`name` as `to_name` "
				." FROM #__community_msg_recepient as a, "
				." #__community_msg as b, #__users c "
				." WHERE "
				." b.`from` = {$from} AND "
				." b.`deleted`=0 AND"
				." b.`id` = a.`msg_id` AND"
				." a.`to` = c.`id`"				
				." ORDER BY b.`parent` DESC, b.`id` ";
				
			$db->setQuery($sql);
			$result = $db->loadObjectList();
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
		    }
			
			// For each message, find the parent+from, group them together 
			$inboxResult =  array();
			$inToName =  array();
			$inToId   =  array();
			if(!empty($result)){
				foreach($result as $row) {
					@$inToName[$row->parent][$row->to_name] = $row->to_name;
					@$inToId[$row->parent][$row->to]        = $row->to;
					//@$inToId[$row->parent][$row->to] .= (empty($inToId[$row->parent])) ? $row->to :  '|'.$row->to ;//ignore the index not found warning.				
									    
					$inboxResult[$row->parent] = $row; 
				}
			}
			
			
			
			//now rewrite back the to / to_name
			foreach($inboxResult as $row){
			   $inboxResult[$row->parent]->to = $inToId[$row->parent];
			   $inboxResult[$row->parent]->to_name = $inToName[$row->parent];
			}
			
			//var_dump($inboxResult);exit;
			
		    $limit 		= $this->getState('limit');
		    $limitstart	= $this->getState('limitstart');
			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($inboxResult), $limitstart, $limit );
				$inboxResult = array_slice($inboxResult, $limitstart, $limit);
			}			
			
			return $inboxResult;
		}				
		
		return null;
	}
	/**
	 * Return the full messages
	 */	 	
	function getFullMessages($id){
		$db =& $this->getDBO();
		
		$sql = "SELECT parent FROM #__community_msg WHERE `id`=" . $db->Quote($id);
		
		$db->setQuery($sql);
		$msg = $db->loadObject();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr(). 'Messsage not found');
		}
		
		$query	= "SELECT * FROM #__community_msg WHERE `parent`=" . $db->Quote($msg->parent);
		$query .= " ORDER BY `id`";
		$db->setQuery($query);

		$result = $db->loadObjectList();
		
		return $result;
	}
	
	/**
	 * Return the sent messages for later removal.
	 */	
	function getSentMessages($id)
	{
		$db =& $this->getDBO();
		$my	= CFactory::getUser();
		
		$sql = "SELECT parent FROM #__community_msg WHERE `id`=" . $db->Quote($id);
		
		$db->setQuery($sql);
		$msg = $db->loadObject();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr(). 'Messsage not found');
		}
		
		$query	= "SELECT * FROM #__community_msg WHERE `parent`=" . $db->Quote($msg->parent);
		$query	.= " AND `from` = " . $db->Quote($my->id);
		$query	.= " ORDER BY `id`";
		$db->setQuery($query);

		$result = $db->loadObjectList();
		
		return $result;	
	}
	
	
	/**
	 * Return the message
	 */	 	
	function &getMessage($id){
		$db =& $this->getDBO();
		$sql = "SELECT * FROM #__community_msg WHERE `id`=" . $db->Quote($id);
		
		$db->setQuery($sql);
		$result = $db->loadObject();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr(). 'Messsage not found');
		}
		
		return $result;
	}
	
	/**
	 * Return the recepient message
	 */	 	
	function &getRecepientMessage($id){
		$db =& $this->getDBO();
		$sql = "SELECT * FROM #__community_msg_recepient WHERE `msg_id`=" . $db->Quote($id);				
		
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr(). 'Messsage not found');
		}
		
		return $result;
	}
	
	/**
	 * Return the time the given user send the last message
	 */	 	
	function getLastSentTime($id){
		$user = CFactory::getUser($id);
		$db =& $this->getDBO();
		$sql = "SELECT `posted_on` FROM #__community_msg WHERE `from`=" . $db->Quote($id)
				." ORDER BY `posted_on` DESC";				
		
		$db->setQuery($sql);
		$postedOn = $db->loadResult();
		
		if(empty($postedOn)){
			// set to a far distance past to indicate last sent time was
			// very far away in the past
			return new JDate('1990-01-01 10:00:00');
		} else {
			return new JDate($postedOn);
		}
	}
	
	/**
	 * Return the latest recepient message based on parent message id.
	 */	 	
	function &getUserMessage($id){
	    $my =& JFactory::getUser();
		$to = $my->id;	
	
		$db =& $this->getDBO();

		$sql = "select a.* from #__community_msg_recepient a";
		$sql .= " where a.`to` = {$to} and a.`msg_parent` = (select distinct b.`msg_parent`";
		$sql .= "          from #__community_msg_recepient b where b.`msg_id` = " . $db->Quote($id) . ")";
		$sql .= " order by `msg_id` desc limit 1";
		
		$db->setQuery($sql);
		$result = $db->loadObject();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr(). 'Messsage not found');
		}
		
		return $result;
	}	
	
	function &getMessages($filter = array())
	{
	
	    $my =& JFactory::getUser();
	    $db =& $this->getDBO();	
	    
		if (empty($this->_data))
		{
			$this->_data = array();			
				
		    $sql = "SELECT a.*, b.`to`, b.`deleted` as `to_deleted`, b.`is_read`"
				." FROM #__community_msg a, #__community_msg_recepient b"
				." where a.`parent` = " . $db->Quote($filter['msgId'])
				." and  b.`msg_parent` = " . $db->Quote($filter['msgId'])
				." and  a.`id` = b.`msg_id`"
				." order by a.`id` desc, a.`deleted` desc, b.`deleted` desc";
			

			$db->setQuery($sql);
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
		    }
		    
			// Now, we get all the conversation within this discussion
		    $allMsgFromMe = $db->loadObjectList();
		    
		    // perform further filtering
		    $prev_id = 0;
			foreach($allMsgFromMe as $row){
			    $showMsg = true;			    			    
			    
			    if($row->to == $my->id){ //message for me.                
                    $showMsg = ($row->to_deleted == 0);
				} else if($row->from == $my->id){ // message from me
				    $showMsg = ($row->deleted == 0);
				}
				
				// check whether this message id is the same as previous one or not.
				// if yes...mean the message send to multiple users. We need to show
				// only one time.
				if($showMsg){
				    $showMsg = ($row->id != $prev_id);				    
				}
				
				//update the flag for next checking.
				$prev_id = $row->id;
				
				if($showMsg){
				    //append message into array object
				    $this->_data[] = $row;
				}
			}
			
			//reverse the array so that it show the old to latest.
			$this->_data = array_reverse($this->_data);
			

// 			// grab everything to me
// 			$sql = "SELECT b.* FROM "
// 				." #__community_msg_recepient as b WHERE "
// 				//." b.`to`='{$filter['to']}' AND "
// 				." b.`msg_parent`='{$filter['msgId']}' ORDER BY `msg_id` ASC";
// 					
// 			$db->setQuery($sql);
// 			echo $db->getQuery();
// 			
// 			if($db->getErrorNum()) {
// 				JError::raiseError( 500, $db->stderr());
// 		    }
// 		    $toMe = $db->loadObjectList();
											
		}
		
		return $this->_data;
	}
	
	
	function send($vars)
	{	    
		$db =& $this->getDBO();
		$my	=& JFactory::getUser();
		
		// @todo: user db table later on				
		//$cDate =& JFactory::getDate(gmdate('Y-m-d H:i:s'), $mainframe->getCfg('offset'));//get the current date from system.
		//$date	= cGetDate();
		$date	=& JFactory::getDate(); //get the time without any offset!
		$cDate	=$date->toMySQL(); 
		
		$obj = new stdClass();
		$obj->id = null;
		$obj->from = $my->id;
		$obj->posted_on = $date->toMySQL();
		$obj->from_name	= $my->name;
		$obj->subject	= $vars['subject'];
		$obj->body		= $vars['body'];
		
		$db->insertObject('#__community_msg', $obj, 'id');
		
		// Update the parent
		$obj->parent = $obj->id;
		$db->updateObject('#__community_msg', $obj, 'id');
		
		if(is_array($vars['to'])){
		    //multiple recepint
		    foreach($vars['to'] as $sToId){
		        $this->addReceipient($obj, $sToId);
		    }		    
		} else {
		    //single recepient
		    $this->addReceipient($obj, $vars['to']);
		}    
		
		return $obj->id;
	}
	
	/**
	 *
	 */	 	
	function sendReply($obj, $replyMsgId){
		$db =& $this->getDBO();
		$my	=& JFactory::getUser();
		
		// get original sender from obj
		$originalMsg  = new CMessage($db);
		$originalMsg->load($replyMsgId);
		
		$recepientMsg = $this->getRecepientMessage($replyMsgId);
		$parentId = $originalMsg->parent;
		
		$db->insertObject('#__community_msg', $obj, 'id');
		
		// Update the parent
		$obj->parent = $parentId;
		$db->updateObject('#__community_msg', $obj, 'id');
		
		if(is_array($recepientMsg)){				    
		    $recepientId = $this->getParticipantsID($replyMsgId, $my->id);
			
			foreach($recepientId as $sToId){
			    $this->addReceipient($obj, $sToId);
			}		
		
		} else {
		
			// add receipient, get the 'to' address from the original
			// sender. BUT, in some case where user try to post two message in
			// a row, the 'from' will failed. instead, we need to use 'to' from 
			// the original message.
			$recepientId = $originalMsg->from;
			if($my->id == $originalMsg->from){
			    $recepientId = $recepientMsg->to;
			}
			$this->addReceipient($obj, $recepientId);
			
		}				
		
		return true;
	}
	
	/**
	 * Add receipient
	 */	 	
	function addReceipient($msgObj, $recepientId){
		$db =& $this->getDBO();
	        
		$recepient = new stdClass();
		$recepient->msg_id = $msgObj->id;
		$recepient->msg_parent = $msgObj->parent;
		$recepient->msg_from = $msgObj->from;
		$recepient->to	= $recepientId;		
		$db->insertObject('#__community_msg_recepient', $recepient);
		
		if($db->getErrorNum()) {
		     JError::raiseError( 500, $db->stderr());
	    }
	}
	
	/**
	 * Remove the received message
	 */	 	
	function removeReceivedMsg($msgId, $userid){
		$db =& $this->getDBO();
		
		// get original sender and recepient
		$originalMsg  = new CMessage($db);
		$originalMsg->load($msgId);
		
		$recepientMsg = $this->getRecepientMessage($msgId);
		
		// we need to determind which table we needed for message removal.
		// we 1st check on the original message 'from', current user id matched,
		// then we remove from master table.
		// ELSE, we remove from child table.
		
		$sql = "";
		$delFrom = false;
		$delTo   = false;
		if($originalMsg->from == $userid){
	 		$sql = "UPDATE #__community_msg "
	 			." SET `deleted`='1' "
	 			." WHERE `id`=" . $db->Quote($msgId) . " AND `from`=" . $db->Quote($userid);
				 
	        //executing update query
	 		$db->setQuery($sql);
	 		$db->query();
			$delFrom = true;				 		
		}
		
		if(is_array($recepientMsg)){
		    //multi recepient
		    //echo "array";
		    
		    foreach($recepientMsg as $row){
				if($row->to == $userid) {
			 		$sql = "UPDATE #__community_msg_recepient "
			 			." SET `deleted`='1' "
			 			." WHERE `msg_id`=" . $db->Quote($msgId) . " AND `to`=" . $db->Quote($userid);
			        //executing update query
			 		$db->setQuery($sql);
			 		$db->query();
					$delTo = true;	 			
				}
		    }
		} else {
			if($recepientMsg->to == $userid) {
		 		$sql = "UPDATE #__community_msg_recepient "
		 			." SET `deleted`='1' "
		 			." WHERE `msg_id`=" . $db->Quote($msgId) . " AND `to`=" . $db->Quote($userid);
		        //executing update query
		 		$db->setQuery($sql);
		 		$db->query();
				$delTo = true;	 			
			}
		}
		
		
		if($delFrom == false && $delTo == false) {
		    //both oso not matched. return false.
		    return false;
		}
		 		
		return true;
	}
	
	function &getUserId($param = array()){
	    	
		$db =& $this->getDBO();
		$userId = 0;							
		$sql = "";
		
		if(! empty($param['name'])){
            // get from users table 				
		    $sql = "select `id` from ".$db->nameQuote('#__users').' where '.$db->nameQuote('username').' = '.$db->Quote($param['name']);		    		    
		} else {
		    // get from community_message table
		    $sql = "select `from` as `id` from ".$db->nameQuote('#__community_message').' where '.$db->nameQuote('id').' = '.$db->Quote($param['id']);		    		    
		}				
		
		$db->setQuery($sql);
		if($db->getErrorNum()) {
		     JError::raiseError( 500, $db->stderr());
	    }
		 
		$result = $db->loadResult();
		
		if(! empty($result)) $userId = $result;
		
		return $userId;
		// JError::raiseError( 500, 'Receiver id not found.');
	}

	/**
	 * Mark a message as "read" (opened)
	 * @param	object 		parent message id
	 * @param	object 		current user id	 
	 */	 	
	function markMessageAsRead($filter){
		$db =& $this->getDBO();
		$my =& JFactory::getUser();				
		
		// update all the messages that belong to current user.
 		$sql = "UPDATE #__community_msg_recepient "
 			." SET `is_read`= 1"
 			." WHERE `msg_parent`=" . $db->Quote($filter['parent']) . " AND `to`=" . $db->Quote($filter['user_id'])
 			." AND `is_read`= 0";
		
        //executing update query
 		$db->setQuery($sql);
 		$db->query();
		 		
		return true;
	}
	
	/**
	 * Mark a message as "new" 
	 * @param	object 		parent message id
	 * @param	object 		current user id	 
	 */	 	
	function markMessageAsUnread($filter){
		$db =& $this->getDBO();
		$my =& JFactory::getUser();				
		
		// update all the messages that belong to current user.
 		$sql = "UPDATE #__community_msg_recepient "
 			." SET `is_read`= 0"
 			." WHERE `msg_parent`=" . $db->Quote($filter['parent']) . " AND `to`=" . $db->Quote($filter['user_id'])
 			." AND `is_read`= 1";
		
        //executing update query
 		$db->setQuery($sql);
 		$db->query();
		 		
		return true;
	}		
	
	/**
	 * Mark a message as "read" (opened) from Inbox page
	 * @param	object 		message id
	 * @param	object 		current user id	 
	 */	 	
	function markAsRead($filter){
		$db =& $this->getDBO();
		$my =& JFactory::getUser();				
		
		// update all the messages that belong to current user.
 		$sql = "UPDATE #__community_msg_recepient "
 			." SET `is_read`= 1"
 			." WHERE `msg_id`=" . $db->Quote($filter['parent']) . " AND `to`=" . $db->Quote($filter['user_id'])
 			." AND `is_read`= 0";
		
        //executing update query
 		$db->setQuery($sql);
 		$db->query();
		 		
		return true;
	}

	/**
	 * Mark a message as "read" (opened) from Inbox page
	 * @param	object 		message id
	 * @param	object 		current user id
	 */
	function markAsUnread($filter){
		$db =& $this->getDBO();
		$my =& JFactory::getUser();

		// update all the messages that belong to current user.
 		$sql = "UPDATE #__community_msg_recepient "
 			." SET `is_read`= 0"
 			." WHERE `msg_id`=" . $db->Quote($filter['parent']) . " AND `to`=" . $db->Quote($filter['user_id'])
 			." AND `is_read`= 1";

        //executing update query
 		$db->setQuery($sql);
 		$db->query();

		return true;
	}
	
	/**
	 * Check if the user can reply to this message thread
	 */	 	
	function canReply( $userid, $msgId ){
		$db =& $this->getDBO();
		$sql = "SELECT COUNT(*) FROM #__community_msg_recepient"
			." WHERE (`msg_parent`=" . $db->Quote($msgId) . " OR `msg_id`=" . $db->Quote($msgId) . " ) "
			." AND ( "
			."	`to`=" . $db->Quote($userid)
			."	OR `msg_from`=" . $db->Quote($userid)
			." )";
		
		$db->setQuery($sql);
		//echo $db->getQuery(); 
		
		return $db->loadResult();
	}
	
	/**
	 * Check if user can read this message.
	 * 
	 * @param	string 	userid
	 * @param 	string	msgID : should be the parent message	 	 	 
	 */	 	
	function canRead( $userid, $msgId ) {
		// really, if the user can reply to this message, then he can read it
		return $this->canReply( $userid, $msgId );
	}
	
	function getTotalMessageSent( $userId )
	{
		CFactory::load( 'helpers' , 'time' );
		$date		= CTimeHelper::getDate();
		$db			=& $this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_msg' ) . ' AS a '
				. 'WHERE a.from=' . $db->Quote( $userId ) . ' '
				. 'AND TO_DAYS(' . $db->Quote( $date->toMySQL( true ) ) . ') - TO_DAYS( DATE_ADD( a.posted_on , INTERVAL ' . $date->getOffset() . ' HOUR ) ) = 0 '
				. 'AND a.parent=a.id';
		$db->setQuery( $query );
		
		$count		= $db->loadResult();
		
		return $count;
	}
	
	/**
	 * Get unread message count for current user
	 * @param	int		parent message id
	 * @param	int		current user id
	 * @return  int     unread message count	 	 
	 */	 	
	function countUnRead($filter){
		 $db =& $this->getDBO();
		 $unRead = 0;
		 
		 // Skip the whole db query if no user specified
		 if(empty($filter['user_id']))
		 	return 0;
		 
		 $sql = "select count('1') as `unread_count`";
		 $sql .= " from #__community_msg_recepient";
		 $sql .= " where `is_read` = 0";
		 if(! empty($filter['parent']))
		     $sql .= " and `msg_parent` =" . $db->Quote($filter['parent']);		 
		 if(! empty($filter['user_id']))
		     $sql .= " and `to` =" . $db->Quote($filter['user_id']);		 		 
		 
		 $sql .= " and `deleted` = 0";
		 $db->setQuery($sql);		 
		 $result = $db->loadObject();
		 
		 if(! empty($result)){
		     $unRead = $result->unread_count;
		 }
		 
		 return $unRead;
	}
	
	/**
	 * Get total recepient conversation message count for a message.
	 */	 	
	function getRecepientCount($filter){
		 $db =& $this->getDBO();
		 $msgCnt = 0;
		 
		 $sql = "select count('1') as `recepient_count`"; 
		 $sql .= " from #__community_msg_recepient";
		 $sql .= " where `msg_parent` = " . $db->Quote($filter['parent']);
		 if(! empty($filter['user_id']))
		     $sql .= " and `to` !=" . $db->Quote($filter['user_id']);		 
		 
		 $db->setQuery($sql);		 
		 $result = $db->loadObject();
		 
		 if(! empty($result)){
		     $msgCnt = $result->unread_count;
		 }
		 
		 return $msgCnt;
	}
	
	/**
	 * Given any message id, return an array of userid that are involved in the
	 * conversation, be it recipient or sender.
	 * 	 	 	 
	 */	 	
	function getParticipantsID($msgid, $exclusion=0){
		$getParticipantsIDs = array();
		$db =& $this->getDBO();
		
		// with the given msgid, get the parent.
		$sql = "SELECT `parent` ";
		$sql .= " FROM #__community_msg ";
		$sql .= " WHERE `id` = ". $db->Quote($msgid);
		
		$db->setQuery($sql);
		$parentId = $db->loadResult();
		if($db->getErrorNum()) {
		     JError::raiseError( 500, $db->stderr());
	     }
	     
			
		// with the parentid, get all the recipient and the senderid
		$sql = "SELECT `msg_from`, `to` ";
		$sql .= " FROM #__community_msg_recepient ";
		$sql .= " WHERE `msg_parent` = ". $db->Quote($parentId);
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		if($db->getErrorNum()) {
		     JError::raiseError( 500, $db->stderr());
	     }
	     
	     if($result){
	     	foreach($result as $row){
				if($exclusion != $row->to){
			    	$getParticipantsIDs[] = $row->to; 
				}
				
				if($exclusion != $row->msg_from){
			    	$getParticipantsIDs[] = $row->msg_from;
				}
		 	}
		 	
		 	$getParticipantsIDs = array_unique($getParticipantsIDs);
		 }
		
		return $getParticipantsIDs;
	}
	
	/**
	 * Get all recepient user id for a message except the current userid.
	 * 
	 * @depreciated, use getParticipantsID instead	 	 
	 */	 	
	function &getMultiRecepientID($filter = array()){
		$db =& $this->getDBO();
		$my =& JFactory::getUser();
		
		$originalMsg  = new CMessage($db);
		$originalMsg->load($filter['reply_id']);
		
		$RecepientMsg = $this->getRecepientMessage($filter['reply_id']);
		
		$recepient = array();		 		 
		 
		if($my->id != $originalMsg->from){
		     $recepient[] = $originalMsg->from; // the original sender
		 }
		 
		 foreach($RecepientMsg as $row){
			 if($my->id != $row->to){
			     $recepient[] = $row->to; // the original sender
			 }
		 }
		 
		 return $recepient;
	}


	/**
	 * Get current user all the unread messages
	 * param user_id
	 */		
	function &getUnReadInbox()
	{
		$db =& $this->getDBO();
		$my = CFactory::getUser();

		$sql = "SELECT b.`id`, b.`from`, b.`parent`, b.`from_name`, b.`posted_on`, b.`subject`";
		$sql .= " FROM #__community_msg_recepient as a, #__community_msg as b";
		$sql .= " WHERE a.`to` = ".$my->id;
		$sql .= " AND `is_read` = 0";
		$sql .= " AND a.`deleted` = 0"; 
		$sql .= " AND b.`id` = a.`msg_id`";
		$sql .= " ORDER BY b.`posted_on` DESC";
		
		$db->setQuery($sql);
		$result = $db->loadObjectList();		 		
	
		return $result;
	}
	
	
	/**
	 * Get current user latest messages
	 * param user_id
	 * param limit (optional)	 	 
	 */	 	
	function &getLatestMessage($filter = array(), $limit = 5){
		 $db =& $this->getDBO();
		 $my =& JFactory::getUser();
		 
		 $user_id = (empty($filter['user_id'])) ? $my->id : $filter['user_id']; 

         $sql = "select a.`msg_id`, a.`msg_parent` , b.`from`, b.`from_name`,"; 
		 $sql .= " b.`posted_on`, b.`body`";
         $sql .= " from #__community_msg_recepient a, #__community_msg b";
         $sql .= " where a.`to` =" . $db->Quote($user_id);
         $sql .= " and a.deleted = 0";
         $sql .= " and a.msg_id = b.id";
         $sql .= " order by msg_id desc"; 
         $sql .= " limit {$limit}";
         
		 $db->setQuery($sql);		 
		 if($db->getErrorNum()) {
		     JError::raiseError( 500, $db->stderr());
	     }
	     
	     $result = $db->loadObjectList();
		 
		 return $result;
	}
	
	function getUserInboxCount()
	{	    
	    $db				=& $this->getDBO();
		$my				=& JFactory::getUser();
		$inboxResult	= array();
		
		// Select all recent message to the user
		$sql = "SELECT MAX(b.`id`) AS `bid`";
		$sql .= " FROM #__community_msg_recepient as a, #__community_msg as b"; 
		$sql .= " WHERE a.`to` = " . $my->id; 
		$sql .= " AND b.`id` = a.`msg_id`"; 
		$sql .= " AND a.`deleted`=0"; 
		$sql .= " GROUP BY b.`parent`";
		$db->setQuery($sql);
		$tmpResult = $db->loadObjectList();			
		
		$strId = '';
		foreach ($tmpResult as $tmp)
		{
			if (empty($strId)) $strId = $tmp->bid;
			else $strId = $strId . ',' . $tmp->bid;
		}
		
		$result	= null;
		if( ! empty($strId) )
		{	
			$sql = "SELECT b.`id`, b.`parent`, b.`posted_on`";
			$sql .= " FROM #__community_msg as b"; 
			$sql .= " WHERE b.`id` in (".$strId.")"; 			
			$sql .= " ORDER BY b.`posted_on` DESC";
				
			$db->setQuery($sql);
			$result = $db->loadObjectList();
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
		    }
	    }
		
		// For each message, find the parent+from, group them together 			
		if(!empty($result)){
			foreach($result as $row) {
				$inboxResult[$row->parent] = $row;
			}
		}
						
		return count($inboxResult);
	}					
}
