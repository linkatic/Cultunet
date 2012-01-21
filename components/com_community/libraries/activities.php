<?php
/**
 * @package		JomSocial
 * @subpackage	Library 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
class CActivities
{
	
	/**
	 * Removes an existing activity from the system
	 * @access	static
	 **/	 	 	
	function remove( $appType , $uniqueId )
	{
		$activitiesModel	=& CFactory::getModel( 'activities' );
		
		return $activitiesModel->removeActivity( $appType , $uniqueId );
	}
	
	/**
	 * Add new activity,
	 * @access 	static
	 * 	 
	 */	 	
	function add($activity, $params='', $points = 1){
		
		CError::assert($activity , '', '!empty', __FILE__ , __LINE__ );
		
		// If params is an object, instead of a string, we convert it to string
		
		$cmd 	= !empty($activity->cmd) 		? $activity->cmd : '';
				
		if( !empty($cmd) )
		{
			$userPointModel	= CFactory::getModel( 'Userpoints' );
	
			// Test command, with userpoint command. If is unpublished do not proceed into adding to activity stream.
			$point			= $userPointModel->getPointData( $cmd );
			
			if( $point && !$point->published )
			{
				return;
			}			
		}
		
		$actor	= !empty($activity->actor) 		? $activity->actor : '';
		$target = !empty($activity->target) 	? $activity->target : 0;
		$title	= !empty($activity->title) 		? $activity->title : '';
		$content= !empty($activity->content) 	? $activity->content : '';
		$appname= !empty($activity->app) 		? $activity->app : '';
		$cid	= !empty($activity->cid) 		? $activity->cid : 0;
		$points	= !empty($activity->points) 	? $activity->points : $points;
		$access	= !empty($activity->access) 	? $activity->access : 0;
		
		// If the params in embedded within the activity object, use it
		// if it is not explicitly overriden
		if (empty($params) && !empty($activity->params))
		{
			$params = $activity->params;
		}
		
		include_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'models'.DS.'activities.php');
		
		if( class_exists('CFactory') )
		{
			$activities =& CFactory::getModel('activities');
		}
		else
		{
			$activities = new CommunityModelActivities();
		}
		
		// Update access for activity based on the user's profile privacy
		if( !empty($actor) && $actor != 0)
		{
			$user			= CFactory::getUser( $actor );
			$userParams		= $user->getParams();
			$profileAccess	= $userParams->get('privacyProfileView');
			
			// Only overwrite access if the user global profile privacy is higher
			if( $profileAccess > $access )
			{
				$access	= $profileAccess;
			}
		}
		$activities->add($actor, $target, $title, $content, $appname, $cid, $params, $points, $access);
	}
	
	
	
	/**
	 * Return the HTML formatted activity contet
	 */
	static function getActivityContent($act)
	{
		// Return empty content or content with old, invalid data
		// In some old version, some content might have 'This is the body'
		if( $act->content == 'This is the body' ){
			return '';
		}
		
		$html = $act->content;
		
		// For know core, apps, we can simply call the content command
		switch($act->app)
		{
			case 'videos':
				//if($act->content == '{getActivityContentHTML}')
				{
					CFactory::load('libraries' , 'videos');
					$html = CVideos::getActivityContentHTML($act);
				}
				break;
				
			case 'photos':
				//if($act->content == '{getActivityContentHTML}')
				{
					CFactory::load('libraries' , 'photos');
					$html = CPhotos::getActivityContentHTML($act);
				}
				break;
				
			case 'events':
				{
					CFactory::load('libraries' , 'events');
					$html = CEvents::getActivityContentHTML($act);
				}
				break;
				
			case 'groups':
				{
					CFactory::load('libraries' , 'groups');
					$html = CGroups::getActivityContentHTML($act);
				}
				break;
			case 'walls':
				// If a wall does not have any content, do not
				// display the summary
				if($act->app == 'walls' && $act->cid == 0){
					$html = '';
					return $html;
				}
			default:
				// for other unknown apps, we include the plugin see if it is is callable
				// we call the onActivityContentDisplay();
				CFactory::load( 'libraries', 'apps' );

				$apps		=& CAppPlugins::getInstance();
				$plugin  	=& $apps->get($act->app);
				$method		= 'onActivityContentDisplay';
				
				if( is_callable( array($plugin, $method) ) )
				{
					$args = array();
					$args[] = $act;

					$html	= call_user_func_array( array($plugin, $method) , $args);
					
				} 
				else
				{
					
						$html = $act->content;
				}
				
		}
			
		return $html;
	}
		 	
	/**
	 * Return an array of activity data
	 */	 	
	function _getData($actor, $target, $date = null, $maxEntry=20 , $type = '') {
	
		$activities =& CFactory::getModel('activities');
		$appModel	=& CFactory::getModel('apps');
		$html 		= '';
		$numLines 	= 0;
		$my			= CFactory::getUser();
		$actorId	= $actor;
		$htmlData 	= array();
		$config		= CFactory::getConfig();
        		
		//Get blocked list
		$model		   = CFactory::getModel('block');
		$blockLists    = $model->getBanList($my->id);
		$blockedUserId = array();
		
		foreach($blockLists as $blocklist){
		    $blockedUserId[] = $blocklist->blocked_userid; 
        }

        // Exclude banned userid
        if( !empty($target) && !empty($blockedUserId) )
            $target = array_diff($target,$blockedUserId);	
		
		if( !empty($type))
		{
			$rows = $activities->getAppActivities( $type , $actor, $maxEntry , $config->get('respectactivityprivacy') );
		}
		else
		{
			$rows = $activities->getActivities( $actor, $target, $date, $maxEntry , $config->get('respectactivityprivacy') );
		}
;		
		$day = -1;
		
		// Inject additional properties for processing
		for($i = 0; $i < count($rows); $i++) 
		{
			$row =& $rows[$i];
			$row->used 		= false;		// A 'used' activities = activities that has been aggregated 
		}
		
		$dayinterval 	= ACTIVITY_INTERVAL_DAY;
		$lastTitle 		= '';
		for($i = 0; $i < count($rows) && (count($htmlData) <= $maxEntry ); $i++) 
		{
			$row = $rows[$i];
			$oRow =& $rows[$i];
			$oRow->activities = array(); // store aggregated activities

			if(!$row->used && count($htmlData) <= $maxEntry ) {

				$oRow =& $rows[$i];
				if(!isset($row->used))
					$row->used = false;
				
				if($day != $row->daydiff)
				{
					$act = new stdClass();
					$act->type = 'content';
					
					$day = $row->daydiff;
					if($day == 0)
						$act->title = JText::_('TODAY');
					else if($day == 1)	
						$act->title = JText::_('CC YESTERDAY');
					else if($day < 7)
						$act->title = JText::sprintf('CC DAYS AGO', $day);
					else if(($day >= 7) && ($day < 30))
					{
						$dayinterval = ACTIVITY_INTERVAL_WEEK;						
						$act->title = (intval($day/$dayinterval) == 1 ? JText::_('CC WEEK AGO') : JText::sprintf('CC WEEKS AGO', intval($day/$dayinterval)));
					}	
					else if(($day >= 30))
					{
						$dayinterval = ACTIVITY_INTERVAL_MONTH;
						$act->title = (intval($day/$dayinterval) == 1 ? JText::_('CC MONTH AGO') : JText::sprintf('CC MONTHS AGO', intval($day/$dayinterval)));
					}	
					
					// set to a new 'title' type if this new one has a new title
					// only add if this is a new title
					if($act->title != $lastTitle) {
						$lastTitle 	= $act->title;
						$act->type 	= 'title'; 
						$htmlData[] = $act;
					}
				}
				
				$act = new stdClass();
				$act->type = 'content';
				
				$title = $row->title;
				$app = $row->app;
				$cid = $row->cid;
				$actor = $row->actor;
				
				for($j = $i; ($j < count($rows)) && ($row->daydiff == $day); $j++)
				{
					$row = $rows[$j];			
					// we aggregate stream that has the same content on the same day.
					// we should not however aggregate content that does not support
					// multiple content. How do we detect? easy, they don't have
					// {multiple} in the title string
					
					// However, if the activity is from the same user, we only want 
					// to show the laste acitivity
					if( ($row->daydiff == $day) 
						&& ($row->title  == $title) 
						&& ($app == $row->app) 
						&& ($cid == $row->cid )
						
						&& ( 
							( JString::strpos($row->title, '{/multiple}') !== FALSE )
							||
							($row->actor == $actor )
							)
						 
						)
					{
						$row->used 		= true;
						$oRow->activities[] = $row;
					}
				}
				
				$app	= !empty($oRow->app) ? $this->_appLink($oRow->app, $oRow->actor, $oRow->target) : ''; 
				$oRow->title	= JString::str_ireplace('{app}', $app, $oRow->title);    
				
				$favicon = '';
				
				
				// this should not really be empty
				if(!empty($oRow->app))
				{
				    // check if the image icon exist in template folder
				    if ( JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'templates' . DS . $config->get('template') . DS . 'images' . DS . 'favicon' . DS . $oRow->app.'.png') )
				    {
				        $favicon = JURI::base(). 'components/com_community/templates/'.$config->get('template').'/images/favicon/'.$oRow->app.'.png';
					}
					else
					{
					    // check if the image icon exist in asset folder
						if ( JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'assets' . DS . 'favicon' . DS . $oRow->app.'.png') )
						{
							$favicon = JURI::base(). 'components/com_community/assets/favicon/'.$oRow->app.'.png';
						}
						elseif ( JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'plugins' . DS . 'community' . DS . $oRow->app . DS . 'favicon.png') )
						{
							$favicon = JURI::base(). 'plugins/community/'.$oRow->app.'/favicon.png';
						}
						else
						{
                            $favicon = JURI::base(). 'components/com_community/assets/favicon/default.png';
						}
					}
				}
				else
				{
				    $favicon = JURI::base(). 'components/com_community/assets/favicon/default.png';
				}

				$act->favicon = $favicon;
				
				$target = $this->_targetLink($oRow->target, true );
				$oRow->title	= JString::str_ireplace('{target}', $target, $oRow->title);
				
				if(count($oRow->activities) > 0)
				{
					
					// multiple
					$actorsLink = '';					
					foreach( $oRow->activities as $actor )
					{
						if(empty($actorsLink))
							$actorsLink = $this->_actorLink(intval($actor->actor));
						else {
							// only add if this actor is NOT already linked
							$alink = $this->_actorLink(intval($actor->actor));
							$pos = strpos($actorsLink, $alink);
							if ($pos === false) {
								$actorsLink .= ', '.$alink;
							}
						}
					}
					$actorLink = $this->_actorLink(intval($oRow->actor));
					
					$count = count($oRow->activities);
					
					$oRow->title 	= preg_replace('/\{single\}(.*?)\{\/single\}/i', '', $oRow->title);
					$search  		= array('{multiple}','{/multiple}');
					
					$oRow->title	= JString::str_ireplace($search, '', $oRow->title);
					$oRow->title	= JString::str_ireplace('{actors}'	, $actorsLink, $oRow->title);
					$oRow->title	= JString::str_ireplace('{actor}'	, $actorLink, $oRow->title);
					$oRow->title	= JString::str_ireplace('{count}'	, $count, $oRow->title);
				} else {
					// single
					$actorLink = $this->_actorLink(intval($oRow->actor));
					
					$oRow->title = preg_replace('/\{multiple\}(.*)\{\/multiple\}/i', '', $oRow->title);
					$search  = array('{single}','{/single}');
					$oRow->title	= JString::str_ireplace($search, '', $oRow->title);
					$oRow->title	= JString::str_ireplace('{actor}', $actorLink, $oRow->title);
				}
				
				// If the param contains any data, replace it with the content
				preg_match_all("/{(.*?)}/", $oRow->title, $matches, PREG_SET_ORDER);
				if(!empty( $matches )) 
				{
					$params = new JParameter( $oRow->params );
					foreach ($matches as $val) 
					{	
						
						$replaceWith = $params->get($val[1], null);
						
						//if the replacement start with 'index.php', we can CRoute it
						if( strpos($replaceWith, 'index.php') === 0){
							$replaceWith = CRoute::_($replaceWith);
						}
						
						if( !is_null( $replaceWith ) ) 
						{
							$oRow->title	= JString::str_ireplace($val[0], $replaceWith, $oRow->title);
						}
					}
				}


				$act->id 		= $oRow->id;
				$act->title 	= $oRow->title;
				$act->actor 	= $oRow->actor;
				$act->content	= $this->getActivityContent( $oRow );
				
				$timeFormat		= $config->get( 'activitiestimeformat' );
				$dayFormat		= $config->get( 'activitiesdayformat' );
				$date			= CTimeHelper::getDate($oRow->created);

				$createdTime 	= $date->toFormat($dayinterval == ACTIVITY_INTERVAL_DAY ? $timeFormat : $dayFormat );
				$act->created 	= $createdTime;
				$act->createdDate = $date->toFormat(JText::_('DATE_FORMAT_LC2'));
				$act->app 		= $oRow->app;
						
				$htmlData[] = $act;
			}
		}
		
		return $htmlData;
	}
	
	
	/**
	 * Return html formatted activity stream
	 * @access 	public
	 * @todo	Add caching	- Improve performance via caching 	 
	 */	 	
	function getHTML( $actor, $target, $date = null, $maxEntry=0 , $type = '', $idprefix = '', $showMore = true )
	{

		jimport('joomla.utilities.date');
		$mainframe =& JFactory::getApplication();
		
		CFactory::load('helpers', 'url');
		CFactory::load('helpers', 'owner');
		CFactory::load('libraries', 'template');
		
		$activities =& CFactory::getModel('activities');
		$appModel	=& CFactory::getModel('apps');
		$config 	=& CFactory::getConfig();
		$html = '';
		$numLines = 0;
		$my			= CFactory::getUser();
		$actorId	= $actor;
		$htmlData = array();
		$tmpl = new CTemplate();
		
		$maxList = $config->get('maxactivities');
		$maxList = ($maxEntry == 0) ? $maxList :  $maxEntry;
		$config	=& CFactory::getConfig();
		$isSuperAdmin	= COwnerHelper::isCommunityAdmin(); 	
		
		$htmlData = $this->_getData($actor, $target, $date, $maxList, $type);
		
		$tmpl->set('isMine', COwnerHelper::isMine($my->id, $actor));
		$tmpl->set('activities', $htmlData);
		$tmpl->set('idprefix', $idprefix);
		$tmpl->set('my', $my);
		$tmpl->set('isSuperAdmin',$isSuperAdmin);
		$tmpl->set( 'config' , $config );
		$tmpl->set( 'showMore'	, $showMore );
		$html = $tmpl->fetch('activities.index');
		
		return $html;
	
	}
	
	/**
	 * Return array of rss-feed compatible data
	 */	 	
	function getFEED($actor, $target, $date = null, $maxEntry=20,  $type='')
	{
		jimport('joomla.utilities.date');
		$mainframe =& JFactory::getApplication();
		
		$activities =& CFactory::getModel('activities');
		$appModel	=& CFactory::getModel('apps');
		$html = '';
		$numLines = 0;
		$my			= CFactory::getUser();
		$actorId	= $actor;
		$feedData 	= array();
		//$rows = $activities->getActivities($actor, $target, $date);
		$htmlData = $this->_getData($actor, $target, $date, $maxEntry, $type);
		
		return $htmlData;
	}
	
	/**
	 * Return how many days has lapse since
	 * @param	JDate date The date you want to compare	 	
	 * @access 	private
	 */	 	
	function _daysLapse($date){
		require_once (JPATH_COMPONENT.DS.'helpers'.DS.'time.php');
		$now =& JFactory::getDate();
		
		$html ='';
		$diff = CTimeHelper::timeDifference($date->toUnix(), $now->toUnix());
		return $diff['days'];
	}
	
	
	/**
	 * Return html formatted lapse time
	 * @param	JDate date The date you want to compare	 	
	 * @access 	private	 
	 */	 	
	function _createdLapse(&$date){
		require_once (JPATH_COMPONENT.DS.'helpers'.DS.'time.php');
		$now =& JFactory::getDate();
		
		$html ='';
		$diff = CTimeHelper::timeDifference($date->toUnix(), $now->toUnix());
		if(!empty($diff['days'])){
			if($diff['days'] == 1)
				$html .= JText::_('CC LAPSED YESTERDAY');
			else
				$html .= JText::sprintf('CC LAPSED DAYS', $diff['days']) . ' ';
		} else {
			// We only show he hours if it is less than 1 day
			if(!empty($diff['hours']))				
				$html .= JText::sprintf('CC LAPSED HOURS', $diff['hours']) . ' ';
			
			if(!empty($diff['minutes']))
				$html .= JText::sprintf('CC LAPSED MINUTES', $diff['minutes']) . ' ';
		}
		
		if(empty($html)){
			$html .= JText::_('CC LAPSED LESS THAN A MINUTE');
		}
		
		if($html != JText::_('CC LAPSED YESTERDAY'))
			$html .= JText::_('CC LAPSED AGO');
		return $html;
	}
	
	/**
	 * Return html formatted link to actor
	 * @param	integer id Actor/user id	 	
	 * @access 	private	 
	 */	
	function _actorLink($id){
		static $instances = array();
		
		if( empty($instances[$id])) {
		
		$my			=& JFactory::getUser();
		$view 		= JRequest::getVar('view', 'frontpage', 'REQUEST');
		$format		= JRequest::getVar('format', 'html', 'REQUEST');
		$linkName	= ($id==0)? false : true;
		
		// Only switch to use 'you' if you're viewing your own profile
// 		if(($id == $my->id) && ($id == $user->id) && ($view == 'profile') && ($format == 'html')){
// 			$name = 'You';
// 			$linkName = false;
// 		} else 
		//{
			$user = CFactory::getUser($id);
			$name = $user->getDisplayName();
		//}
		
		// Wrap the name with link to his/her profile
		$html = $name;
		if($linkName)
			$html = '<a href="'.CUrlHelper::userLink($id).'" class="actor-link">'.$name.'</a>';
		$instances[$id] = $html;
		
		}
		
		return $instances[$id];
	}
	
	/**
	 * Return html formatted link to target
	 * @param	integer id Target/user id	 	
	 * @access 	private	 
	 */	
	function _targetLink( $id, $onApp=false )
	{
		static $instances = array();
		
		if( empty($instances[$id]) ){
		
		$my			=& JFactory::getUser();
		$linkName	= ($id==0)? false : true;
		
// 		if(($id == $my->id) && ($id == $user->id)){
// 			$name = $onApp ? 'your' : 'you';
// 			$linkName = false;
// 		} else 
		//{
			$user 	= CFactory::getUser($id);
			$name = $user->getDisplayName();
		//}
		
		// Wrap the name with link to his/her profile
		$html = $name;
		if($linkName)
			$html = '<a href="'.CUrlHelper::userLink($id).'">'.$name.'</a>';
			
		$instances[$id] = $html;
		}
		return $instances[$id];
	}
	
	/**
	 * Return html formatted link to application
	 * @param	integer id Actor/user id	 	
	 * @access 	private	
	 * @todo	Add link to known application/views 	 
	 */	
	function _appLink($name, $actor = 0, $userid = 0){
		
// 		static $instances = array();
		//$my =& JFactory::getUser();
		
		if(empty($name))
			return '';
		
// 		if( empty($instances[$id.$actor.$userid]) )
// 		{
		$appModel	= CFactory::getModel('apps');
		$url = '';
		
		// @todo: check if this app exist
		if(true) {
			// if no target specified, we use actor
			if($userid == 0) 
				$userid= $actor;
				
			if( $userid != 0 
				&& $name != 'profile'
				&& $name != 'news_feed'
				&& $name != 'photos'
				&& $name != 'friends')
				{
					
				$url = CUrlHelper::userLink($userid) . '#app-' . $name;
				$url = '<a href="' . $url .'" >'. $appModel->getAppTitle($name) . '</a>';
			}else{
				$url = $appModel->getAppTitle($name);
			}
			
		}
		return $url;
	}
	
	function getCustomActivities()
	{
		
	}
}

/**
 * Maintain classname compatibility with JomSocial 1.6 below
 */
class CActivityStream extends CActivities
{}