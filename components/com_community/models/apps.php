<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'models'.DS.'models.php');

// Deprecated since 1.8.x to support older modules / plugins
CFactory::load( 'tables' , 'app' );


class CommunityModelApps extends JCCModel
{

	  /**
	   * Items total
	   * @var integer
	   */
	  var $_total = null;
	
	  /**
	   * Pagination object
	   * @var object
	   */
	  var $_pagination = null;

	/**
	 *Constructor
	 *
	 */
 	 function CommunityModelApps()
	 {
 	 	parent::JCCModel();

 	 	$mainframe =& JFactory::getApplication();
 	 	
 	 	// Get pagination request variables
 	 	$limit		=10;
 	 	$limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');

		$this->setState('limit',$limit);
 	 	$this->setState('limitstart',$limitstart);
 	 }
	  	 	 	
	/**
	 * Gets the pagination Object
	 * 
	 *	return JPagination object	 	 
	 */
	function &getPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}


	/**
	 * Return the total number of applications that is installed on this Joomla site
	 * 
	 *	return int Total count of applications	 	 
	 **/	 	
	function getTotal()
	{
		if (empty($this->_total))
		{
			$this->_total	= count( $this->getAvailableApps() );
		}
		return $this->_total;
	}
	
	// Return the title given its element name
	function getAppTitle($appname){
		static $instances = array();
		
		if(empty($instances[$appname]))
		{
			$db	 = &$this->getDBO();
			$sql = "SELECT name FROM #__plugins WHERE `element`=". $db->Quote($appname);
			$db->setQuery($sql);
			$instances[$appname] = $db->loadResult();
		}
		
		return $instances[$appname];
	}

	function setOrder( $userId, $newOrder )
	{
		$db	 = &$this->getDBO();

		foreach( $newOrder as $order )
		{
			// $order = 'appId,position,order'
			$order = explode(',', $order); 

			$query	= 'UPDATE ' . $db->nameQuote( '#__community_apps' ) . ' '
					. 'SET ' . $db->nameQuote( 'ordering' ) . '=' . $db->Quote( $order[2] ) . ', '
					.		$db->nameQuote( 'position' ) . '=' . $db->Quote( $order[1] ) . ' '
					. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $order[0] ) . ' '
					. 'AND ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userId );

			$db->setQuery( $query );
			$db->query();

			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
			}
		}
		return true;
	}
	
	/***
	 * Save the new application ordering in db. The caller should have called 
	 * 3 times in a row for all the 3 positions. Otherwise, old data might not
	 * be properly updated	 	 
	 */	 	
	function setOrdering( $userId , $position, $orderings )
	{
		$db	 = &$this->getDBO();
		
		foreach( $orderings as $appId => $order )
		{
			$query	= 'UPDATE ' . $db->nameQuote( '#__community_apps' ) . ' '
					. 'SET ' . $db->nameQuote( 'ordering' ) . '=' . $db->Quote( $order ) . ', '
					.		$db->nameQuote( 'position' ) . '=' . $db->Quote( $position ) . ' '
					. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $appId ) . ' '
					. 'AND ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userId );

			$db->setQuery( $query );
			$db->query();
	
			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
			}
		}
		return true;
	}
	
	/**
	 * Return the list of all user-apps, in proper order
	 * 
	 * @param	int		user id	
	 * @return	array	of objects	 
	 */	 	
	function getUserApps($userid, $state = 0)
	{
		$db	 = &$this->getDBO();
		
		$query	= 'SELECT `element` FROM #__plugins '
					. 'WHERE `published`=' . $db->Quote( 1 ) . ' '
					. 'AND `folder`=' . $db->Quote( 'community' );
		$db->setQuery( $query );
		$elementsResult	= $db->loadResultArray();
		$elements		= "'" . implode( $elementsResult , "','" ) . "'";

		$query	= 'SELECT DISTINCT a.* FROM #__community_apps AS a '
				. 'WHERE a.`userid`=' . $db->Quote( $userid ) . ' '
				. 'AND a.`apps`!="news_feed" '
				. 'AND a.`apps`!="profile" '
				. 'AND a.`apps`!="friends" ';
		
		if( !empty( $elements ) )
		{
			$query	.= 'AND a.`apps` IN (' . $elements . ') ';
		}
				
		$query	.= 'ORDER BY a.`ordering`';

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		// If no data yet, we load default apps
		// and add them to db
		if(empty($result))
		{
			$result = $this->getCoreApps();
			foreach($result as $row)
			{
				$row->userid = $userid;
				$db->insertObject('#__community_apps', $row);
				if($db->getErrorNum()) {
					JError::raiseError( 500, $db->stderr());
				}
			}
			
			// Reload the apps
			// @todo: potential duplicate code
			$sql = "SELECT * FROM #__community_apps "
				." WHERE `userid`=" . $db->Quote($userid)
				." AND `apps`!='news_feed' "
				." AND `apps`!='profile' "
				." AND `apps`!='friends' "
				." ORDER BY `ordering` ";
			$db->setQuery( $sql );
			$result = $db->loadObjectList();
			
			
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
			}
		}

		return $result;
	}

	/**
	 * Get user privacy setting
	 */	 	
	function getPrivacy($userid, $appname){
		static $privacy = array();
		
		if( empty($privacy[$userid]) )
		{
			// Preload all this user's privacy settings
			$db	 = &$this->getDBO();
			$sql = "SELECT privacy, apps FROM #__community_apps" 
				  ." WHERE `userid`=" . $db->Quote($userid);
				
			$db->setQuery( $sql );
			$db->query();
			
			if($db->getErrorNum()) 
			{
				JError::raiseError( 500, $db->stderr());
			}
			
		    $result = $db->loadObjectList();
		    $privacy[$userid] = array();
		    
		    foreach($result as $row)
			{
				$privacy[$userid][$row->apps] = $row->privacy;
			}
	    }
	    
	    if(empty($privacy[$userid][$appname]))
	    	$privacy[$userid][$appname] = 0;
	    	
	    $result = $privacy[$userid][$appname];
	    
		return $result;	
	}

	
	/**
	 * Store user privacy setting
	 */	 	
	function setPrivacy($userid, $appname, $val){
		$db	 = &$this->getDBO();
		$sql = "UPDATE #__community_apps SET `privacy`='{$val}' " 
			." WHERE `userid`=" . $db->Quote($userid) . " AND `apps`=" . $db->Quote($appname);
			
		$db->setQuery( $sql );
		$db->query();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	/**
	 * Return the list of all available apps. 
	 * @todo: need to display apps that are only permitted for the user	 
	 * 	 
	 * @return	array	of objects	 
	 */
	function getAvailableApps( $enableLimit = true )
	{
		$db		=& $this->getDBO();
	
		// This is bad, we load everything and slice them up
		$applications	= JPluginHelper::getPlugin('community');
		
		
		$apps	= array();
		
		// $applications are already filtered by the plugin helper.
		// where disabled applications are automatically filtered.
		for( $i = 0; $i < count( $applications ); $i++ )		
		{
			$row	= $applications[$i];
			$obj	= $this->getAppInfo( $row->name );
						
			//@rule: Application may be removed, so we need to test if the data really exists or not.
			if( isset( $obj->title ) )
			{
				$obj->title = JText::_($obj->title);
							
				if($obj->isApplication )
					$apps[]	= $obj;
			}
		}
		
		$totalApps = count($apps);
		
		if( $enableLimit )
		{
			$limitstart = $this->getState('limitstart');
			$limit		= $this->getState('limit');
			
			$apps = array_slice($apps, $limitstart, $limit);
		}
		
		// Appy pagination
		if(empty($this->_pagination))
		{
	 	    jimport('joomla.html.pagination');
	 	    $this->_pagination = new JPagination($totalApps, $this->getState('limitstart'), $this->getState('limit') );
	 	}
		return $apps;
	}
	
	function getAppInfo($appname)
	{
		static $instances = array();
		
		if(empty($instances[$appname]))
		{
			$app = new stdClass();
			$parser =& JFactory::getXMLParser('Simple');
			
			$xmlPath	= JPATH_PLUGINS . DS . 'community' . DS . $appname . '.xml';
			
			jimport('joomla.filesystem.file');
				
			if(!JFile::exists($xmlPath))
			{
				switch($appname)
				{
					case 'status':
						$app->title = 'Status';
						break;
					default:
						break;
				}
				return $app;
			}
			
			$parser->loadFile($xmlPath);
			$document =& $parser->document;
			
			// Get the title from db
			$app->title = JText::_($this->getAppTitle($appname));
			
			//$element =& $document->getElementByPath('name');
			//$app->title	= $element->data();
			
			$element =& $document->getElementByPath('description');
			$app->description = $element->data();
			
			$element =& $document->getElementByPath('author');
			$app->author = $element->data();
			
			$element =& $document->getElementByPath('version');
			$app->version = $element->data();
					
			$element =& $document->getElementByPath('creationdate');
			$app->creationDate = $element->data();
			
			// Determine whether the application is core application
			$params		= $this->getPluginParams( $this->getPluginId( $appname ) , null );
			$params		= new JParameter( $params );
			$app->coreapp		= $params->get( 'coreapp' );
			
			$element	=& $document->getElementByPath('isapplication');
			if($element)
				$app->isApplication	= ($element->data() == 'true');
			else
				$app->isApplication	= false;
	
			//$app->creationDate = 'test';
	
			$app->name = $appname;
			//$app->path = $appname;
			$instances[$appname] = $app;
		}
		
		return $instances[$appname];
	}
	
	/**
	 * Return list of core apps, as assigned by admin
	 */	 	
	function getCoreApps()
	{
		$applications	= array();
		$enableLimit	= false;
		$availableApps	= $this->getAvailableApps( $enableLimit );
		
		for( $i = 0; $i < count( $availableApps ); $i++ )
		{
			$application	=& $availableApps[$i];
			
			$params			= $this->getPluginParams( $this->getPluginId( $application->name ) );
			$params			= new JParameter( $params );

			if($params->get( 'coreapp' ) )
			{
				$obj		= new stdClass();
				$obj->apps	= $application->name;
				$applications[]	= $obj;
			}
			
		}
		return $applications;
	}
	
	function addNeededCoreApps( $userId )
	{
		$db		=& JFactory::getDBO();
		
		$query	= 'SELECT a.id, a.element FROM ' . $db->nameQuote('#__plugins') . ' AS a '
				. 'LEFT JOIN ' . $db->nameQuote( '#__community_apps' ) . ' AS b '
				. 'ON a.element=b.apps '
				. 'WHERE a.folder=' . $db->Quote( 'community' ) .' '
				. 'AND b.apps is null';

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		if( $result )
		{
			foreach( $result as $row )
			{
				$params			= $this->getPluginParams( $row->id );
				$params			= new JParameter( $params );
				if($params->get( 'coreapp' ) )
				{
					$obj			= new stdClass();
					$obj->apps		= $row->element;
					$obj->userid	= $userId;
					$db->insertObject( '#__community_apps', $obj );
					
					if($db->getErrorNum())
					{
						JError::raiseError( 500, $db->stderr());
					}
				}
			}
		}
		return true; 
	}
	
	function deleteApp($userid, $appid)
	{
		$db	 = &$this->getDBO();
		$sql = "DELETE FROM `#__community_apps` WHERE `userid`=" . $db->Quote($userid) . " AND `id`=" . $db->Quote($appid);
		$db->setQuery( $sql );
		$result = $db->query();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		return true;
	}
	
	/**
	 * Add new apps for this user
	 */	 	
	function addApp($userid, $appName, $position='content')
	{
		$db	 = &$this->getDBO();
		
		// @todo: make sure this apps is not inserted yet
		$sql = "SELECT count(*) FROM #__community_apps WHERE `userid`=" . $db->Quote($userid) 
			 . " AND `apps`=" . $db->Quote($appName);
		$db->setQuery( $sql );
		$exist = $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!$exist){
			// Fix the position to the last spot
			// @todo: make sure this apps is not inserted yet
			$sql = "SELECT count(*) FROM #__community_apps WHERE `userid`=" . $db->Quote($userid) 
				 . " AND `position`=" . $db->Quote($position);
			$db->setQuery( $sql );
			$currentPost = $db->loadResult();
			
			
			$sql = "INSERT INTO #__community_apps SET `userid`=" . $db->Quote($userid) . ", "
				 . " `apps`=" . $db->Quote($appName) . ", "
				 . " `position`=" . $db->Quote($position). ", "
				 . " `ordering`=" . $db->Quote($currentPost);

			$db->setQuery( $sql );
			$result = $db->query();
			
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
			}
		}
		return true;
	}

	/**
	 * Return parameter object of the given app
	 */		
	function getUserAppParams( $id , $userId = null )
	{
		$db		=& $this->getDBO();
		
		$query	= 'SELECT ' . $db->nameQuote( 'params' ) . ' '
				. 'FROM ' . $db->nameQuote( '#__community_apps' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $id );

		if( !is_null( $userId ) )
		{
			$query	.= ' AND ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userId );
		}
		$db->setQuery($query);
		$result	= $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		return $result;
	}
	
	/**
	 * Return parameter object of the given app
	 */	 	
	function getPluginParams( $pluginId )
	{
		$db		= &$this->getDBO();
		
		$query	= 'SELECT ' . $db->nameQuote( 'params' ) . ' '
				. 'FROM ' . $db->nameQuote( '#__plugins' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $pluginId );

		$db->setQuery( $query );

		$result = $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		return $result;
	}
	
	/**
	 * Return default parameter of the given application from Joomla.
	 * 
	 * param	string	name	The element of the application
	 **/
// 	function getDefaultParams( $element )
// 	{
// 		$db		=& $this->getDBO();
// 		
// 		$query	= 'SELECT ' . $db->nameQuote( 'params' ) . ' FROM ' 
// 				. $db->nameQuote( '#__plugins' ) . ' WHERE '
// 				. $db->nameQuote( 'element' )
// 	}
	/**
	 * Return parameter object of the given app
	 */	 	
	function storeParams($id, $params){
		$db	 = &$this->getDBO();
		$sql = "UPDATE #__community_apps SET  `params`=" . $db->Quote($params)
			  ." WHERE `id`=" . $db->Quote($id);
			  
		$db->setQuery( $sql );
		$db->query();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		return true;
	}
	
	/**
	 * Return true if the user own the given appid
	 */	 	
	function isOwned($userid, $appid)
	{
		$db	 = &$this->getDBO();
		
		$query	= 'SELECT COUNT(*) FROM '
				. $db->nameQuote( '#__community_apps' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $appid ) . ' '
				. 'AND ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userid );
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		return $result;
	}
	
	/**
	 * Return true if the user already enable
	 */	 	
	function isAppUsed($userid, $apps){
		$db	 = &$this->getDBO();
		$sql = "SELECT count(*) FROM #__community_apps WHERE `apps`=" . $db->Quote($apps) . " AND `userid`=" . $db->Quote($userid);
		$db->setQuery( $sql );
		$result = ($db->loadResult() > 0) ? true : false;
		
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}
	
	/**
	 * Return the app name given the app id 
	 * @param	int		row id in __community_apps
	 */	 	
	function getAppName($id){
		$db	 = &$this->getDBO();
		$sql = "SELECT `apps` FROM #__community_apps WHERE `id`=" . $db->Quote($id);
		$db->setQuery( $sql );
		$result = $db->loadResult();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		return $result;
	}

	/**
	 * Return the application id in Joomla's plugin table.
	 *
	 * @param	string	Element of the plugin.
	 */	 
	function getPluginId( $element )
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT ' . $db->nameQuote( 'id' ) . ' ' 
				. 'FROM ' . $db->nameQuote( '#__plugins' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'element' ) . '=' . $db->Quote( $element );

		$db->setQuery( $query );

		$result = $db->loadResult();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}
	
	/**
	 * return the position of the given app.
	 */	 	
	function getUserAppPosition($userid, $element)
	{
	
		$db		= &$this->getDBO();
		$query	= 'SELECT ' . $db->nameQuote( 'position' ) . ' FROM ' . $db->nameQuote( '#__community_apps' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'apps' ) . '=' . $db->Quote( $element )
				. '	AND ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userid );

		$db->setQuery( $query );

		$result = $db->loadResult();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		// if empty, then it is a core apps and its position is set by the admin
		if(empty($result))
			$result = 'content';
			
		return $result;
	}
	
	function getUserApplicationId( $element , $userId = null )
	{
		$db		= &$this->getDBO();
		$query	= 'SELECT ' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( '#__community_apps' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'apps' ) . '=' . $db->Quote( $element );

		if( !is_null($userId) )
			$query	.= ' AND ' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userId );
		
		$db->setQuery( $query );

		$result = $db->loadResult();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		return $result;
	}
	
	function checkObsoleteApp($obsoleteApp)
	{
		$db		= &$this->getDBO();
		$query	= 'SELECT ' . $db->nameQuote( 'published' ) . ' FROM ' . $db->nameQuote( '#__plugins' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'element' ) . '=' . $db->Quote( $obsoleteApp );		
		$db->setQuery( $query );
		$result = $db->loadResult();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		return $result;
	}
	
	function removeObsoleteApp($obsoleteApp)
	{
		$db		= &$this->getDBO();
		$query	= 'DELETE FROM ' . $db->nameQuote( '#__community_apps' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'apps' ) . '=' . $db->Quote( $obsoleteApp );		
		$db->setQuery( $query );
		$result = $db->query();
		
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		return $result;
	}

	public function hasConfig( $element )
	{
		jimport('joomla.filesystem.file' );
		
		return JFile::exists( JPATH_PLUGINS . DS . 'community' . DS . JString::trim( $element ) . DS . 'config.xml' ); 
	} 
}