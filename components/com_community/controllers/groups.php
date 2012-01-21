<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 *
 */
class CommunityGroupsController extends CommunityBaseController
{
	public function editGroupWall( $wallId )
	{
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'helpers' , 'time');
		
		$wall			=& JTable::getInstance( 'Wall' , 'CTable' );
		$wall->load( $wallId );

		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $wall->contentid );
		
		$model			=& CFactory::getModel( 'Groups' );
		$my				=& CFactory::getUser();

		// @rule: We only allow editing of wall in 15 minutes
		$now		= JFactory::getDate();
		$interval	= CTimeHelper::timeIntervalDifference( $wall->date , $now->toMySQL() );
		$interval	= abs( $interval );
		
		if( ( $model->isAdmin( $my->id , $group->id ) || COwnerHelper::isCommunityAdmin() || $my->id == $wall->post_by ) && ( COMMUNITY_WALLS_EDIT_INTERVAL > $interval ) )
		{
			return true;
		}
		return false;
	}
	
	public function editDiscussionWall( $wallId )
	{
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'helpers' , 'time');
		
		$wall			=& JTable::getInstance( 'Wall' , 'CTable' );
		$wall->load( $wallId );
		
		$discussion		=& JTable::getInstance( 'Discussion' , 'CTable' );
		$discussion->load( $wall->contentid );
		
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $discussion->groupid );
		
		$model			=& CFactory::getModel( 'Groups' );
		$my				=& CFactory::getUser();

		
		// @rule: We only allow editing of wall in 15 minutes
		$now		= JFactory::getDate();
		$interval	= CTimeHelper::timeIntervalDifference( $wall->date , $now->toMySQL() );
		$interval	= abs( $interval );
		
		if( ( $model->isAdmin( $my->id , $group->id ) || COwnerHelper::isCommunityAdmin() || $my->id == $wall->post_by ) && ( COMMUNITY_WALLS_EDIT_INTERVAL > $interval ) )
		{
			return true;
		}
		return false;
	}
	
    public function ajaxRemoveFeatured( $groupId )
    {
    	$objResponse	= new JAXResponse();
    	CFactory::load( 'helpers' , 'owner' );
		
		if( COwnerHelper::isCommunityAdmin() )
    	{
			$model	=& CFactory::getModel('Featured');

    		CFactory::load( 'libraries' , 'featured' );
    		$featured	= new CFeatured(FEATURED_GROUPS);
    		$my			= CFactory::getUser();
    		
    		if($featured->delete($groupId))
    		{
    			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC GROUP REMOVED FROM FEATURED'));	
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC ERROR REMOVING GROUP FROM FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons   = '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
    public function ajaxAddFeatured( $groupId )
    {
    	$objResponse	= new JAXResponse();
    	CFactory::load( 'helpers' , 'owner' );
		
		if( COwnerHelper::isCommunityAdmin() )
    	{
			$model	=& CFactory::getModel('Featured');
			
			if( !$model->isExists( FEATURED_GROUPS , $groupId ) )
			{
	    		CFactory::load( 'libraries' , 'featured' );
	    		CFactory::load( 'models', 'groups' );
	    		
	    		$featured	= new CFeatured( FEATURED_GROUPS );
	    		$table		=& JTable::getInstance( 'Group' , 'CTable' );
	    		$table->load( $groupId );
	    		$my			= CFactory::getUser();
	    		$featured->add( $groupId , $my->id );
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::sprintf('CC GROUP IS FEATURED', $table->name ));
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC GROUP ALREADY FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons   = '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
	/**
	 * Method is called from the reporting library. Function calls should be
	 * registered here.
	 *
	 * return	String	Message that will be displayed to user upon submission.
	 **/	 	 	
	public function reportDiscussion( $link, $message , $discussionId )
	{
		CFactory::load( 'libraries' , 'reporting' );
		$report = new CReportingLibrary();
		
		$report->createReport( JText::_('CC INVALID DISCUSSION') , $link , $message );

		$action					= new stdClass();
		$action->label			= 'Remove discussion';
		$action->method			= 'groups,removeDiscussion';
		$action->parameters		= $discussionId;
		$action->defaultAction	= true;
		
		$report->addActions( array( $action ) );
		
		return JText::_('CC REPORT SUBMITTED');
	}
	
	public function removeDiscussion( $discussionId )
	{
		$model		=& CFactory::getModel('groups');
		$my			= CFactory::getUser();
		
		if( $my->id == 0 )
		{
			return $this->blockUnregister();
		}
		
		CFactory::load( 'models' , 'discussions' );
		$discussion	=& JTable::getInstance( 'Discussion' , 'CTable' );
		
		$discussion->load( $discussionId );
		$discussion->delete();
		
		return JText::_('CC DISCUSSION REMOVED');
	}
	
	/**
	 * Method is called from the reporting library. Function calls should be
	 * registered here.
	 *
	 * return	String	Message that will be displayed to user upon submission.
	 **/	 	 	
	public function reportGroup( $link, $message , $groupId )
	{
		CFactory::load( 'libraries' , 'reporting' );
		$report = new CReportingLibrary();
		
		$report->createReport( JText::_('Bad group') , $link , $message );

		$action					= new stdClass();
		$action->label			= 'Unpublish group';
		$action->method			= 'groups,unpublishGroup';
		$action->parameters		= $groupId;
		$action->defaultAction	= true;
		
		$report->addActions( array( $action ) );
		
		return JText::_('CC REPORT SUBMITTED');
	}
	
	public function unpublishGroup( $groupId )
	{	
		CFactory::load( 'models' , 'groups' );
		
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		$group->published	= '0';
		$group->store();
		
		return JText::_('CC GROUP IS UNPUBLISHED');
	}
	
	/**
	 * Displays the default groups view
	 **/
	public function display()
	{
		$config	=& CFactory::getConfig();

		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}
		$my			=& JFactory::getUser();
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType);

 		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Full application view
	 */
	public function app()
	{
		$view	=& $this->getView('groups');

		echo $view->get( 'appFullView' );
	}

	/**
	 * Full application view for discussion
	 */
	public function discussApp()
	{
		$view	=& $this->getView('groups');

		echo $view->get( 'discussAppFullView' );
	}

	public function ajaxAcceptInvitation( $groupId )
	{
		$response	= new JAXResponse();
		$my			= CFactory::getUser();
		$table		=& JTable::getInstance( 'GroupInvite' , 'CTable' );
		$table->load( $groupId , $my->id );
		
		if( !$table->isOwner() )
		{
			$response->addScriptCall( 'CC INVALID ACCESS' );
			return $response->sendResponse();
		}

		$this->_saveMember( $groupId );
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $table->groupid );
		$url	= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );
		$response->addScriptCall( "joms.jQuery('#groups-invite-" . $groupId . "').html('<span class=\"community-invitation-message\">" . JText::sprintf('CC GROUP INVITATION ACCEPTED', $group->name , $url ) . "</span>')");

		return $response->sendResponse();
	}
	
	public function ajaxRejectInvitation( $groupId )
	{
		$response	= new JAXResponse();
		$my			= CFactory::getUser();
		$table		=& JTable::getInstance( 'GroupInvite' , 'CTable' );
		$table->load( $groupId , $my->id );
		
		if( !$table->isOwner() )
		{
			$response->addScriptCall( 'CC INVALID ACCESS' );
			return $response->sendResponse();
		}
		
		if( $table->delete() )
		{
			$group	=& JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $table->groupid );
			$url	= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );
			$response->addScriptCall( "joms.jQuery('#groups-invite-" . $groupId . "').html('<span class=\"community-invitation-message\">" . JText::sprintf('CC GROUP INVITATION REJECTED', $group->name , $url ) . "</span>')");
		}

		return $response->sendResponse();
	}
		
	/**
	 *  Ajax function to unpublish a group
	 *
	 * @param	$groupId	The specific group id to unpublish
	 **/
	public function ajaxUnpublishGroup( $groupId )
	{
		$response	= new JAXResponse();

		CFactory::load( 'helpers' , 'owner' );

		if( !COwnerHelper::isCommunityAdmin() )
		{
			$response->addScriptCall( 'alert' , JText::_('CC NOT ALLOWED TO UNPUBLISH GROUP'));
		}
		else
		{
			CFactory::load( 'models' , 'groups' );

			$group	=& JTable::getInstance( 'Group' , 'CTable' );
			$group->load( $groupId );

			if( $group->id == 0 )
			{
				$response->addScriptCall( 'alert' , JText::_('CC INVALID GROUP ID'));
			}
			else
			{
				$group->published	= 0;

				if( $group->store() )
				{
					$html	= '<div class=\"warning\">' . JText::_('CC GROUP UNPUBLISHED') . '</div>';
					$response->addScriptCall('joms.jQuery("#community-wrap .group .warning").remove();');
					$response->addScriptCall('joms.jQuery("' . $html . '").prependTo("#community-wrap .group");');
					$response->addScriptCall('joms.jQuery("#community-wrap .group").css("border","3px solid red");');

					//trigger for onGroupDisable
					$this->triggerGroupEvents( 'onGroupDisable' , $group);
				}
				else
				{
					$response->addScriptCall( 'alert' , JText::_('CC ERROR WHILE SAVING GROUP') );
				}
			}
		}

		return $response->sendResponse();
	}

	/**
	 *  Ajax function to delete a group
	 *
	 * @param	$groupId	The specific group id to unpublish
	 **/
	public function ajaxDeleteGroup( $groupId, $step=1 )
	{
		$response	= new JAXResponse();

		CFactory::load( 'libraries' , 'activities' );
		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'groups' );
		
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		
		$groupModel		=& CFactory::getModel( 'groups' );		
		$membersCount	= $groupModel->getMembersCount($groupId);	
		$my				= CFactory::getUser();
		$isMine			= ($my->id == $group->ownerid);		
		
		if( !COwnerHelper::isCommunityAdmin() && !($isMine && $membersCount<=1))
		{
			$content = JText::_('CC NOT ALLOWED TO DELETE GROUP');
			$buttons  = '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('CC CANCEL') . '"/>';
			$response->addScriptCall('cWindowResize', 100);
			$response->addAssign('cWindowContent', 'innerHTML' , $content);
			$response->addScriptCall('cWindowActions', $buttons);
		}
		else
		{
			$response->addScriptCall('cWindowResize', 160);
			
			$doneMessage	= ' - <span class=\'success\'>'.JText::_('CC DONE').'</span><br />';
			$failedMessage	= ' - <span class=\'failed\'>'.JText::_('CC FAILED').'</span><br />';
			
			switch($step)
			{
				case 1:
					// Nothing gets deleted yet. Just show a messge to the next step					
					if( empty($groupId) )
					{
						$content = JText::_('CC INVALID GROUP ID');
					}
					else
					{
						$content	= '<strong>' . JText::sprintf( 'CC DELETING GROUP' , $group->name ) . '</strong><br/>';
						$content .= JText::_('CC DELETING GROUP BULLETIN');
						
						$response->addScriptCall('jax.call(\'community\', \'groups,ajaxDeleteGroup\', \''.$groupId.'\', 2);' );
						
						//trigger for onBeforeGroupDelete			
						$this->triggerGroupEvents( 'onBeforeGroupDelete' , $group);
					}
					$response->addAssign('cWindowContent', 'innerHTML' , $content);
					break;
				case 2:
					// Delete all group bulletins
					if(CommunityModelGroups::deleteGroupBulletins($groupId))
					{
						$content = $doneMessage;
					}
					else
					{
						$content = $failedMessage;
					}
					$content .= JText::_('CC DELETING GROUP MEMBERS');
					$response->addScriptCall('joms.jQuery("#cWindowContent").append("' . $content . '");' );
					$response->addScriptCall('jax.call(\'community\', \'groups,ajaxDeleteGroup\', \''.$groupId.'\', 3);' );			
					break;
				case 3:
					// Delete all group members
					if(CommunityModelGroups::deleteGroupMembers($groupId))
					{	
						$content = $doneMessage;
					}
					else
					{
						$content = $failedMessage;
					}
					$content .= JText::_('CC DELETING GROUP WALLS'); 
					$response->addScriptCall('joms.jQuery("#cWindowContent").append("' . $content . '");' );
					$response->addScriptCall('jax.call(\'community\', \'groups,ajaxDeleteGroup\', \''.$groupId.'\', 4);' );			
					break;
				case 4:
					// Delete all group wall
					if(CommunityModelGroups::deleteGroupWall($groupId))
					{
						$content = $doneMessage;
					}
					else
					{
						$content = $failedMessage;
					}
					$content .= JText::_('CC DELETING GROUP DISCUSSIONS');
					$response->addScriptCall('joms.jQuery("#cWindowContent").append("' . $content . '");' );
					$response->addScriptCall('jax.call(\'community\', \'groups,ajaxDeleteGroup\', \''.$groupId.'\', 5);' );			
					break;
				case 5:
					// Delete all group discussions
					if(CommunityModelGroups::deleteGroupDiscussions($groupId))
					{
						$content = $doneMessage;
					}
					else
					{
						$content = $failedMessage;
					}
					$content .= JText::_('CC DELETING GROUP MEDIA');
					$response->addScriptCall('joms.jQuery("#cWindowContent").append("' . $content . '");' );
					$response->addScriptCall('jax.call(\'community\', \'groups,ajaxDeleteGroup\', \''.$groupId.'\', 6);' );			
					break;
				case 6:
					// Delete all group's media files
					if(CommunityModelGroups::deleteGroupMedia($groupId))
					{
						$content = $doneMessage;
					}
					else
					{
						$content = $failedMessage;
					}
					$response->addScriptCall('joms.jQuery("#cWindowContent").append("' . $content . '");' );
					$response->addScriptCall('jax.call(\'community\', \'groups,ajaxDeleteGroup\', \''.$groupId.'\', 7);' );			
					break;					
					
				case 7:
					// Delete group
					$group	=& JTable::getInstance( 'Group' , 'CTable' );
					$group->load( $groupId );
					$groupData = $group;
					
					if( $group->delete( $groupId ) )
					{
						CFactory::load( 'libraries' , 'featured' );
			    		$featured	= new CFeatured(FEATURED_GROUPS);
			    		$featured->delete($groupId);
						
						jimport( 'joomla.filesystem.file' );
						
						//@rule: Delete only thumbnail and avatars that exists for the specific group
						if($groupData->avatar != "components/com_community/assets/group.jpg" && !empty($groupData->avatar))
						{
							$path = explode('/', $groupData->avatar);
							$file = JPATH_ROOT . DS . $path[0] . DS . $path[1] . DS . $path[2] .DS . $path[3];
							if(JFile::exists($file))
							{
								JFile::delete($file);
							}
						}

						if($groupData->thumb != "components/com_community/assets/group_thumb.jpg" && !empty($groupData->thumb))
						{
							$path = explode('/', $groupData->thumb);
							$file = JPATH_ROOT . DS . $path[0] . DS . $path[1] . DS . $path[2] .DS . $path[3];
							if(JFile::exists($file))
							{
								JFile::delete($file);
							}
						}						
						
						$html	= '<div class=\"info\" style=\"display: none;\">' . JText::_('CC GROUP DELETED') . '</div>';
						$response->addScriptCall('joms.jQuery("' . $html . '").prependTo("#community-wrap").fadeIn();');
						$response->addScriptCall('joms.jQuery("#community-groups-wrap").fadeOut();');
												
						$content = JText::_('CC GROUP DELETED');						
					
						//trigger for onGroupDelete			
						$this->triggerGroupEvents( 'onAfterGroupDelete' , $groupData);
						 
						// Remove from activity stream
						CActivityStream::remove('groups', $groupId);
					}
					else
					{
						$content = JText::_('CC ERROR WHILE DELETING GROUP');
					}
					$redirect = CRoute::_(JURI::root().'index.php?option=com_community&view=groups');	
					$buttons  = '<input type="button" class="button" id="groupDeleteDone" onclick="cWindowHide(); window.location=\''.$redirect.'\';" value="' . JText::_('Done') . '"/>';
															
					$response->addAssign('cWindowContent', 'innerHTML' , $content);
					$response->addScriptCall('cWindowActions', $buttons);
					$response->addScriptCall('cWindowResize', 100);
					break;
				default:
					break;
			}
		}
		
		return $response->sendResponse();
	}
	
	/**
	 *  Ajax function to prompt warning during group deletion
	 *
	 * @param	$groupId	The specific group id to unpublish
	 **/
	public function ajaxWarnGroupDeletion( $groupId )
	{
		$response	= new JAXResponse();
		
		$title      = JText::_('CC DELETE GROUP');
		$content 	= JText::_('CC GROUP DELETION WARNING');
		$buttons	= '<input type="button" class="button" onclick="jax.call(\'community\', \'groups,ajaxDeleteGroup\', \''.$groupId.'\', 1);" value="' . JText::_('CC DELETE') . '"/>';
		$buttons   .= '<input type="button" class="button" onclick="cWindowHide();" value="' . JText::_('CC BUTTON CANCEL') . '"/>';
		
		$response->addAssign('cWindowContent', 'innerHTML' , $content);
		$response->addScriptCall( 'cWindowActions' , $buttons );
		$response->addAssign('cwin_logo', 'innerHTML', $title);

		return $response->sendResponse();
	}

	/**
	 * Ajax function to remove a reply from the discussions
	 *
	 * @params $discussId	An string that determines the discussion id
	 **/
	public function ajaxRemoveReply( $wallId )
	{
		CError::assert($wallId , '', '!empty', __FILE__ , __LINE__ );

		$response	= new JAXResponse();
		
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}

		//@rule: Check if user is really allowed to remove the current wall
		$my			= CFactory::getUser();
		$model		=& $this->getModel( 'wall' );
		$wall		= $model->get( $wallId );
		CFactory::load( 'models' , 'discussions' );
		
		$discussion	=& JTable::getInstance( 'Discussion' , 'CTable' );
		$discussion->load( $wall->contentid );
		
		$groupModel	=& CFactory::getModel( 'groups' );
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $discussion->groupid );
		
		CFactory::load( 'helpers' , 'owner' );

		if( !COwnerHelper::isCommunityAdmin() && !$groupModel->isAdmin( $my->id , $group->id ) )
		{
			$response->addScriptCall( 'alert' , JText::_('CC NOT ALLOWED TO REMOVE WALL') );
		}
		else
		{
			if( !$model->deletePost( $wallId ) )
			{
				$response->addAlert( JText::_('CC CANNOT REMOVE WALL') );
			} 
			else
			{
				//add user points
				if($wall->post_by != 0)
				{
					CFactory::load( 'libraries' , 'userpoints' );		
					CUserPoints::assignPoint('wall.remove', $wall->post_by);
				}				
			}
		}

		return $response->sendResponse();
	}

	/**
	 * Ajax function to display the remove bulletin information
	 **/
	public function ajaxShowRemoveBulletin( $groupid , $bulletinId )
	{
		$response	= new JAXResponse();

		ob_start();
?>
		<div id="community-groups-join">
			<p>
				<?php echo JText::_('CC REMOVE GROUP BULLETIN QUESTION');?>
			</p>
		</div>
<?php
		$contents	= ob_get_contents();
		ob_end_clean();

		$buttons	= '<form name="jsform-groups-ajaxshowremovebulletin" method="post" action="' . CRoute::_('index.php?option=com_community&view=groups&task=deleteBulletin') . '">';
		$buttons	.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" name="Submit"/>';
		$buttons	.= '<input type="hidden" value="' . $groupid . '" name="groupid" />';
		$buttons	.= '<input type="hidden" value="' . $bulletinId . '" name="bulletinid" />';
		$buttons	.= '&nbsp;';
		$buttons	.= '<input onclick="cWindowHide();return false" type="button" value="' . JText::_('CC BUTTON NO') . '" class="button" name="Submit"/>';
		$buttons	.= '</form>';

		$response->addAssign('cWindowContent' , 'innerHTML' , $contents);
		$response->addScriptCall('cWindowActions', $buttons);

		return $response->sendResponse();
	}

	/**
	 * Ajax function to display the remove discussion information
	 **/
	public function ajaxShowRemoveDiscussion( $groupid , $topicid )
	{
		$response	= new JAXResponse();

		ob_start();
?>
		<div id="community-groups-join">
			<p>
				<?php echo JText::_('CC REMOVE GROUP DISCUSSION QUESTION');?>
			</p>
		</div>
<?php
		$contents	= ob_get_contents();
		ob_end_clean();

		$buttons	= '<form name="jsform-groups-ajaxshowremovediscussion" method="post" action="' . CRoute::_('index.php?option=com_community&view=groups&task=deleteTopic') . '">';
		$buttons	.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" name="Submit"/>';
		$buttons	.= '<input type="hidden" value="' . $groupid . '" name="groupid" />';
		$buttons	.= '<input type="hidden" value="' . $topicid . '" name="topicid" />';
		$buttons	.= '&nbsp;';
		$buttons	.= '<input onclick="cWindowHide();return false" type="button" value="' . JText::_('CC BUTTON NO') . '" class="button" name="Submit"/>';
		$buttons	.= '</form>';

		$response->addAssign('cWindowContent' , 'innerHTML' , $contents);
		$response->addScriptCall('cWindowActions', $buttons);

		return $response->sendResponse();
	}

	/**
	 * Ajax function to approve a specific member
	 *
	 * @params	string	id	The member's id that needs to be approved.
	 * @params	string	groupid	The group id that the user is in.
	 **/
	public function ajaxApproveMember( $memberId , $groupId )
	{
		$response	= new JAXResponse();

		$my			= CFactory::getUser();
		$model		= $this->getModel( 'groups' );

		CFactory::load( 'helpers' , 'owner' );

		if( !$model->isAdmin( $my->id , $groupId ) && !COwnerHelper::isCommunityAdmin() )
		{
			$response->addScriptCall( JText::_('CC NOT ALLOWED TO ACCESS SECTION') );
		}
		else
		{
			// Load required tables
			$member		=& JTable::getInstance( 'GroupMembers' , 'CTable' );
			$group		=& JTable::getInstance( 'Group' , 'CTable' );

			// Load the group and the members table
			$group->load( $groupId );
			$member->load( $memberId , $groupId );
			
			// Only approve members that is really not approved yet.
			if( $member->approved )
			{
				$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC MEMBER ALREADY APPROVED') . '");');
			}
			else
			{
				$member->approve();
				
				$user	= CFactory::getUser( $memberId );

				// Add notification
				CFactory::load( 'libraries' , 'notification' );

				$params			= new JParameter( '' );
				$params->set('url' , 'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id );
				$params->set('group' , $group->name );
	
				CNotificationLibrary::add( 'groups.member.approved' , $group->ownerid , $user->id , JText::sprintf( 'CC GROUP MEMBER APPROVED EMAIL SUBJECT' , $group->name ) , '' , 'groups.memberapproved' , $params );
				
				$act = new stdClass();
				$act->cmd 		= 'group.join';
				$act->actor   	= $memberId;
				$act->target  	= 0;
				$act->title	  	= JText::sprintf('CC ACTIVITIES MEMBER JOIN GROUP' , '{group_url}' , $group->name );
				$act->content	= '';
				$act->app		= 'groups';
				$act->cid		= $group->id;
				
				$params = new JParameter('');
				$params->set( 'action' , 'groups.join');
				$params->set( 'group_url', CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );
	
				// Add activity logging
				CFactory::load ( 'libraries', 'activities' );
				CActivityStream::add( $act, $params->toString() );
				
				//add user points
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('group.join', $memberId);			
	
				$response->addScriptCall('joms.jQuery("#member_' . $memberId . '").css("border","3px solid blue");');
				$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC MEMBER APPROVED') . '");');
				$response->addScriptCall('joms.jQuery("#notice").attr("class","info");');
				$response->addScriptCall('joms.jQuery("#groups-approve-' . $memberId . '").remove();');
				
				//trigger for onGroupJoinApproved
				$this->triggerGroupEvents( 'onGroupJoinApproved' , $group , $memberId);	
				
				// UPdate group stats();
				$group->updateStats();
				$group->store();		
			}
		}
		return $response->sendResponse();
	}

	/**
	 * Ajax method to remove specific member
	 *
	 * @params	string	id	The member's id that needs to be approved.
	 * @params	string	groupid	The group id that the user is in.
	 **/
	public function ajaxRemoveMember( $memberId , $groupId )
	{
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}

		$response	= new JAXResponse();

		$model		=& $this->getModel( 'groups' );
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );

		$my			= CFactory::getUser();
		
		CFactory::load( 'helpers' , 'owner' );
		
		if( $group->ownerid != $my->id || !COwnerHelper::isCommunityAdmin() )
		{
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC PERMISSION DENIED') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","error");');			
		}
		
		if( $group->ownerid == $memberId )
		{
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC YOU ARE NOT ALLOWED TO REMOVE YOURSELF') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","error");');
		}
		else
		{
			$groupMember	=& JTable::getInstance( 'GroupMembers' , 'CTable' );
			$groupMember->load( $memberId , $groupId );

			$data		= new stdClass();

			$data->groupid	= $groupId;
			$data->memberid	= $memberId;

			$model->removeMember($data);
			
			//add user points
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('group.member.remove', $memberId);			
			
			$response->addScriptCall('joms.jQuery("#member_' . $memberId . '").css("border","1px solid red");');
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC GROUP MEMBER REMOVED') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","info");');
			
			//trigger for onGroupLeave
			$this->triggerGroupEvents( 'onGroupLeave' , $group , $memberId);
		}

		// Store the group and update the data
		$group->updateStats();
		$group->store();
		return $response->sendResponse();
	}

	/**
	 * Ajax method to display HTML codes to leave group
	 *
	 * @params	string	id	The member's id that needs to be approved.
	 * @params	string	groupid	The group id that the user is in.
	 **/
	public function ajaxShowLeaveGroup( $groupId )
	{
		$response	= new JAXResponse();

		$model		=& $this->getModel( 'groups' );
		$my			=& JFactory::getUser();

		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );

		ob_start();
?>
		<div id="community-groups-join">
			<p><?php echo JText::_('CC CONFIRM LEAVE GROUP');?> <strong><?php echo $group->name; ?></strong>?</p>
		</div>
<?php
		$contents	= ob_get_contents();
		ob_end_clean();

		$buttons	= '<form name="jsform-groups-ajaxshowleavegroup" method="post" action="' . CRoute::_('index.php?option=com_community&view=groups&task=leavegroup') . '">';
		$buttons	.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" name="Submit"/>';
		$buttons	.= '<input type="hidden" value="' . $groupId . '" name="groupid" />';
		$buttons	.= '<input onclick="cWindowHide();return false" type="button" value="' . JText::_('CC BUTTON NO') . '" class="button" name="Submit"/>';
		$buttons	.= '</form>';

		// Change cWindow title
		$response->addAssign('cwin_logo', 'innerHTML', JText::_('CC LEAVE GROUP TITLE'));
		$response->addAssign('cWindowContent' , 'innerHTML' , $contents);
		$response->addScriptCall('cWindowActions', $buttons);
		return $response->sendResponse();
	}

	/**
	 * Ajax function to display the join group
	 *
	 * @params $groupid	A string that determines the group id
	 **/
	public function ajaxShowJoinGroup( $groupId , $redirectUrl)
	{
		if (!COwnerHelper::isRegisteredUser()) 
		{
			return $this->ajaxBlockUnregister();
		}

		$response	= new JAXResponse();

		$model		=& $this->getModel( 'groups' );
		$my			= CFactory::getUser();
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );

		$members	= $model->getMembersId( $groupId );

		ob_start();
		?>
		<div id="community-groups-join">
			<?php if( in_array( $my->id , $members ) ): ?>
			<?php
			$buttons	= '<input onclick="cWindowHide();" type="submit" value="' . JText::_('CC BUTTON CLOSE') . '" class="button" name="Submit"/>';
			?>
				<p><?php echo JText::_('CC ALREADY MEMBER OF GROUP'); ?></p>
			<?php else: ?>
			<?php
			$buttons	= '<form name="jsform-groups-ajaxshowjoingroup" method="post" action="' . CRoute::_('index.php?option=com_community&view=groups&task=joingroup') . '">';
			$buttons	.= '<input type="submit" value="' . JText::_('CC BUTTON YES') . '" class="button" name="Submit"/>';
			$buttons	.= '<input type="hidden" value="' . $groupId . '" name="groupid" />';   
			$buttons	.= '<input onclick="cWindowHide();" type="button" value="' . JText::_('CC BUTTON NO') . '" class="button" name="Submit" />';
			$buttons	.= '</form>';
			?>
				<p>
					<?php echo JText::sprintf('CC CONFIRM JOIN GROUP', $group->name );?>
				</p>
			<?php endif; ?>
		</div>
		<?php

		$contents	= ob_get_contents();
		ob_end_clean();

		// Change cWindow title
		$response->addAssign('cwin_logo', 'innerHTML', JText::_('CC JOIN GROUP TITLE'));

		$response->addAssign('cWindowContent' , 'innerHTML' , $contents);
		$response->addScriptCall('cWindowActions', $buttons);
		return $response->sendResponse();
	}

	/**
	 * Ajax Method to remove specific wall from the specific group
	 *
	 * @param wallId	The unique wall id that needs to be removed.
	 * @todo: check for permission
	 **/
	public function ajaxRemoveWall( $wallId )
	{
		CError::assert($wallId , '', '!empty', __FILE__ , __LINE__ );

		$response	= new JAXResponse();
		
		if (!COwnerHelper::isRegisteredUser())
		{
			return $this->ajaxBlockUnregister();
		}

		//@rule: Check if user is really allowed to remove the current wall
		$my			= CFactory::getUser();
		$model		=& $this->getModel( 'wall' );
		$wall		= $model->get( $wallId );
		
		$groupModel	=& CFactory::getModel( 'groups' );
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $wall->contentid );
		
		CFactory::load( 'helpers' , 'owner' );

		if( !COwnerHelper::isCommunityAdmin() && !$groupModel->isAdmin( $my->id , $group->id ) )
		{
			$response->addScriptCall( 'alert' , JText::_('CC NOT ALLOWED TO REMOVE WALL') );
		}
		else
		{
			if( !$model->deletePost( $wallId ) )
			{
				$response->addAlert( JText::_('CC CANNOT REMOVE WALL') );
			}
			else
			{
				if($wall->post_by != 0)
				{
					//add user points
					CFactory::load( 'libraries' , 'userpoints' );		
					CUserPoints::assignPoint('wall.remove', $wall->post_by);
				}			
			}
			
			$group->updateStats();
			$group->store();
		}

		return $response->sendResponse();
	}

	/**
	 * Ajax function to add new admin to the group
	 *
	 * @param memberid	Members id
	 * @param groupid	Groupid
	 *
	 **/
	public function ajaxRemoveAdmin( $memberId , $groupId )
	{
		$response	= new JAXResponse();
		
		$my			= CFactory::getUser();

		$model		=& $this->getModel( 'groups' );
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId ); 
		
		CFactory::load( 'helpers' , 'owner' );
		
		if( $group->ownerid != $my->id && !COwnerHelper::isCommunityAdmin() )
		{       
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC PERMISSION DENIED') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","error");');			
		}
		else
		{
			$member		=& JTable::getInstance( 'GroupMembers' , 'CTable' );

			$member->load( $memberId , $group->id );
			$member->permissions	= 0;
	
			$member->store();
	
			$response->addScriptCall('joms.jQuery("#member_' . $memberId . '").css("border","3px solid blue");');
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC MEMBER MADE USER') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","info");');   
		}
		
		return $response->sendResponse();
	}
	
	/**
	 * Ajax function to add new admin to the group
	 *
	 * @param memberid	Members id
	 * @param groupid	Groupid
	 *
	 **/
	public function ajaxAddAdmin( $memberId , $groupId )
	{
		$response	= new JAXResponse();
		
		$my			= CFactory::getUser();

		$model		=& $this->getModel( 'groups' );
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		
		CFactory::load( 'helpers' , 'owner' );
		
		
		
		if( $group->ownerid != $my->id && !COwnerHelper::isCommunityAdmin() )
		{
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC PERMISSION DENIED') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","error");');			
		}
		else
		{       
			$member		=& JTable::getInstance( 'GroupMembers' , 'CTable' );

			$member->load( $memberId , $group->id );
			$member->permissions	= 1;
	
			$member->store();
	
			$response->addScriptCall('joms.jQuery("#member_' . $memberId . '").css("border","3px solid green");');
			$response->addScriptCall('joms.jQuery("#notice").html("' . JText::_('CC MEMBER MADE ADMIN') . '");');
			$response->addScriptCall('joms.jQuery("#notice").attr("class","info");');
		}
		
		return $response->sendResponse();
	}
	
	/**
	 * Ajax function to save a new wall entry
	 *
	 * @param message	A message that is submitted by the user
	 * @param uniqueId	The unique id for this group
	 *
	 **/
	public function ajaxSaveDiscussionWall( $message , $uniqueId )
	{
		if (!COwnerHelper::isRegisteredUser()) {
			return $this->ajaxBlockUnregister();
		}

		$response		= new JAXResponse();

		$my				= CFactory::getUser();

		CFactory::load( 'models' , 'groups' );
		CFactory::load( 'models' , 'discussions' );
		CFactory::load( 'helpers' , 'url' );
		CFactory::load( 'libraries', 'activities' );
		CFactory::load( 'libraries', 'wall' );

		// Load models
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$discussionModel	= CFactory::getModel( 'Discussions' );
		$discussion		=& JTable::getInstance( 'Discussion' , 'CTable' );
		$message		= strip_tags( $message );
		$discussion->load( $uniqueId );
		$group->load( $discussion->groupid );

		// If the content is false, the message might be empty.
		if( empty( $message) )
		{
			$response->addAlert( JText::_('CC EMPTY MESSAGE') );
			return $response->sendResponse();
		}

		// Save the wall content
		$wall		= CWallLibrary::saveWall( $uniqueId , $message , 'discussions' , $my , ($my->id == $discussion->creator) , 'groups,discussion');
		$date		=& JFactory::getDate();
		
		$discussion->lastreplied	= $date->toMySQL();
		$discussion->store();

		// @rule: only add the activities of the wall if the group is not private.
		if( $group->approvals == COMMUNITY_PUBLIC_GROUP )
		{
			// Build the URL
			$discussURL		= CUrl::build( 'groups' , 'viewdiscussion', array( 'groupid' => $discussion->groupid , 'topicid' => $discussion->id) , true );

			$act = new stdClass();
			$act->cmd 		= 'group.discussion.reply';
			$act->actor 	= $my->id;
			$act->target 	= 0;
			$act->title		= JText::sprintf('CC ACTIVITIES REPLY DISCUSSION' , '{discuss_url}', $discussion->title );
			$act->content	= $message;
			$act->app		= 'groups';
			$act->cid		= $group->id;
			
			$params = new JParameter('');
			$params->set( 'action', 'group.discussion.reply' );
			$params->set( 'wallid', $wall->id);
			$params->set( 'group_url', 'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id);
			$params->set( 'group_name', $group->name);
			$params->set( 'discuss_url' , CUrl::build( 'groups' , 'viewdiscussion', array( 'groupid' => $discussion->groupid , 'topicid' => $discussion->id) , false ) );
		
			// Add activity log
			CActivityStream::add( $act, $params->toString() );
		}

		// Get repliers for this discussion and notify the discussion creator too
		$users		= $discussionModel->getRepliers( $discussion->id );
		$users[]	= $discussion->creator;
		$key		= array_search( $my->id , $users );
		
		if( $key !== false && isset( $users[ $key ] ) )
		{
			unset( $users[ $key ] );
		}
		
		// Add notification
		CFactory::load( 'libraries' , 'notification' );

		$params			= new JParameter( '' );
		$params->set( 'url' , 'index.php?option=com_community&view=groups&task=viewdiscussion&groupid='.$discussion->groupid . '&topicid=' . $discussion->id );
		$params->set( 'message' , $message );
		$params->set( 'title' , $discussion->title );
		
		CNotificationLibrary::add( 'groups.discussion.reply' , $my->id , $users , JText::sprintf( 'CC GROUP NEW DISCUSSION REPLY SUBJECT' , $my->getDisplayName() , $discussion->title ) , '' , 'groups.discussion.reply' , $params );
		
		//add user points
		CFactory::load( 'libraries' , 'userpoints' );		
		CUserPoints::assignPoint('group.discussion.reply');			

		$response->addScriptCall( 'joms.walls.insert' , $wall->content );

		return $response->sendResponse();
	}


	/**
	 * Ajax function to save a new wall entry
	 *
	 * @param message	A message that is submitted by the user
	 * @param uniqueId	The unique id for this group
	 *
	 **/
	public function ajaxSaveWall( $message , $groupId )
	{
		$response		= new JAXResponse();
		$my				= CFactory::getUser();

		// Load necessary libraries
		CFactory::load( 'libraries' , 'wall' );
		CFactory::load( 'helpers' , 'url' );
		CFactory::load ( 'libraries', 'activities' );

		$groupModel		=& CFactory::getModel( 'groups' );
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		$config			= CFactory::getConfig();
		
		// @rule: If configuration is set for walls in group to be restricted to memebers only,
		// we need to respect this.
		if( $config->get('lockgroupwalls') && !$group->isMember( $my->id ) )
		{
			$response->addScriptCall( 'alert' , JText::_('CC ACCESS FORBIDDEN' ) );
			return $response->sendResponse();
		}
		
		$message		= strip_tags( $message );
		// If the content is false, the message might be empty.
		if( empty( $message) )
		{
			$response->addAlert( JText::_('CC EMPTY MESSAGE') );
		}
		else
		{
			$isAdmin		= $groupModel->isAdmin( $my->id , $group->id );

			// Save the wall content
			$wall			= CWallLibrary::saveWall( $groupId , $message , 'groups' , $my , $isAdmin , 'groups,group');

			// Store event will update all stats count data
			$group->updateStats();
			$group->store();

			// @rule: only add the activities of the wall if the group is not private.
			if( $group->approvals == COMMUNITY_PUBLIC_GROUP )
			{
	
				$params = new JParameter('');
				$params->set('action', 'group.wall.create');
				$params->set('wallid', $wall->id);
				$params->set('group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $groupId ) , false ) );
				
				$act = new stdClass();
				$act->cmd 		= 'group.wall.create';
				$act->actor 	= $my->id;
				$act->target 	= 0;
				$act->title		= JText::sprintf('CC ACTIVITIES WALL POST GROUP' , '{group_url}' , $group->name );
				$act->content	= $message;
				$act->app		= 'groups';
				$act->cid		= $groupId;
				
				CActivityStream::add( $act, $params->toString() );
			}
			
			// @rule: Add user points
			CFactory::load( 'libraries' , 'userpoints' );
			CUserPoints::assignPoint('group.wall.create');

			// @rule: Send email notification to members
			$groupParams	= $group->getParams();
			
			if( $groupParams->get( 'wallnotification' ) == '1' )
			{
				$model			=& $this->getModel( 'groups' );
				$members 		= $model->getMembers($groupId, null );
				$admins			= $model->getAdmins( $groupId , null );
				
				$membersArray = array();

				foreach($members as $row)
				{
					$membersArray[] = $row->id;
				}
				
				foreach($admins as $row )
				{
					$membersArray[]	= $row->id;
				}
				unset($members);
				unset($admins);

				// Add notification
				CFactory::load( 'libraries' , 'notification' );

				$params			= new JParameter( '' );
				$params->set('url' , 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId );
				$params->set('group' , $group->name );
				$params->set('message' , $message );
				CNotificationLibrary::add( 'groups.wall.create' , $my->id , $membersArray , JText::sprintf('CC NEW WALL POST NOTIFICATION EMAIL SUBJECT' , $my->getDisplayName() , $group->name ) , '' , 'groups.wall' , $params );

			}
			$response->addScriptCall( 'joms.walls.insert' , $wall->content );
		}

		return $response->sendResponse();
	}

	public function edit()
	{
		$document	=& JFactory::getDocument();
		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd( 'view' , $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType );
		$mainframe	=& JFactory::getApplication();
		$groupId	= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$model		=& $this->getModel( 'groups' );
		$my			= CFactory::getUser();
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		CFactory::load( 'helpers' , 'owner' );

		if( $my->id == 0 )
		{
			return $this->blockUnregister();
		}
		
		if( !$group->isAdmin($my->id) && !COwnerHelper::isCommunityAdmin() )
		{
			echo $view->noAccess();
			return;
		}
		
		if( JRequest::getMethod() == 'POST' )
		{
		    JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
			$data		= JRequest::get( 'POST' );
			$group->bind( $data );

			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array( 'jsform-groups-forms' ) );

			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				$redirect	= CRoute::_('index.php?option=com_community&view=groups&task=edit&groupid=' . $groupId , false );
	
				$removeActivity		= JRequest::getVar( 'removeactivities' , false , 'POST' );
				
				if( $removeActivity )
				{
					$activityModel	=& CFactory::getModel( 'activities' );
					
					$activityModel->removeActivity( 'groups' , $group->id );
				}
				
				// validate all fields
				if( empty($group->name ))
				{
					$mainframe->redirect( $redirect , JText::_('CC GROUP NAME CANNOT BE EMPTY') );
					return;
				}
		
				if( $model->groupExist($group->name, $group->id) )
				{
					$mainframe->redirect( $redirect , JText::_('CC GROUP NAME TAKEN') );
					return;
				}
		
				if( empty($group->description ))
				{
					$mainframe->redirect( $redirect , JText::_('CC GROUP DESCRIPTION CANNOT BE EMPTY') );
					return;
				}
				
				// @rule: Retrieve params and store it back as raw string
				$params	= new JParameter('');
				
				$discussordering			= JRequest::getVar( 'discussordering' , DISCUSSION_ORDER_BYLASTACTIVITY , 'REQUEST' );
				$params->set('discussordering' , $discussordering );
				
				$photopermission			= JRequest::getVar( 'photopermission' , GROUP_PHOTO_PERMISSION_ADMINS , 'REQUEST' );
				$params->set('photopermission' , $photopermission );
				
				$videopermission			= JRequest::getVar( 'videopermission' , GROUP_PHOTO_PERMISSION_ADMINS , 'REQUEST' );
				$params->set('videopermission' , $videopermission );
				
				$grouprecentphotos			= JRequest::getInt( 'grouprecentphotos' , GROUP_PHOTO_RECENT_LIMIT , 'REQUEST' );
				$params->set('grouprecentphotos' , $grouprecentphotos );
				
				$grouprecentvideos			= JRequest::getInt( 'grouprecentvideos' , GROUP_VIDEO_RECENT_LIMIT , 'REQUEST' );
				$params->set('grouprecentvideos' , $grouprecentvideos );
				
				$newmembernotification		= JRequest::getVar( 'newmembernotification' , '1' , 'REQUEST' );
				$params->set('newmembernotification' , $newmembernotification );
				
				$joinrequestnotification	= JRequest::getVar( 'joinrequestnotification' , '1' , 'REQUEST' );
				$params->set('joinrequestnotification' , $joinrequestnotification );
	
				$wallnotification			= JRequest::getInt( 'wallnotification' , '1' , 'REQUEST' );
				$params->set('wallnotification' , $wallnotification );
				
				$group->params		= $params->toString();
				
				CFactory::load('helpers' , 'owner' );
				
				if( $model->isAdmin($my->id, $group->id) || COwnerHelper::isCommunityAdmin() )
				{
					$group->updateStats();
					$group->store();
		
					$act = new stdClass();
					$act->cmd 		= 'group.updated';
					$act->actor   	= $my->id;
					$act->target  	= 0;
					$act->title	  	= JText::sprintf('CC ACTIVITIES GROUP UPDATED' , '{group_url}' , $group->name );
					$act->content	= '';
					$act->app		= 'groups';
					$act->cid		= $group->id;
					
					$params = new JParameter('');
					$params->set('group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $groupId ) , false ) );
					
		
					// Add activity logging
					CFactory::load ( 'libraries', 'activities' );
					CActivityStream::add( $act, $params->toString() );
					
					//add user points
					CFactory::load( 'libraries' , 'userpoints' );		
					CUserPoints::assignPoint('group.updated');			
		
					// Reupdate the display.
					$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id , false ) , JText::_('CC GROUP UPDATED') );
					return;
				}
			}
		}
		
		echo $view->get( __FUNCTION__ );
	}
	
	/**
	 * Method to display the create group form
	 **/
	public function create()
	{
		$my =& JFactory::getUser();
		if($my->id == 0){
			return $this->blockUnregister();
		}

		$config 	=& CFactory::getConfig();
		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}
		
		if( !$config->get('creategroups') )
		{
			echo JText::_('CC GROUPS CREATION DISABLED');
			return;
		}

		CFactory::load('helpers' , 'limits' );						
		if(CLimitsHelper::exceededGroupCreation($my->id))
		{
			$groupLimit	= $config->get('groupcreatelimit');			
			echo JText::sprintf('CC GROUPS CREATION REACH LIMIT' , $groupLimit);
			return;
		}
		
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view = & $this->getView( $viewName , '' , $viewType);

		$model		=& $this->getModel( 'groups' );
 		$data		= new stdClass(); 		
		$data->categories	=	$model->getCategories();

		if( JRequest::getVar('action', '', 'POST') == 'save')
		{
			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array( 'jsform-groups-forms' ) );

			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				$gid = $this->save();
				
				if($gid !== FALSE )
				{
					$mainframe =& JFactory::getApplication();
	
					$group		=& JTable::getInstance( 'Group' , 'CTable' );
					$group->load($gid);
					
					//trigger for onGroupCreate
					$this->triggerGroupEvents( 'onGroupCreate' , $group);
					
					$url = CRoute::_( 'index.php?option=com_community&view=groups&task=created&groupid='.$gid , false );
					$mainframe->redirect( $url , JText::sprintf('CC GROUP CREATED NOTICE', $group->name ));
					return;
				}
			}
		}

		echo $view->get( __FUNCTION__ , $data );
	}

	/**
	 * A new group has been created
	 */
	public function created()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Method to save the group
	 * @return false if create fail, return the group id if create is successful
	 **/
	public function save()
	{
		if( JString::strtoupper(JRequest::getMethod()) != 'POST')
		{
			$view->addWarning( JText::_('CC PERMISSION DENIED'));
			return false;
		}

		$mainframe 	=& JFactory::getApplication();
		$document 	=& JFactory::getDocument();
		JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
 		$view		=& $this->getView( $viewName , '' , $viewType); 

 		// Get my current data.
		$my			= CFactory::getUser();
		$validated	= true;

		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$model		=& $this->getModel( 'groups' );

		$name			= JRequest::getVar('name' , '' , 'POST');
		$description	= JRequest::getVar('description' , '' , 'POST');
		$categoryId		= JRequest::getVar('categoryid' , '' , 'POST');
		$website		= JRequest::getVar('website' , '' , 'POST');

		// @rule: Test for emptyness
		if( empty( $name ) )
		{
			$validated = false;
			$mainframe->enqueueMessage( JText::_('CC GROUP NAME CANNOT BE EMPTY'), 'error');
		}

		// @rule: Test if group exists
		if( $model->groupExist( $name ) )
		{
			$validated = false;
			$mainframe->enqueueMessage( JText::_('CC GROUP NAME TAKEN'), 'error');
		}

		// @rule: Test for emptyness
		if( empty( $description ) )
		{
			$validated = false;
			$mainframe->enqueueMessage( JText::_('CC GROUP DESCRIPTION CANNOT BE EMPTY'), 'error');
		}

		if( empty( $categoryId ) )
		{
			$validated	= false;
			$mainframe->enqueueMessage(JText::_('CC GROUP CATEGORY NOT SELECTED'), 'error');
		}

		if($validated)
		{
			// Assertions
			// Category Id must not be empty and will cause failure on this group if its empty.
			CError::assert( $categoryId , '', '!empty', __FILE__ , __LINE__ );

			// Get the configuration object.
			$config	=& CFactory::getConfig();

			// @rule: Retrieve params and store it back as raw string
			$params	= new JParameter('');
			
			$discussordering			= JRequest::getVar( 'discussordering' , DISCUSSION_ORDER_BYLASTACTIVITY , 'REQUEST' );
			$params->set('discussordering' , $discussordering );
			
			$photopermission			= JRequest::getVar( 'photopermission' , GROUP_PHOTO_PERMISSION_ADMINS , 'REQUEST' );
			$params->set('photopermission' , $photopermission );
			
			$videopermission			= JRequest::getVar( 'videopermission' , GROUP_PHOTO_PERMISSION_ADMINS , 'REQUEST' );
			$params->set('videopermission' , $videopermission );
			
			$grouprecentphotos			= JRequest::getInt( 'grouprecentphotos' , GROUP_PHOTO_RECENT_LIMIT , 'REQUEST' );
			$params->set('grouprecentphotos' , $grouprecentphotos );
			
			$grouprecentvideos			= JRequest::getInt( 'grouprecentvideos' , GROUP_VIDEO_RECENT_LIMIT , 'REQUEST' );
			$params->set('grouprecentvideos' , $grouprecentvideos );			
			
			$newmembernotification		= JRequest::getInt( 'newmembernotification' , '1' , 'REQUEST' );
			$params->set('newmembernotification' , $newmembernotification );
			
			$joinrequestnotification	= JRequest::getInt( 'joinrequestnotification' , '1' , 'REQUEST' );
			$params->set('joinrequestnotification' , $joinrequestnotification );
			
			$wallnotification			= JRequest::getInt( 'wallnotification' , '1' , 'REQUEST' );
			$params->set('wallnotification' , $wallnotification );
			
			CFactory::load('helpers' , 'owner' );
			
			// Bind the post with the table first
			$group->name		= $name;
			$group->description	= $description;
			$group->categoryid	= $categoryId;
			$group->website		= $website;
			$group->ownerid		= $my->id;
			$group->created		= gmdate('Y-m-d H:i:s');
			$group->approvals	= JRequest::getVar('approvals' , '0' , 'POST');
			$group->params		= $params->toString();
			
			// Set the default thumbnail and avatar for the group just in case
			// the user decides to skip this
			// Update: No longer needed as getAvatar() in table takes care of it
			//$group->thumb		= 'components/com_community/assets/group_thumb.jpg';
			//$group->avatar		= 'components/com_community/assets/group.jpg';

			// @rule: check if moderation is turned on.
			$group->published	= ( $config->get('moderategroupcreation') ) ? 0 : 1;
			
			// we here save the group 1st. else the group->id will be missing and causing the member connection and activities broken.
			$group->store();
			
			// Since this is storing groups, we also need to store the creator / admin
			// into the groups members table
			$member				=& JTable::getInstance( 'GroupMembers' , 'CTable' );
			$member->groupid	= $group->id;
			$member->memberid	= $group->ownerid;
			
			// Creator should always be 1 as approved as they are the creator.
			$member->approved	= 1;
			
			// @todo: Setup required permissions in the future
			$member->permissions	= '1';
			$member->store();
			
			// @rule: Only add into activity once a group is created and it is published.
			if( $group->published )
			{
				$act = new stdClass();
				$act->cmd 		= 'group.create';
				$act->actor   	= $my->id;
				$act->target  	= 0;
				$act->title	  	= JText::sprintf('CC ACTIVITIES NEW GROUP' , '{group_url}' , $group->name );
				$act->content	= ( $group->approvals == 0) ? $group->description : '';
				$act->app		= 'groups';
				$act->cid		= $group->id;
	
				// Store the group now.
				$group->updateStats();
				$group->store();		
	
				$params = new JParameter('');
				$params->set( 'action', 'group.create' );
				$params->set( 'group_url' , 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );
		
				// Add activity logging
				CFactory::load ( 'libraries', 'activities' );
				CActivityStream::add( $act, $params->toString() );
			}
			
			//add user points
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('group.create');	
			

			$validated = $group->id;
		}

		return $validated;
	}

	/**
	 * Method to search for a group based on the parameter given
	 * in a POST request
	 **/
	public function search()
	{
		$config 	=& CFactory::getConfig();
		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}	
	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType );

		echo $view->get( __FUNCTION__  );
	}

	/**
	 * Ajax function call that allows user to leave group
	 *
	 * @param groupId	The groupid that the user wants to leave from the group
	 *
	 **/
	public function leaveGroup()
	{
		$groupId	= JRequest::getVar('groupid' , '' , 'POST');
		CError::assert( $groupId , '' , '!empty' , __FILE__ , __LINE__ );

		$model		=& $this->getModel('groups');
		$my			= CFactory::getUser();

		if( $my->id == 0 )
		{
			return $this->blockUnregister();
		}
		
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		
		$data		= new stdClass();
		$data->groupid	= $groupId;
		$data->memberid	= $my->id;

		$model->removeMember($data);
		
		//add user points
		CFactory::load( 'libraries' , 'userpoints' );		
		CUserPoints::assignPoint('group.leave');
		
		$mainframe =& JFactory::getApplication();
		
		//trigger for onGroupLeave
		$this->triggerGroupEvents( 'onGroupLeave' , $group , $my->id);
		
		// STore the group and update the data
		$group->updateStats();
		$group->store();
		
		$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups' , false) , JText::_('CC SUCCESS LEFT GROUP') );
	}

	/**
	 * Method is used to receive POST requests from specific user
	 * that wants to join a group
	 *
	 * @return	void
	 **/
	public function joinGroup()
	{
		$mainframe =& JFactory::getApplication();        

		$groupId	= JRequest::getVar('groupid' , '' , 'POST');

		// Add assertion to the group id since it must be specified in the post request
		CError::assert( $groupId , '' , '!empty' , __FILE__ , __LINE__ );

		// Get the current user's object
		$my			= CFactory::getUser();

		if( $my->id == 0 )
		{
			return $this->blockUnregister();
		}
		
		// Load necessary tables
		$groupModel	=& CFactory::getModel('groups');

		if( $groupModel->isMember( $my->id , $groupId ) )
		{
			$url 	= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$groupId, false);
			$mainframe->redirect( $url , JText::_( 'CC ALREADY MEMBER OF GROUP' ) );
		}
		else
		{
			$url 	= CRoute::getExternalURL('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$groupId, false);
				
			$member	= $this->_saveMember( $groupId );
			if( $member->approved )
			{
				$mainframe->redirect( $url , JText::_('CC SUCCESS JOIN GROUP') );
			}
			
			$mainframe->redirect( $url , JText::_( 'CC USER JOIN GROUP NEED APPROVAL' ) );
		}
	}

	private function _saveMember( $groupId )
	{
		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$member		=& JTable::getInstance( 'GroupMembers' , 'CTable' );

		$group->load( $groupId );
		$params		= $group->getParams();
		$my			= CFactory::getUser();
		
		// Set the properties for the members table
		$member->groupid	= $group->id;
		$member->memberid	= $my->id;

		CFactory::load( 'helpers' , 'owner' );
		// @rule: If approvals is required, set the approved status accordingly.
		$member->approved	= ( $group->approvals == COMMUNITY_PRIVATE_GROUP ) ? '0' : 1;
		
		// @rule: Special users should be able to join the group regardless if it requires approval or not
		$member->approved	= COwnerHelper::isCommunityAdmin() ? 1 : $member->approved;

 		//@todo: need to set the privileges
 		$member->permissions	= '0';
		
		$member->store();
		$owner	= CFactory::getUser( $group->ownerid );
		
		//trigger for onGroupJoin
		$this->triggerGroupEvents( 'onGroupJoin' , $group , $my->id);

		// Test if member is approved, then we add logging to the activities.
		if( $member->approved )
		{
			$act = new stdClass();
			$act->cmd 		= 'group.join';
			$act->actor   	= $my->id;
			$act->target  	= 0;
			$act->title	  	= JText::sprintf('CC ACTIVITIES GROUP JOIN' , '{group_url}' , $group->name );
			$act->content	= '';
			$act->app		= 'groups';
			$act->cid		= $group->id;
			
			$params = new JParameter('');
			$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );
			
			// Add logging
			CFactory::load ( 'libraries', 'activities' );
			CActivityStream::add($act, $params->toString() );
			
			//add user points
			CFactory::load( 'libraries' , 'userpoints' );		
			CUserPoints::assignPoint('group.join');	
			
			// Store the group and update stats
			$group->updateStats();
			$group->store();
		}
		return $member;
	}
	
	public function uploadAvatar()
	{
		$mainframe =& JFactory::getApplication();

		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);
		$my			=& JFactory::getUser();
		$config		= CFactory::getConfig();

		$groupid	= JRequest::getVar('groupid' , '' , 'REQUEST');
		$data		= new stdClass();
		$data->id	= $groupid;

		$groupsModel	=& $this->getModel( 'groups' );
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupid );

		if( $my->id == 0 )
		{
			return $this->blockUnregister();
		}
		
		if( !$group->isAdmin($my->id) && !COwnerHelper::isCommunityAdmin() )
		{
			echo $view->noAccess();
			return;
		}
		
		if( JRequest::getMethod() == 'POST' )
		{
			CFactory::load( 'helpers' , 'image' );

			$file		= JRequest::getVar('filedata' , '' , 'FILES' , 'array');

            if( !CImageHelper::isValidType( $file['type'] ) )
			{
				$mainframe->enqueueMessage( JText::_('CC IMAGE FILE NOT SUPPORTED') , 'error' );
				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id . '&task=uploadAvatar', false) );
        	}
        	
			CFactory::load( 'libraries' , 'apps' );
			$appsLib		=& CAppPlugins::getInstance();
			$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-groups-uploadavatar' ));

			if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
			{
				if( empty( $file ) )
				{
					$mainframe->enqueueMessage(JText::_('CC NO POST DATA'), 'error');
				}
				else
				{
	
					
					$uploadLimit	= (double) $config->get('maxuploadsize');
					$uploadLimit	= ( $uploadLimit * 1024 * 1024 );
	
					// @rule: Limit image size based on the maximum upload allowed.
					if( filesize( $file['tmp_name'] ) > $uploadLimit )
					{
						$mainframe->enqueueMessage( JText::_('CC IMAGE FILE SIZE EXCEEDED') , 'error' );
						$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=uploadavatar&groupid=' . $group->id , false) );
					}
					
					if( !CImageHelper::isValid($file['tmp_name'] ) )
					{
						$mainframe->enqueueMessage( JText::_('CC IMAGE FILE NOT SUPPORTED') , 'error');
					}
					else
					{
						// @todo: configurable width?
						$imageMaxWidth	= 160;
	
						// Get a hash for the file name.
						$fileName		= JUtility::getHash( $file['tmp_name'] . time() );
						$hashFileName	= JString::substr( $fileName , 0 , 24 );
	
						// @todo: configurable path for avatar storage?
						$storage			= JPATH_ROOT . DS . $config->getString('imagefolder') . DS . 'avatar' . DS . 'groups';
						$storageImage		= $storage . DS . $hashFileName . CImageHelper::getExtension( $file['type'] );
						$storageThumbnail	= $storage . DS . 'thumb_' . $hashFileName . CImageHelper::getExtension( $file['type'] );
						$image				= $config->getString('imagefolder'). '/avatar/groups/' . $hashFileName . CImageHelper::getExtension( $file['type'] );
						$thumbnail			= $config->getString('imagefolder'). '/avatar/groups/' . 'thumb_' . $hashFileName . CImageHelper::getExtension( $file['type'] );
	
						// Generate full image
						if(!CImageHelper::resizeProportional( $file['tmp_name'] , $storageImage , $file['type'] , $imageMaxWidth ) )
						{
							$mainframe->enqueueMessage(JText::sprintf('CC ERROR MOVING UPLOADED FILE' , $storageImage), 'error');
						}
	
						// Generate thumbnail
						if(!CImageHelper::createThumb( $file['tmp_name'] , $storageThumbnail , $file['type'] ))
						{
							$mainframe->enqueueMessage(JText::sprintf('CC ERROR MOVING UPLOADED FILE' , $storageThumbnail), 'error');
						}
	
						// Update the group with the new image
						$groupsModel->setImage( $groupid , $image , 'avatar' );
						$groupsModel->setImage( $groupid , $thumbnail , 'thumb' );
	
						// @rule: only add the activities of the news if the group is not private.
						if( $group->approvals == COMMUNITY_PUBLIC_GROUP )
						{
							$url = CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid='.$groupid);
							$act = new stdClass();
							$act->cmd 		= 'group.avatar.upload';
							$act->actor   	= $my->id;
							$act->target  	= 0;
							$act->title	  	= JText::sprintf('CC ACTIVITIES NEW GROUP AVATAR' , '{group_url}' , $group->name );
							$act->content	= '<img src="' . rtrim( JURI::root() , '/' ) . '/' . $thumbnail . '" style="border: 1px solid #eee;margin-right: 3px;" />';
							$act->app		= 'groups';
							$act->cid		= $group->id;
		
							$params = new JParameter('');
							$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );
						
							CFactory::load ( 'libraries', 'activities' );
							CActivityStream::add( $act, $params->toString() );
						}
						
						//add user points
						CFactory::load( 'libraries' , 'userpoints' );		
						CUserPoints::assignPoint('group.avatar.upload');					
	
						$mainframe =& JFactory::getApplication();
						$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupid , false ) , JText::_('CC GROUP AVATAR UPLOADED') );
						exit;
					}
				}
			}
		}

		echo $view->get( __FUNCTION__ , $data );
	}

	/**
	 * Method that loads the viewing of a specific group
	 **/
	public function viewGroup()
	{
		$config 	=& CFactory::getConfig();
		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Show only current user group
	 */
	public function mygroups(){
		$config 	=& CFactory::getConfig();
		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		echo $view->get( __FUNCTION__ );
	}

	public function myinvites()
	{
		$config 	=& CFactory::getConfig();
		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		echo $view->get( __FUNCTION__ );
	}
	
	public function viewmembers()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		$data		= new stdClass();
		$data->id	= JRequest::getVar('groupid' , '' , 'GET');
		echo $view->get( __FUNCTION__ , $data );
	}

	/**
	 * Show full view of the news for the group
	 **/
	public function viewbulletin()
	{
		$config 	=& CFactory::getConfig();
		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		$data		= new stdClass();

		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Show all news from specific groups
	 **/
	public function viewbulletins()
	{
		$my			= CFactory::getUser();		
	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Show all discussions from specific groups
	 **/
	public function viewdiscussions()
	{
		$my			= CFactory::getUser();		

		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		echo $view->get( __FUNCTION__ );
	}
	
	private function _saveDiscussion( &$discussion )
	{
		$topicId		= JRequest::getVar( 'topicid' , 'POST' );
		$postData		= JRequest::get( 'post' );
		$inputFilter	= CFactory::getInputFilter(true);
		$groupid		= JRequest::getVar('groupid' , '' , 'REQUEST');
		$my				= CFactory::getUser();
		$mainframe		= JFactory::getApplication();
		$groupid		= JRequest::getVar('groupid' , '' , 'REQUEST');
		$groupsModel	=& $this->getModel( 'groups' );
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupid );
		
		$discussion->bind( $postData );
		
		CFactory::load( 'helpers' , 'owner' );
		
		if( !empty( $discussion->creator ) && !$groupsModel->isAdmin( $my->id, $discussion->groupid ) & !COwnerHelper::isCommunityAdmin() )
		{
			$mainframe->enqueueMessage(JText::_('CC ACCESS FORBIDDEN'), 'error');
			return false;
		}
		
		$discussion->creator		= $my->id;
		$discussion->groupid		= $groupid;
		$discussion->created		= gmdate('Y-m-d H:i:s');
		$discussion->lastreplied	= $discussion->created;
		$discussion->message		= JRequest::getVar( 'message', '' , 'post' , 'string' , JREQUEST_ALLOWRAW);
		$discussion->message		= $inputFilter->clean( $discussion->message );
		
		// @rule: do not allow html tags in the title
		$discussion->title			= strip_tags( $discussion->title );

		CFactory::load( 'libraries' , 'apps' );
		$appsLib		=& CAppPlugins::getInstance();
		$saveSuccess	= $appsLib->triggerEvent( 'onFormSave' , array('jsform-groups-discussionform' ));
		$validated		= true;

		if( empty($saveSuccess) || !in_array( false , $saveSuccess ) )
		{
			if( empty($discussion->title) )
			{
				$validated 	= false;
				$mainframe->enqueueMessage(JText::_('CC DISCUSSION TOPIC CANNOT BE EMPTY'), 'error');
			}

			if( empty($discussion->message) )
			{
				$validated	= false;
				$mainframe->enqueueMessage(JText::_('CC DISCUSSION CANNOT BE EMPTY'), 'error');
			}

			if( $validated )
			{
				CFactory::load( 'models' , 'discussions' );
				
				$isNew	= is_null( $discussion->id ) || !$discussion->id ? true : false;
				
				$discussion->store();

				if( $isNew )
				{
					$group	=& JTable::getInstance( 'Group' , 'CTable' );
					$group->load( $groupid );
					
					// @rule: only add the activities of the discussion if the group is not private.
					if( $group->approvals == COMMUNITY_PUBLIC_GROUP )
					{
						// Add logging.
						$url				= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupid );
						CFactory::load ( 'libraries', 'activities' );
		
						$act = new stdClass();
						$act->cmd 		= 'group.discussion.create';
						$act->actor 	= $my->id;
						$act->target 	= 0;
						$act->title		= JText::sprintf('CC ACTIVITIES NEW GROUP DISCUSSION' , '{group_url}' , $group->name );
						$act->content	= $message;
						$act->app		= 'groups';
						$act->cid		= $group->id;
						
						$params				= new JParameter('');
						$params->set( 'action', 'group.discussion.create' );
						$params->set( 'topic_id', $discussion->id );
						$params->set( 'topic', $discussion->title );
						$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );
						$params->set( 'topic_url',  CUrl::build( 'groups' , 'viewdiscussion', array('groupid' =>$group->id, 'topicid' => $discussion->id), false) );
						
						CActivityStream::add( $act, $params->toString() );
					}
	
	
					//@rule: Add notification for group members whenever a new discussion created.
					$config		=& CFactory::getConfig();
					
					if($config->get('groupdiscussnotification') == 1 )
					{
						$model			=& $this->getModel( 'groups' );
						$members 		= $model->getMembers($groupid, null );
						$admins			= $model->getAdmins( $groupid , null );
						
						$membersArray = array();
		
						foreach($members as $row)
						{
							$membersArray[] = $row->id;
						}
						
						foreach($admins as $row )
						{
							$membersArray[]	= $row->id;
						}
						unset($members);
						unset($admins);
	
						// Add notification
						CFactory::load( 'libraries' , 'notification' );
		
						$params			= new JParameter( '' );
						$params->set('url' , 'index.php?option=com_community&view=groups&task=viewdiscussion&groupid=' . $group->id . '&topicid=' . $discussion->id );
						$params->set('group' , $group->name );
						$params->set('user' , $my->getDisplayName() );
						$params->set('subject'	, $discussion->title );
						$params->set('message' , $discussion->message );
	
						CNotificationLibrary::add( 'groups.create.discussion' , $discussion->creator , $membersArray , JText::sprintf('CC NEW DISCUSSION NOTIFICATION EMAIL SUBJECT' , $group->name ) , '' , 'groups.discussion' , $params );
					}
				}
								
				//add user points
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('group.discussion.create');
			}
		}
		else
		{
			$validated	= false;
		}
		
		return $validated;
	}
	
	public function adddiscussion()
	{
		$mainframe		=& JFactory::getApplication();
		$document 		=& JFactory::getDocument();
		$viewType		= $document->getType();
 		$viewName		= JRequest::getCmd( 'view', $this->getName() );
		$view			=& $this->getView( $viewName , '' , $viewType);
		$my				= CFactory::getUser();
		$groupid		= JRequest::getVar('groupid' , '' , 'REQUEST');
		$groupsModel	=& $this->getModel( 'groups' );
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupid );
						
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}
		
		CFactory::load('helpers', 'owner');
		if( !$group->isMember($my->id) && !COwnerHelper::isCommunityAdmin() )
		{
			echo $view->noAccess();
			return;
		}
		$discussion	=& JTable::getInstance( 'Discussion' , 'CTable' );
		
		if( JRequest::getMethod() == 'POST' )
		{
		    JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );

			if( $this->_saveDiscussion( $discussion ) !== false )
			{
				$redirectUrl	= CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussion&topicid=' . $discussion->id . '&groupid=' . $groupid , false );

				$mainframe->redirect( $redirectUrl , JText::_('CC DISCUSSION ADDED'));
				exit;
			}			
		}

		echo $view->get( __FUNCTION__  , $discussion );
	}

	/**
	 * Show discussion
	 */
	public function viewdiscussion()
	{
		$config 	=& CFactory::getConfig();
		if( !$config->get('enablegroups') )
		{
			echo JText::_('CC GROUPS DISABLED');
			return;
		}	
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		echo $view->get( __FUNCTION__ );
	}

	/**
	 * Show Invite
	 */
	public function invitefriends()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);
		$my			= CFactory::getUser();
		$invited		= JRequest::getVar( 'invite-list' , '' , 'POST' );
		$inviteMessage	= JRequest::getVar( 'invite-message' , '' , 'POST' );
		$groupId		= JRequest::getVar( 'groupid' , '' , 'REQUEST' );
		$groupsModel	=& $this->getModel( 'groups' );
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		
		if( $my->id == 0 )
		{
			return $this->blockUnregister();
		}
		
		if( !$group->isMember($my->id) && !COwnerHelper::isCommunityAdmin() )
		{
			echo JText::_('CC ACCESS FORBIDDEN');
			return;
		}
				
		if( JRequest::getMethod() == 'POST' )
		{
		    JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
			if( !empty($invited ) )
			{
				$my				= CFactory::getUser();
				$mainframe		=& JFactory::getApplication();
				$groupsModel	=& CFactory::getModel( 'Groups' );
				$group			=& JTable::getInstance( 'Group' , 'CTable' );
				$group->load( $groupId );

				
				foreach( $invited as $invitedUserId )
				{
					$groupInvite			=& JTable::getInstance( 'GroupInvite' , 'CTable' );
					$groupInvite->groupid	= $group->id;
					$groupInvite->userid	= $invitedUserId;
					$groupInvite->creator	= $my->id;
					
					$groupInvite->store();
				}
				// Add notification
				CFactory::load( 'libraries' , 'notification' );

				$params			= new JParameter( '' );
				$params->set('url' , 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id );
				$params->set('groupname' , $group->name );
				$params->set('message' , $inviteMessage );

				CNotificationLibrary::add( 'groups.invite' , $my->id , $invited , JText::sprintf('CC INVITED TO JOIN GROUP' , $group->name ) , '' , 'groups.invite' , $params );
				
				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $group->id , false ) , JText::_( 'CC GROUP INVITATIONS SENT' ) );				
			}
			else
			{
				$view->addWarning( JText::_('CC INVITE NEED AT LEAST 1 FRIEND') );
			}
		}
		echo $view->get( __FUNCTION__ );
	}

	public function editDiscussion()
	{
		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);
		$topicId	= JRequest::getVar( 'topicid' , 'POST' );
		$discussion	=& JTable::getInstance( 'Discussion' , 'CTable' );
		$discussion->load( $topicId );
		$groupId	= JRequest::getVar( 'groupid' , '' );
		$groupsModel	= CFactory::getModel( 'Groups' );
		$my			= CFactory::getUser();
		CFactory::load( 'helpers' , 'owner' );
		
		// Make sure this user is a member of this group
		if( !$groupsModel->isAdmin( $my->id , $discussion->groupid ) && !COwnerHelper::isCommunityAdmin() )
		{
			$mainframe	= JFactory::getApplication();
			$mainframe->enqueueMessage( JText::_('CC ACCESS FORBIDDEN'), 'error');
		}
		else
		{
			if( JRequest::getMethod() == 'POST' )
			{
			    JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
	
				if( $this->_saveDiscussion( $discussion ) !== false )
				{
					$redirectUrl	= CRoute::_('index.php?option=com_community&view=groups&task=viewdiscussion&topicid=' . $discussion->id . '&groupid=' . $groupId , false );
					
					$mainframe		=& JFactory::getApplication();
					$mainframe->redirect( $redirectUrl , JText::_('CC DISCUSSION UPDATED'));
				}
			}
			echo $view->get( __FUNCTION__ , $discussion );
		}
	}
	
	public function editNews()
	{
		$mainframe		=& JFactory::getApplication();
		$my				= CFactory::getUser();

		if($my->id == 0)
		{
			return $this->blockUnregister();
		}

		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		// Load necessary models
		$groupsModel	=& CFactory::getModel( 'groups' );
		CFactory::load( 'models' , 'bulletins' );
		
		$groupId		= JRequest::getVar( 'groupid' , '' , 'REQUEST' );

		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		CFactory::load( 'helpers' , 'owner' );
		
		// Ensure user has really the privilege to view this page.
		if( $my->id != $group->ownerid && !COwnerHelper::isCommunityAdmin() && !$groupsModel->isAdmin( $my->id , $groupId ) )
		{
			echo JText::_('CC PERMISSION DENIED');
			return;
		}

		if( JRequest::getMethod() == 'POST' )
		{  
		    JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
			// Get variables from query
			$bulletin			=& JTable::getInstance( 'Bulletin' , 'CTable' );
			$bulletinId			= JRequest::getVar( 'bulletinid' , '' , 'POST' );
			
			$bulletin->load( $bulletinId );
			$bulletin->message	= JRequest::getVar( 'message', '', 'post', 'string', JREQUEST_ALLOWRAW );
			$bulletin->title	= JRequest::getVar( 'title', '', 'post', 'string' );
			// Groupid should never be empty. Add some assert codes here
			CError::assert( $groupId , '' , '!empty' , __FILE__ , __LINE__ );
			CError::assert( $bulletinId , '' , '!empty' , __FILE__ , __LINE__ );

			if( empty( $bulletin->message ) )
			{
				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewbulletin&bulletinid=' . $bulletinId . '&groupid=' . $groupId , false ), JText::_('CC BULLETIN NO MESSAGE') );
			}

			$bulletin->store();
			$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewbulletin&bulletinid=' . $bulletinId . '&groupid=' . $groupId , false ), JText::_('CC BULLETIN UPDATED') );
		}
	}
	
	/**
	 * Method to add a new discussion
	 **/
	public function addNews()
	{
		$mainframe =& JFactory::getApplication();  

		$my = CFactory::getUser();

		if($my->id == 0)
		{
			return $this->blockUnregister();
		}

		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		// Load necessary models
		$groupsModel	=& CFactory::getModel( 'groups' );
		CFactory::load( 'models' , 'bulletins' );
		$groupId		= JRequest::getVar( 'groupid' , '' , 'REQUEST' );

		$group		=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		CFactory::load( 'helpers' , 'owner' );
		
		// Ensure user has really the privilege to view this page.
		if( $my->id != $group->ownerid && !COwnerHelper::isCommunityAdmin() && !$groupsModel->isAdmin( $my->id , $groupId ) )
		{
			echo $view->noAccess();
			return;
		}

		$title		= '';
		$message	= '';

		if( JRequest::getMethod() == 'POST' )
		{   
		    JRequest::checkToken() or jexit( JText::_( 'CC INVALID TOKEN' ) );
		    
			// Get variables from query
			$bulletin			=& JTable::getInstance( 'Bulletin' , 'CTable' );
			$bulletin->title	= JRequest::getVar( 'title' , '' , 'post' );
			$bulletin->message	= JRequest::getVar( 'message', '', 'post', 'string', JREQUEST_ALLOWRAW );

			// Groupid should never be empty. Add some assert codes here
			CError::assert( $groupId , '' , '!empty' , __FILE__ , __LINE__ );

			$validated	= true;

			if( empty($bulletin->title) )
			{
				$validated	= false;
				$mainframe->enqueueMessage( JText::_('CC BULLETIN NO TITLE'), 'notice');
			}

			if( empty($bulletin->message) )
			{
				$validated 	= false;
				$mainframe->enqueueMessage(JText::_('CC BULLETIN NO MESSAGE'), 'notice');
			}

			if( $validated )
			{
				$bulletin->groupid		= $groupId;
				$bulletin->date			= gmdate( 'Y-m-d H:i:s' );
				$bulletin->created_by	= $my->id;

	 			// @todo: Add moderators for the groups.
				// Since now is default to the admin, default to publish the news
	 			$bulletin->published	= 1;

				$bulletin->store();

				// Send notification to all user
				$model			=& $this->getModel( 'groups' );
				$memberCount 	= $model->getMembersCount($groupId);
				$members 		= $model->getMembers($groupId, $memberCount);
				
				$membersArray = array();

				foreach($members as $row)
				{
					$membersArray[] = $row->id;
				}
				unset($members);

				// Add notification
				CFactory::load( 'libraries' , 'notification' );

				$params			= new JParameter( '' );
				$params->set('url' , 'index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId );
				$params->set('group' , $group->name );
				$params->set('subject' , $bulletin->title );

				CNotificationLibrary::add( 'groups.create.news' , $my->id , $membersArray , JText::sprintf('CC NEW BULLETIN NOTIFICATION EMAIL SUBJECT' , $group->name ) , '' , 'groups.bulletin' , $params );

				// @rule: only add the activities of the news if the group is not private.
				if( $group->approvals == COMMUNITY_PUBLIC_GROUP )
				{
					// Add logging to the bulletin
					$url	= CRoute::_('index.php?option=com_community&view=groups&task=viewbulletin&groupid=' . $group->id . '&bulletinid=' . $bulletin->id );
	
					// Add activity logging
					CFactory::load ( 'libraries', 'activities' );
					$act = new stdClass();
					$act->cmd 		= 'group.news.create';
					$act->actor 	= $my->id;
					$act->target 	= 0;
					$act->title		= JText::sprintf('CC ACTIVITIES NEW GROUP NEWS' , '{group_url}' , $bulletin->title );
					$act->content	= ( $group->approvals == 0 ) ? JString::substr( strip_tags( $bulletin->message ) , 0 , 100 ) : '';
					$act->app		= 'groups';
					$act->cid		= $bulletin->groupid;
					
					$params = new JParameter('');
					$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );
				
				
					CActivityStream::add( $act, $params->toString() );
				}
							
				//add user points
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('group.news.create');				

				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupId , false ), JText::_('CC BULLETIN ADDED') );
			}
			else
			{
				echo $view->get( __FUNCTION__ , $bulletin );
				return;
			}
		}

		echo $view->get( __FUNCTION__ , false );
	}

	public function deleteTopic()
	{
		$mainframe =& JFactory::getApplication();

		$my	= CFactory::getUser();
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}

		$document 	=& JFactory::getDocument();
		$viewType	= $document->getType();
 		$viewName	= JRequest::getCmd( 'view', $this->getName() );
		$view		=& $this->getView( $viewName , '' , $viewType);

		$topicid	= JRequest::getVar( 'topicid' , '' , 'POST' );
		$groupid	= JRequest::getVar( 'groupid' , '' , 'POST' );

		if( empty( $topicid ) || empty($groupid ) )
		{
			echo JText::_('Invalid id');
			return;
		}

		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'discussions' );

		$groupsModel	=& CFactory::getModel( 'groups' );
		$wallModel		=& CFactory::getModel( 'wall' );
		$discussion		=& JTable::getInstance( 'Discussion' , 'CTable' );
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupid );
		$isGroupAdmin	= $groupsModel->isAdmin( $my->id , $group->id );

		if( $isGroupAdmin || COwnerHelper::isCommunityAdmin() )
		{
			$discussion->load( $topicid );

			if( $discussion->delete() )
			{
				// Remove the replies to this discussion as well since we no longer need them
				$wallModel->deleteAllChildPosts( $topicid , 'discussions' );
				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupid , false ), JText::_('CC DISCUSSION REMOVED') );
			}
		}
		else
		{
			$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupid , false ), JText::_('CC NOT ALLOWED TO REMOVE GROUP TOPIC') );
		}
	}

	public function deleteBulletin()
	{
		$mainframe	=& JFactory::getApplication();
		$my			= CFactory::getUser();

		if($my->id == 0)
		{
			return $this->blockUnregister();
		}

		$bulletinId	= JRequest::getVar( 'bulletinid' , '' , 'POST' );
		$groupid	= JRequest::getVar( 'groupid' , '' , 'POST' );

		if( empty( $bulletinId ) || empty($groupid ) )
		{
			echo JText::_('CC INVALID ID');
			return;
		}

		CFactory::load( 'helpers' , 'owner' );
		CFactory::load( 'models' , 'bulletins' );

		$groupsModel	=& CFactory::getModel( 'groups' );
		$bulletin		=& JTable::getInstance( 'Bulletin' , 'CTable' );
		$group			=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupid );

		if( $groupsModel->isAdmin( $my->id , $group->id ) || COwnerHelper::isCommunityAdmin() )
		{
			$bulletin->load( $bulletinId );

			if( $bulletin->delete() )
			{
			
				//add user points
				CFactory::load( 'libraries' , 'userpoints' );		
				CUserPoints::assignPoint('group.news.remove');			
			
				$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupid , false ), JText::_('CC BULLETIN REMOVED') );
			}
		}
		else
		{
			$mainframe->redirect( CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groupid , false ), JText::_('CC NOT ALLOWED TO REMOVE GROUP TOPIC') );
		}
	}
	
	/*
	 * group event name
	 * object array	 	
     */	
	
	public function triggerGroupEvents( $eventName, &$args, $target = null)
	{
		CError::assert( $args , 'object', 'istype', __FILE__ , __LINE__ );
		
		require_once( COMMUNITY_COM_PATH.DS.'libraries' . DS . 'apps.php' );
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();		
		
		$params		= array();
		$params[]	= &$args;
		
		if(!is_null($target))
			$params[]	= $target;
				
		$appsLib->triggerEvent( $eventName , $params);
		return true;
	}
}