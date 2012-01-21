<?php
/**
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CWall
{
	function _processWallContent($comment)
	{
		// Convert video link to embedded video
		CFactory::load('helpers' , 'videos');
		$comment = CVideosHelper::getVideoLink($comment);
		
		return $comment;
	}
	
	/**
	 * Method to get the walls HTML form
	 * 
	 * @param	userId
	 * @param	uniqueId
	 * @param	appType
	 * @param	$ajaxFunction	Optional ajax function
	 **/	 	
	function getWallInputForm( $uniqueId , $ajaxAddFunction, $ajaxRemoveFunc, $viewAllLink ='' )
	{
		$my = CFactory::getUser();
		
		// Hide the input form completely from visitors
		if($my->id == 0)
			return '';

		$tmpl		= new CTemplate();

		$tmpl->set( 'uniqueId'		, $uniqueId );
		$tmpl->set( 'viewAllLink'	, $viewAllLink );
		$tmpl->set( 'ajaxAddFunction'	, $ajaxAddFunction );
		$tmpl->set( 'ajaxRemoveFunc'	, $ajaxRemoveFunc);
		return $tmpl->fetch( 'wall.form' );	
	}

	function saveWall( $uniqueId , $message , $appType , &$creator , $isOwner , $processFunc = '', $templateFile = 'wall.content' , $wallId = 0 )
	{
		$my = CFactory::getUser();

		// Add some required parameters, otherwise assert here
		CError::assert( $uniqueId, '' , '!empty' , __FILE__ , __LINE__ );
		CError::assert( $appType, '' , '!empty' , __FILE__ , __LINE__ );
		CError::assert( $message, '' , '!empty' , __FILE__ , __LINE__ );
		CError::assert( $my->id, '', '!empty' , __FILE__ , __LINE__ );

		// Load the models
		CFactory::load( 'models' , 'wall' );
		CFactory::load( 'helpers' , 'url' );
		$wall				=& JTable::getInstance( 'Wall' , 'CTable' );
		$wall->load( $wallId );

		
		if( $wallId == 0 )
		{	
			// Get current date
			$now				=& JFactory::getDate();
			$now				= $now->toMySQL();
			
			// Set the wall properties
			$wall->type			= $appType;
			$wall->contentid	= $uniqueId;
			$wall->post_by		= $creator->id;
			
			$wall->date			= $now;
			$wall->published	= 1;
			
			// @todo: set the ip address
			$wall->ip			= $_SERVER['REMOTE_ADDR'];
		}
		$wall->comment		= $message;
		
		// Store the wall message
		$wall->store();

		// Convert it to array so that the walls can be processed by plugins
		$args 			= array();
		$args[0]		=& $wall;

		//Process wall comments
		CFactory::load('libraries', 'comment');
		$comment		= new CComment();
		$wallComments	= $wall->comment;
		$wall->comment  = $comment->stripCommentData($wall->comment);
 			
		// Trigger the wall comments
		CWall::triggerWallComments( $args );
		
		$wallData	= new stdClass();
		
		$wallData->id		= $wall->id;
		$wallData->content	= CWallLibrary::_getWallHTML( $wall , $wallComments , $appType, $isOwner , $processFunc , $templateFile );

		CFactory::load( 'helpers' , 'string' );
		$wallData->content	= CStringHelper::replaceThumbnails($wallData->content);
				
		return $wallData;
	}
	
	/**
	 * Return html-free summary of the wall content
	 */	 	
	static function getWallContentSummary($wallId)
	{
		CFactory::load('libraries', 'comment');
		CFactory::getModel( 'wall' );
		$config   = CFactory::getConfig();
		
		$wall =& JTable::getInstance( 'Wall' , 'CTable' );
		$wall->load( $wallId );
		
		$comment = new CComment();
		$wall->comment = $comment->stripCommentData($wall->comment);
		
		$tmpl	= new CTemplate();
		$tmpl->set( 'comment' , JString::substr($wall->comment, 0, $config->getInt('streamcontentlength')) );
		$html	= $tmpl->fetch( 'activity.wall.post' );
		return $html;
	}

	public function canComment( $appType , $uniqueId )
	{
		$my			=& CFactory::getUser();
		$allowed	= false;
		
		switch( $appType )
		{
			case 'groups':
				$group	=& JTable::getInstance( 'Group' , 'CTable' );
				$group->load( $uniqueId );
				
				$allowed	= $group->isMember( $my->id );
				break;
			default:
				$allowed	= true;
				break;
		}
		return $allowed;
	}
	
	/**
	 * Fetches the wall content template and returns the wall data in HTML format
	 *
	 * @param	appType			The application type to load the walls from
	 * @param	uniqueId		The unique id for the specific application	 
	 * @param	isOwner			Boolean value if the current browser is owner of the specific app or profile
	 * @param	limit			The limit to display the walls
	 * @param	templateFile	The template file to use.
	 **/	 	
	function getWallContents( $appType , $uniqueId , $isOwner , $limit = 0 , $limitstart = 0, $templateFile = 'wall.content' , $processFunc = '' )
	{
		CError::assert( $appType , '' , '!empty' , __FILE__ , __LINE__ );
		CError::assert( $uniqueId , '' , '!empty' , __FILE__ , __LINE__ );

		$html	= '';
		$model	=& CFactory::getModel( 'wall' );
		
		//@rule: If limit is not set, then we need to use Joomla's limit
		if( $limit == 0 )
		{
			$jConfig	=& JFactory::getConfig();
			$limit		= $jConfig->getValue( 'list_limit' );
		}

		$walls	= $model->getPost( $appType , $uniqueId , $limit, $limitstart);

		if( $walls )
		{	
			//Process wall comments
			CFactory::load('libraries', 'comment');
			$wallComments	= array();
			$comment		= new CComment();
			
			for( $i = 0 ; $i < count( $walls ); $i++ )
			{
				$wall			= $walls[ $i ];
				$wallComments[]	= $wall->comment;
				$wall->comment  = $comment->stripCommentData($wall->comment);
			}
 			
			 // Trigger the wall applications / plugins
			CWall::triggerWallComments( $walls );
			
			for( $i = 0; $i < count( $walls ); $i++ )
			{
				$html	.= CWallLibrary::_getWallHTML( $walls[$i] , $wallComments[ $i ] , $appType , $isOwner , $processFunc , $templateFile );
			}
		}
		
		return $html;
	}
	
	function _getWallHTML( $wall , $wallComments , $appType , $isOwner , $processFunc , $templateFile )
	{
		CFactory::load( 'helpers' , 'url' );
		CFactory::load('helpers' , 'user');
		CFactory::load('helpers' , 'videos');
		CFactory::load( 'libraries' , 'comment' );
		CFactory::load( 'helpers' , 'owner' );
		$user	= CFactory::getUser( $wall->post_by );
		$date	= CTimeHelper::getDate( $wall->date );

		$config			=& CFactory::getConfig();
		
		// @rule: for site super administrators we want to allow them to view the remove link
		$isOwner	= COwnerHelper::isCommunityAdmin() ? true : $isOwner;
		$isEditable	= CWall::isEditable( $processFunc , $wall->id );

		// Apply any post processing on the content 
		$wall->comment = CWallLibrary::_processWallContent($wall->comment);
		$commentsHTML	= '';
		
		$comment		= new CComment();
		// If the wall post is a user wall post (in profile pages), we 
		// add wall comment feature
		if( $appType == 'user' || $appType == 'groups' || $appType == 'events' )
		{
			$commentsHTML	= $comment->getHTML( $wallComments , 'wall-cmt-'.$wall->id , CWall::canComment( $wall->type , $wall->contentid ) );
		}

		$avatarHTML		= CUserHelper::getThumb( $wall->post_by , 'avatar' );

		// @rule: We only allow editing of wall in 15 minutes
		$now		= JFactory::getDate();
		$interval		= CTimeHelper::timeIntervalDifference( $wall->date , $now->toMySQL() );
		$interval		= COMMUNITY_WALLS_EDIT_INTERVAL - abs( $interval );
		$editInterval	= round( $interval / 60 );
		
		// Create new instance of the template
		$tmpl	= new CTemplate();
		$tmpl->set( 'id'		, $wall->id );
		$tmpl->set( 'author'	, $user->getDisplayName() );
		$tmpl->set( 'avatarHTML', $avatarHTML );
		$tmpl->set( 'authorLink', CUrlHelper::userLink( $user->id ) );
		$tmpl->set( 'created'	, $date->toFormat( JText::_('DATE_FORMAT_LC2') ) );
		$tmpl->set( 'content'	, $wall->comment);
		$tmpl->set( 'commentsHTML'	, $commentsHTML );
		$tmpl->set( 'avatar'	, $user->getThumbAvatar() );
		$tmpl->set( 'isMine'	, $isOwner );
		$tmpl->set( 'isEditable'	, $isEditable );
		$tmpl->set( 'editInterval'	, $editInterval );
		$tmpl->set( 'processFunc'	, $processFunc );
		$tmpl->set( 'config'	, $config );
		
		return $tmpl->fetch( $templateFile );
	}
	
	function getViewAllLinkHTML($link, $count=null)
	{
		if (!$link) return '';
		
		$tmpl = new CTemplate();
		$tmpl->set( 'viewAllLink', $link );
		$tmpl->set( 'count', $count );
		return $tmpl->fetch('wall.misc');
	}
	
	function getWallCount($appType , $uniqueId)
	{
		$model	=& CFactory::getModel( 'wall' );
		$count	= $model->getCount($uniqueId , $appType);
		return $count;
	}
	
	function isEditable( $processFunc , $wallId )
	{
		$func	= explode( ',' , $processFunc );
		
		if( count($func) < 2 )
		{
			return false;
		}
		
		$controller	= $func[0];
		$method		= 'edit' . $func[1] . 'Wall';

		if( count( $func ) > 2 )
		{
			//@todo: plugins
		}
		
		return CWall::_callFunction( $controller , $method , array( $wallId ) );
	}
	
	function _checkWallFunc( $processFunc )
	{

	}
	
	function _callFunction( $controller , $method , $arguments )
	{
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . 'controller.php' );
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'controllers' . DS . JString::strtolower( $controller ) . '.php' );

		$controller	= JString::ucfirst( $controller );
		$controller	= 'Community' . $controller . 'Controller';
		$controller	= new $controller();

		// @rule: If method not exists, we need to do some assertion here.
		if( !method_exists( $controller, $method ) )
		{
			JError::raiseError( 500 , JText::_( 'Method not found' ) );
		}

		return call_user_func_array( array( $controller , $method ) , $arguments );
	}
		
	function addWallComment()
	{
		
	}

	/**
	 * Formats the comment in the rows
	 * 
	 * @param Array	An array of wall objects	 	 
	 **/	 
	function triggerWallComments( &$rows )
	{
		CError::assert( $rows , 'array', 'istype', __FILE__ , __LINE__ );
		
		require_once( COMMUNITY_COM_PATH.DS.'libraries' . DS . 'apps.php' );
		$appsLib	=& CAppPlugins::getInstance();
		$appsLib->loadApplications();
		
		for( $i = 0; $i < count( $rows ); $i++ )
		{
			$args 	= array();
			$args[]	=& $rows[ $i ];

			$appsLib->triggerEvent( 'onWallDisplay' , $args );
		}
		return true;
	}
}

/**
 * Maintain classname compatibility with JomSocial 1.6 below
 */ 
class CWallLibrary extends CWall
{}