<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Friends
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die ('Restricted access');

require_once (JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

class CommunityModelFriends extends JCCModel
{
    var $_data = null;
    var $_profile;
    var $_pagination;

    function CommunityModelFriends()
    {
        parent::JCCModel();
        global $option;
        $mainframe = & JFactory::getApplication();

        // Get pagination request variables
        $limit = ($mainframe->getCfg('list_limit') == 0)?5:$mainframe->getCfg('list_limit');
        $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0?(floor($limitstart/$limit)*$limit):
            0);

            $this->setState('limit', $limit);
            $this->setState('limitstart', $limitstart);
        }
		
		/**
		 * Deprecated since 1.8
		 */	
        function addFriendCount($userId)
        {
             $this->updateFriendCount($userId);
        }
		
		/**
		 * Deprecated since 1.8
		 */		 		
        function substractFriendCount($userId)
        {
            $this->updateFriendCount($userId);
        }
        
        function updateFriendCount($userId)
        {
        	$db = & $this->getDBO();
        	$count = $this->getFriendsCount($userId);
        	
        	$query = 'UPDATE '.$db->nameQuote('#__community_users')
            .'SET '.$db->nameQuote('friendcount').'='.$db->Quote($count)
            .'WHERE '.$db->nameQuote('userid').'='.$db->Quote($userId);
            $db->setQuery($query);
            $db->query();

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
		}

        function & getData()
        {
            if ( empty($this->_data))
            {
                $this->_data = array ();

                $this->_data['name'] = 'Testing';
                $this->_data['status'] = 'Alive';
            }

            return $this->_data;
        }

        function & getFiltered($wheres = array ())
        {
            $db = & $this->getDBO();

            $wheres[] = 'block = 0';

            $query = "SELECT *"
            .' FROM #__users'
            .' WHERE '.implode(' AND ', $wheres)
            .' ORDER BY `id` DESC ';

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            $result = $db->loadObjectList();
            return $result;
        }

        function setFriend()
        {
        }

        /**
         * Search for people
         * @param query	string	people's name to seach for
         */
        function searchPeople($query)
        {
            $filter = array ();
            $strict = true;
            $regex = $strict?
            '/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i':
            '/^([*+!.&#$�\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i'
            ;
            if (preg_match($regex, JString::trim($query), $matches))
            {
                $query = array ($matches[1], $matches[2]);
                $filter = array ("`email`='{$matches[1]}@{$matches[2]}'");
            } else
            {
                $filter = array ("`username`='$query'");
            }

            $result = $this->getFiltered($filter);

            // for each one of these people, we need to load their relationship with
            // our current user
            // 		if(!empty($result)){
            // 			for($i = 0; $i = $result; $i++){
            //
            // 			}
            // 		}

            return $result;
        }


        /**
         * Save a friend request to stranger. Stranger will have to approve
         * @param	$id		int		stranger user id
         * @param   $fromid int     owner's id
         */
        function addFriend($id, $fromid, $msg='', $status = 0)
        {
            $my = JFactory::getUser();
            $db = & $this->getDBO();
            $wheres[] = 'block = 0';

            if ($my->id == $id)
            {
                JError::raiseError(500, JText::_('Cannot Add Your Self As A Friend'));
            }

            //@todo escape code
            $date	=& JFactory::getDate(); //get the time without any offset!
            $query	= "INSERT INTO #__community_connection SET"
				. ' `connect_from` = '.$db->Quote($fromid)
            	. ', `connect_to` = '.$db->Quote($id)
            	. ', `status` = '. $db->Quote($status)
            	. ', `created` = ' . $db->Quote($date->toMySQL())
				. ', `msg` = ' . $db->Quote($msg);

            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

        }

        /**
         * Send a friend request to stranger. Stranger will have to approve
         * @param	$id		int		stranger user id
         */
        function addFriendRequest($id, $fromid)
        {
            $my = JFactory::getUser();
            $db = & $this->getDBO();
            $wheres[] = 'block = 0';

            if ($my->id == $id)
            {
                JError::raiseError(500, JText::_('Cannot Add Your Self As A Friend'));
            }

			$date	=& JFactory::getDate(); //get the time without any offset!
            //@todo escape code
            $query = "INSERT INTO #__community_connection SET"
            	. ' `connect_from`='.$db->Quote($fromid)
            	. ', `connect_to`='.$db->Quote($id)
            	. ', `status`=1'
            	. ', `created` = ' . $db->Quote($date->toMySQL());

            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }


            //@todo escape code
            $query = "INSERT INTO #__community_connection SET"
            	. ' `connect_from`='.$db->Quote($id)
            	. ', `connect_to`='.$db->Quote($fromid)
            	. ', `status`=1'
            	. ', `created` = ' . $db->Quote($date->toMySQL());

            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
        }



        /**
         *@param $id int user id
         *@param $groupname var group name
         *@return boolean
         */
        function addFriendGroup($id, $groupname)
        {
            $db = & $this->getDBO();
            $query = "SELECT `group_name` FROM #__community_friendlist
		        WHERE `group_name`=".$db->Quote($groupname)."
				AND `user_id`=".$db->Quote($id);

            $resource = $db->setQuery($query);

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }


            $row = & $db->loadObject();
            //echo '......'.get_class($row);
            $name = & $row->group_name;
            //echo $name;
            if ($name != $groupname)
            {
                //@todo escape Quote
                $query = "INSERT INTO #__community_friendlist SET"
                .'`group_name`='.$db->Quote($groupname)
                .',`user_id`='.$db->Quote($id);
                $db->setQuery($query);
                $db->query();

                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }
                return true;
            }
            else
            {
                return false;
            }

        }
        /**
         *@param $id int user id
         *@param $groupid int group id
         *
         */
        function deleteFriendGroup($id, $groupid)
        {
            $db = & $this->getDBO();
            $query = "DELETE FROM #__community_friendlist WHERE"
            .'`user_id`='.$db->Quote($id).' AND'
            .'`group_id`='.$db->Quote($groupid);
            $db->setQuery($query);
            $db->query();

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

        }


        /**
         *@param $id int user id
         *@param $groupid int group id
         *
         */
        function deleteFriendsTag($id, $groupid)
        {
            $db = & $this->getDBO();
            $query = "DELETE FROM #__community_friendgroup WHERE"
            .'`user_id`='.$db->Quote($id).' AND'
            .'`group_id`='.$db->Quote($groupid);
            $db->setQuery($query);
            $db->query();


            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            return true;

        }


        /**
         * Delete sent request
         */
        function deleteSentRequest($from, $to)
        {
            $db = & $this->getDBO();

            $query = "DELETE FROM #__community_connection
				  WHERE `connect_from` = ".$db->Quote($from)."
				  AND `connect_to` = ".$db->Quote($to)." AND `status` = '0' ";

            $db->setQuery($query);
            $db->query();

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            return true;
        }

        /**
         *delete friend connection
         *@param $conn_from int user_id should use JFactory::getUser() id
         *@param $conn_to int user_id
         *@return true when delete success
         */

        function deleteFriend($conn_from, $conn_to)
        {
            $db = & $this->getDBO();
            //1- check connection exist or not
            $query = "SELECT * FROM #__community_connection
	            WHERE `connect_from` = ".$db->Quote($conn_from)." AND `connect_to` = ".$db->Quote($conn_to)."
	            AND `status`=1";

            $db->setQuery($query);

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
            $rows1 = $db->loadObject();

            $query = "SELECT * FROM #__community_connection
	            WHERE `connect_from` = ".$db->Quote($conn_to)." AND `connect_to` = ".$db->Quote($conn_from)."
	            AND `status`=1";

            $db->setQuery($query);

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
            $rows2 = $db->loadObject();

            //@todo avoid sql injection..
            //2- delete connection
            if (count($rows1) > 0 && count($rows2) > 0)
            {
                $query = "DELETE FROM #__community_connection
			        WHERE `connection_id` in (".$db->Quote($rows1->connection_id).",".$db->Quote($rows2->connection_id).")";
                $db->setQuery($query);
                $db->query();

                //@todo remove friend's tag too..

                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }

                return true;
            }

        }

        /**
         *@param $vars array consist of table column
         *
         */

        function setFriendsTag($vars)
        {
            $db = & $this->getDBO();

            // @todo: user db table later on
            // @todo: use joomla date function instead of NOW()

            $obj = new stdClass ();
            $obj->group_id = $vars['group_id'];
            $obj->user_id = $vars['user_id'];


            $db->insertObject('#__community_friendgroup', $obj, 'group_id');
            return true;
        }

        /**
         *Retrieve friend assigned tag
         *@param $filter array, where statement
         *@return $result obj, records
         */
        function & getFriendsTag($filter = array ())
        {
            $db = & $this->getDBO();
            $query = 'SELECT * FROM #__community_friendgroup';

            $wheres = array ();
            foreach ($filter as $column=>$value)
            {
                $wheres[] = ''.$column.'="'.$value.'"';
            }


            if (count($wheres) > 0)
            {
                $query .= ' WHERE '.implode(' AND ', $wheres);
            }

            $db->setQuery($query);

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            $result = $db->loadObjectList();
            return $result;

        }

        /**
         * Return friends with the given tag id
         */
        function getFriendsWithTag($tagid)
        {
            $db = & $this->getDBO();

            // select all frinds with the given group
            // @todo: check and make sure the given tagid belog to current user
            $query = "SELECT `user_id` FROM #__community_friendgroup WHERE `group_id`=".$db->Quote($tagid);
            $db->setQuery($query);
            $result = $db->loadObjectList();

            foreach ($result as $row)
            {
                $userid[] = $row->user_id;
            }

            // With all the list of friends, we now need to load their info
            $userids = implode(',', $userid);
            $where = array ();
            $where[] = "`id` IN ($userids)";
            $result = $this->getFiltered($where);

            return $result;
        }

        /**
         *Retrieve friend's tagsname and name
         *@param $user_id int, user id
         *@return $tagNames array, return tag names
         */
        function getFriendsTagNames($user_id)
        {
            $db = & $this->getDBO();


            $query = 'SELECT fg.*,fl.`group_name`,u.`name` FROM
					#__community_friendgroup fg
					join #__community_friendlist fl on (fg.`group_id`=fl.`group_id`)
					join #__users u on (fg.`user_id`=u.`id`)
					WHERE fg.`user_id`='.$db->Quote($user_id);


            $db->setQuery($query);

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            $rows = $db->loadObjectList();

            if (count($rows) > 0)
            {
                $tagNames = array();
				foreach ($rows as $row)
                {
                    $tagNames[$row->group_id] = $row->group_name;

                }
                return $tagNames;
            }
            else
            {
                return array ();
            }
        }



        /**
         * Get all people what are waiting to get user's approval
         * @param	id	int		userid of the user responsible for approving it
         */
        function getPending($id)
        {
			if($id == 0)
			{
				// guest obviouly hasn't send any request
				return null;
			}
			
            $db = & $this->getDBO();

            $wheres[] = 'block = 0';

            $limit = $this->getState('limit');
            $limitstart = $this->getState('limitstart');
			
            $total = $this->countPending($id);

            // Apply pagination
            if ( empty($this->_pagination))
            {
                jimport('joomla.html.pagination');
                $this->_pagination = new JPagination($total, $limitstart, $limit);
            }
			
			$query = 'SELECT b.*, a.connection_id, a.msg '
            .' FROM #__community_connection as a, #__users as b'
            .' WHERE a.`connect_to`='.$db->Quote($id)
            .' AND a.`status`=0 '
            .' AND a.`connect_from`=b.`id` '
            .' ORDER BY a.`connection_id` DESC '
            ." LIMIT {$limitstart}, {$limit} ";

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
            $result = $db->loadObjectList();
            return $result;
        }
        
        /**
         * Count total pending request.
         **/
        function countPending($id)
        {
        	$db = & $this->getDBO();
        	
        	$query = "SELECT count(*) "
            .' FROM #__community_connection as a, #__users as b'
            .' WHERE a.`connect_to`='.$db->Quote($id)
            .' AND a.`status`=0 '
            .' AND a.`connect_from`=b.`id` '
            .' ORDER BY a.`connection_id` DESC ';

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
            
            return $db->loadResult();
        }
        
        /**
         * Lets caller know if the request really belongs to the UserId
         **/
        function isMyRequest($requestId, $userId)
        {
            $db = & $this->getDBO();

            $query = 'SELECT COUNT(*) FROM '
            .$db->nameQuote('#__community_connection')
            .'WHERE '.$db->nameQuote('connection_id').'='.$db->Quote($requestId).' '
            .'AND '.$db->nameQuote('connect_to').'='.$db->Quote($userId);

            $db->setQuery($query);
            $status = ($db->loadResult() > 0)?true:false;

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            return $status;
        }

        /**
         * approve the requested friend connection
         * @param	id 	int		the connection request id
         * @return	true if everything is ok
         */
        function approveRequest($id)
        {
            $connection = array ();
            $db = & $this->getDBO();
            //get connect_from and connect_to
            $query = "SELECT `connect_from`,`connect_to`"
            ." FROM #__community_connection "
            ." WHERE `connection_id` =".$db->Quote($id);

            $db->setQuery($query);
            $conn = $db->loadObject();

            if (! empty($conn))
            {
                $connect_from = $conn->connect_from;
                $connect_to = $conn->connect_to;

                $connection[] = $connect_from;
                $connection[] = $connect_to;

                //delete connection id
                $query = "DELETE FROM #__community_connection"
                ." WHERE `connection_id`=".$db->Quote($id);

                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }


				$date	=& JFactory::getDate(); //get the time without any offset!
                //do double entry                
                //@todo escape code
                $query = "INSERT INTO #__community_connection SET"
                	. ' `connect_from`='.$db->Quote($connect_from)
                	. ', `connect_to`='.$db->Quote($connect_to)
                	. ', `status`=1'
                	. ', `created` = ' . $db->Quote($date->toMySQL());

                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }


                //@todo escape code
                $query = "INSERT INTO #__community_connection SET"
                	. ' `connect_from`='.$db->Quote($connect_to)
                	. ', `connect_to`='.$db->Quote($connect_from)
                	. ', `status`=1'
                	. ', `created` = ' . $db->Quote($date->toMySQL());

                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }

                return $connection;
            }
            else
            {
                // Return null is null
                return null;
            }
        }


        /**
         * reject the requested friend connection
         * @param	id 	int		the connection request id
         * @return	true if everything is ok
         */
        function rejectRequest($id)
        {
            $db = & $this->getDBO();

            //validating the connection id to avoid injection
            $query = "SELECT `connect_from`,`connect_to`
		          FROM #__community_connection
				  WHERE `connection_id` = ".$db->Quote($id);

            $db->setQuery($query);
            $conn = $db->loadObject();

            if (! empty($conn))
            {

                //delete connection id
                $query = "DELETE FROM #__community_connection"
                ." WHERE `connection_id`=".$db->Quote($id);

                $db->setQuery($query);
                $db->query();

                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }

                return true;
            } else
            {
                return false;
            }
        }

        /**
         * Get all request that the user has send but not yet approved
         */
        function getSentRequest($id)
        {
        	if($id == 0)
			{
				// guest obviouly hasn't send any request
				return null;
			}
			
            $db = & $this->getDBO();

			$wheres = array();
            $wheres[] = 'block = 0';

            $limit = $this->getState('limit');
            $limitstart = $this->getState('limitstart');

            $query = "SELECT count(*) "
            .' FROM #__community_connection as a, #__users as b'
            .' WHERE a.`connect_from`='.$db->Quote($id)
            .' AND a.`status`=0 '
            .' AND a.`connect_to`=b.`id` '
            .' ORDER BY a.`connection_id` DESC ';

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
            $total = $db->loadResult();

            // Appy pagination
            if ( empty($this->_pagination))
            {
                jimport('joomla.html.pagination');
                $this->_pagination = new JPagination($total, $limitstart, $limit);
            }

            $query = JString::str_ireplace('count(*)', 'b.*', $query);
            $query .= " LIMIT {$limitstart}, {$limit} ";

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
            $result = $db->loadObjectList();
            return $result;
        }

        function & getPagination()
        {
            return $this->_pagination;
        }

        /**
         * Return an array of friend id
         */
        function getFriendIds($id)
        {
        	if($id == 0)
			{
				// guest obviously has no frinds
				$fid = array();
				return $fid;
			}
			
        	$db		=& JFactory::getDBO();
			$query	= 'SELECT DISTINCT(a.connect_to) AS id  FROM ' . $db->nameQuote('#__community_connection') . ' AS a '
					. 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b '
					. 'ON a.connect_from=' . $db->Quote( $id ) . ' '
					. 'AND a.connect_to=b.id '
					. 'AND a.status=' . $db->Quote( 1 );
            $db->setQuery( $query );
            $friends	= $db->loadObjectList();
            
            $fid = array ();
            foreach ($friends as $row)
            {
                $fid[] = $row->id;
            }
            return $fid;
        }


        /**
         * Return the total number of friend for the user
         * @paran int id	the user id
         */
        function getFriendsCount($id)
        {
            // For visitor with id=0, obviously he won't have any friend!
            if ( empty($id))
            return 0;

            $db = & $this->getDBO();



            // Search those we send connection
            $query = "SELECT count(*) "
            .' FROM #__community_connection as a, #__users as b'
            .' WHERE a.`connect_from`='.$db->Quote($id)
            .' AND a.`status`=1 '
            .' AND a.`connect_to`=b.`id` '
            .' AND NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) '
            .' ORDER BY a.`connection_id` DESC ';

            $db->setQuery($query);
            $total = $db->loadResult();
            return $total;
        }



        /**
         * return the list of friend from approved connections
         * controller need to set the id
         *
         * @param	id	int		user id of the person we want to searhc their friend
         * @param	bool do we need to randomize the result
         * @param	sorted	boolean	do we need sorting?
         * @return	CUser objects
         */
        function & getFriends($id, $sorted = 'latest', $useLimit = true , $filter = 'all' , $maxLimit = 0 )
        {
            $cusers = array ();
			
			// Deprecated since 1.8 .
			// Earlier versions the default $filter is empty but since we will now need to handle character filter,
			// we need to set the default to 'all'
			if( empty($filter) )
			{
				$filter	= 'all';
			}
		
            // For visitor with id=0, obviously he won't have any friend!
            if ( empty($id))
            {
                return $cusers;
            }

            $db = & $this->getDBO();

            $wheres = array ();
            $wheres[] = 'block = 0';
            $limit = $this->getState('limit');
            $limitstart = $this->getState('limitstart');

			$query	= 'SELECT DISTINCT(a.connect_to) AS id ';
			if($filter == 'suggestion')
			{
				$query	= 'SELECT DISTINCT(b.connect_to) AS id ';
			}
			$query	.= ', CASE WHEN c.userid IS NULL THEN 0 ELSE 1 END AS online';
			
			switch( $filter )
			{
                case 'mutual':
                	$user	= CFactory::getUser();
					
					$query	.= ' FROM ' . $db->nameQuote( '#__community_connection' ) . ' AS a '
							. 'INNER JOIN ' . $db->nameQuote( '#__community_connection' ) . ' AS b ON ( a.connect_to = b.connect_to ) '
							. 'AND a.connect_from=' . $db->Quote( $id ) . ' '
							. 'AND b.connect_from=' . $db->Quote( $user->id ) . ' '
							. 'AND a.status=' . $db->Quote( '1' );
					$query	.= ' LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON a.connect_to = c.userid';
					$query  .= ' WHERE NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) ';

		            // Search those we send connection
		            $pagingQuery = "SELECT count(*) "
		            .' FROM #__community_connection as a, #__users as b'
		            .' WHERE a.`connect_from`='.$db->Quote($id)
		            .' AND a.`status`=1 '
		            .' AND a.`connect_to`=b.`id` '
		            .' AND NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) '
		            .' ORDER BY a.`connection_id` DESC ';
		
		            $db->setQuery($pagingQuery);
		            $total = $db->loadResult();
		
		            // Appy pagination
		            if ( empty($this->_pagination))
		            {
		                jimport('joomla.html.pagination');
		                $this->_pagination = new JPagination($total, $limitstart, $limit);
		            }
                	break;
                case 'suggestion':
                	$user	= CFactory::getUser();
					$query	.= ', COUNT(1) AS totalFriends, b.connect_to AS id'
							. ' FROM ' . $db->nameQuote( '#__community_connection' ) . ' AS b'
							. ' LEFT JOIN '. $db->nameQuote('#__session') . ' AS c ON c.`userid` = b.`connect_to`'
							. ' WHERE b.`connect_to` != ' . $db->Quote( $user->id )
							. ' AND b.`connect_from` IN (SELECT a.`connect_to` FROM ' . $db->nameQuote( '#__community_connection' ) . ' a WHERE a.`connect_from` = ' . $db->Quote( $user->id ) . ' AND a.status = ' . $db->Quote( '1' ) . ')'
							. ' AND NOT EXISTS(SELECT d.`connect_to` FROM `#__community_connection` d WHERE (d.`connect_to` = ' . $db->Quote( $user->id ) . ' AND d.`connect_from` = b.`connect_to`) OR (d.`connect_to` = b.`connect_to` AND d.`connect_from` = ' . $db->Quote( $user->id ) . ') )'
					        . ' AND NOT EXISTS ( SELECT e.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS e WHERE e.`userid` = ' . $db->Quote( $id ) . ' AND e.`blocked_userid` = b.`connect_to`) ';
		            
                    // Search those we send connection
		            $pagingQuery = "SELECT count(*) "
		            .' FROM #__community_connection as a, #__users as b'
		            .' WHERE a.`connect_from`='.$db->Quote($id)
		            .' AND a.`status`=1 '
		            .' AND a.`connect_to`=b.`id` '
		            .' AND NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) '
		            .' ORDER BY a.`connection_id` DESC ';
		
		            $db->setQuery($pagingQuery);
		            $total = $db->loadResult();
		
		            // Appy pagination
		            if ( empty($this->_pagination))
		            {
		                jimport('joomla.html.pagination');
		                $this->_pagination = new JPagination($total, $limitstart, $limit);
		            }
                	break;
                case 'all':
                	$query	.= ', b.name';
					$query	.= ' FROM ' . $db->nameQuote( '#__community_connection' ) . ' AS a '
							. 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b '
							. 'ON a.connect_from=' . $db->Quote( $id ) . ' '
							. 'AND a.connect_to=b.id '
							. 'AND a.status=' . $db->Quote( '1' ) . ' '
					        . 'LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON a.connect_to = c.userid '
					        . 'WHERE NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) ';
					
		            // Search those we send connection
		            $pagingQuery = "SELECT count(*) "
		            .' FROM #__community_connection as a, #__users as b'
		            .' WHERE a.`connect_from`='.$db->Quote($id)
		            .' AND a.`status`=1 '
		            .' AND a.`connect_to`=b.`id` '
		            .' AND NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) '
		            .' ORDER BY a.`connection_id` DESC ';
		                                                        
		            $db->setQuery($pagingQuery);
		            $total = $db->loadResult();
		
		            // Appy pagination
		            if ( empty($this->_pagination))
		            {
		                jimport('joomla.html.pagination');
		                $this->_pagination = new JPagination($total, $limitstart, $limit);
		            }
                    break;
                default:
					$filterCount	= JString::strlen( $filter );
					
					$filterQuery	= '';
					
					if( $filter == 'others' )
					{
						$filterQuery	= ' AND b.name REGEXP "^[^a-zA-Z]."';
					}
					else
					{
						$filterQuery	= ' AND(';
						for( $i = 0; $i < $filterCount; $i++ )
						{
							$char		= $filter{$i};
							$filterQuery	.= $i != 0 ? ' OR ' : ' ';
							$filterQuery	.= 'b.name LIKE "' . JString::strtoupper($char) . '%" OR b.name LIKE "' . JString::strtolower($char) . '%"'; 
						}
						$filterQuery	.= ')';
					}
					
                	$query	.= ', b.name';
					$query	.= ' FROM ' . $db->nameQuote( '#__community_connection' ) . ' AS a '
							. 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b '
							. 'ON a.connect_from=' . $db->Quote( $id ) . ' '
							. 'AND a.connect_to=b.id '
							. 'AND a.status=' . $db->Quote( '1' );
					$query	.= $filterQuery;
					$query	.= ' LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON a.connect_to = c.userid';
					$query  .= ' WHERE NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) ';

		            // Search those we send connection
		            $pagingQuery = "SELECT count(*) "
		            .' FROM #__community_connection as a, #__users as b'
		            .' WHERE a.`connect_from`='.$db->Quote($id)
		            .' AND a.`status`=1 '
		            .' AND a.`connect_to`=b.`id` '
		            . $filterQuery
		            .' AND NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) '
		            .' ORDER BY a.`connection_id` DESC ';
		
		            $db->setQuery($pagingQuery);
		            $total = $db->loadResult();
		
		            // Appy pagination
		            if ( empty($this->_pagination))
		            {
		                jimport('joomla.html.pagination');
		                $this->_pagination = new JPagination($total, $limitstart, $limit);
		            }
                	break;
			}
			
            switch($sorted)
            {
                // We only want the id since we use CFactory::getUser later to get their full details.
                case 'online':                		
						$query	.= ' ORDER BY online DESC';
                    break;
                case 'suggestion':
						$query	.=	' GROUP BY (b.`connect_to`)'
								. ' HAVING (totalFriends >= ' . FRIEND_SUGGESTION_LEVEL . ')';
								
                    break;
                case 'name':
            		//sort by name only applicable to filter is not mutual and suggestion
            		if($filter != 'mutual' && $filter != 'suggestion')
						$query	.= ' ORDER BY b.name ASC';
					break;	
                default:
						$query	.= ' ORDER BY a.connection_id DESC';
                    break;
            }

            if ($useLimit)
            {
                $query .= " LIMIT {$limitstart}, {$limit} ";
            }
            else if ($maxLimit > 0)
            {
            	// we override the limit by specifying how many return need to be return.
            	$query .= " LIMIT 0, {$maxLimit} ";
            }

            $db->setQuery($query);

            $result = $db->loadObjectList();

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
			
			// preload all users
			$uids = array();
			foreach($result as $m)
			{
				$uids[] = $m->id;
			}
			CFactory::loadUsers($uids);
			
            for ($i = 0; $i < count($result); $i++)
            {

                $usr = CFactory::getUser($result[$i]->id);
                $cusers[] = $usr;
            }

            return $cusers;
        }

        /**
         * return the list of friends group
         * @param id int user id of that person we want to search for their friend group
         *
         */

        function getFriendsGroup($id)
        {
            $db = & $this->getDBO();

            // Search those we send connection
            $query = 'SELECT * 
				FROM #__community_friendlist
				WHERE `user_id` = '.$db->Quote($id);

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            $result = $db->loadObjectList();

            return $result;
        }

        /**
         *get Friend Connection
         *
         *@param connect_from int owner's id
         *@param connect_to stranger's id
         *return db object
         */

        function getFriendConnection($connect_from, $connect_to)
        {

            $db = & $this->getDBO();

            $query = "SELECT * FROM #__community_connection
		        WHERE (`connect_from` = ".$db->Quote($connect_from)." AND `connect_to` =".$db->Quote($connect_to).")
				OR ( `connect_from` = ".$db->Quote($connect_to)." AND `connect_to` =".$db->Quote($connect_from).")";

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());

            }

            $result = $db->loadObjectList();

            return $result;
        }
        
        function getPendingUserId($id)
        {
			$db = & $this->getDBO();

            $query = "	SELECT `connect_from`
		          		FROM #__community_connection
				  		WHERE `connection_id` = ".$db->Quote($id);

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());

            }

            $result = $db->loadObject();

            return $result;
		}

    }//end of class
