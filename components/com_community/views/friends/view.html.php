<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.arrayhelper');
jimport( 'joomla.html.html');

class CommunityViewFriends extends CommunityView
{
	function _addSubmenu(){
		$mySQLVer	= 0;
	
		if(JFile::exists(JPATH_COMPONENT.DS.'libraries'.DS.'advancesearch.php'))
		{	
			require_once (JPATH_COMPONENT.DS.'libraries'.DS.'advancesearch.php');
			$mySQLVer	= CAdvanceSearch::getMySQLVersion();
		}
	
		$this->addSubmenuItem('index.php?option=com_community&view=friends', JText::_('CC SHOW ALL FRIENDS'));
		$this->addSubmenuItem('index.php?option=com_community&view=search', JText::_('CC SEARCH FRIENDS'));		
		if($mySQLVer >= 4.1 )
			$this->addSubmenuItem('index.php?option=com_community&view=search&task=advancesearch', JText::_('CC CUSTOM SEARCH'));
		$this->addSubmenuItem('index.php?option=com_community&view=friends&task=invite', JText::_('CC INVITE FRIENDS'));
		$this->addSubmenuItem('index.php?option=com_community&view=friends&task=sent', JText::_('CC REQUEST SENT'));
		$this->addSubmenuItem('index.php?option=com_community&view=friends&task=pending', JText::_('CC PENDING APPROVAL'));
	}

	function showSubmenu(){
		$this->_addSubmenu();
		parent::showSubmenu();
	}
	
	/**
	 * DIsplay list of friends
	 * 
	 * if no $_GET['id'] is set, we're viewing our own friends	 	 
	 */	 	
	function friends($data = null)
	{
		// Load window library
		CFactory::load( 'libraries' , 'window' );
		
		// Load required filterbar library that will be used to display the filtering and sorting.
		CFactory::load( 'libraries' , 'filterbar' );

		// Load necessary window css / javascript headers.
		CWindow::load();
		
		$mainframe =& JFactory::getApplication();
		$my	= CFactory::getUser();
		$id = JRequest::getCmd('userid', 0 );
		
		if( $id == 0 )
		{
			$id	= $my->id;
		}
		// Display mini header if user is viewing other user's friend
		if( $id != $my->id )
		{
			$this->attachMiniHeaderUser( $id );
		}

		/* begin: COMMUNITY_FREE_VERSION */
		if( !COMMUNITY_FREE_VERSION ) {
			$feedLink = CRoute::_('index.php?option=com_community&view=friends&format=feed');
			$feed = '<link rel="alternate" type="application/rss+xml" title="' . JText::_('CC SUBSCRIBE TO FRIENDS FEEDS') . '" href="'.$feedLink.'"/>';
			$mainframe->addCustomHeadTag( $feed );
		}
		/* end: COMMUNITY_FREE_VERSION */

		$friendsModel	=& CFactory::getModel('friends');
		$user			= CFactory::getUser($id);
		$params 		= $user->getParams();
		CFactory::load( 'helpers' , 'friends' );
			
		$people  	=& CFactory::getModel( 'search' );
		$userModel 	=& CFactory::getModel( 'user' );
		$avatar	 	=& CFactory::getModel( 'avatar' );
		$friends 	=& CFactory::getModel( 'friends' );
		$sorted		= JRequest::getVar( 'sort' , 'latest' , 'GET' );
		$filter		= JRequest::getWord( 'filter' , 'all' , 'GET' );
		
		CFactory::load('helpers', 'friends');
		$rows 		= $friends->getFriends( $id , $sorted , true , $filter );
		$isMine		= ( ($id == $my->id) && ($my->id != 0) );
		$document	=& JFactory::getDocument();
		
		$this->addPathway(JText::_('CC FRIENDS'), CRoute::_('index.php?option=com_community&view=friends'));
		$this->addPathway(JText::sprintf('CC ALL FRIENDS', $user->getDisplayName()));
		
		// Hide submenu if we are viewing other's friends
		if( $isMine )
		{
			$this->showSubmenu();
			$document->setTitle(JText::_('CC MY FRIENDS'));
		}
		else
		{
			$this->addSubmenuItem('index.php?option=com_community&view=profile&userid=' . $user->id , JText::_('CC BACK TO PROFILE'));
			parent::showSubmenu ();
			
			$document->setTitle(JText::sprintf('CC ALL FRIENDS', $user->getDisplayName()));
		}

		$sortItems =  array(
							'latest' 		=> JText::_('CC RECENT FRIENDS') , 
 							'online'		=> JText::_('CC ONLINE') );
		
		$config			= CFactory::getConfig();
		$filterItems	= array();
		
		if( $config->get('alphabetfiltering') )
		{
			$filterItems	=  array(
								'all' 	=> JText::_('CC ALL') ,
								'abc' 	=> JText::_('CC ABC') , 
								'def'	=> JText::_('CC DEF') ,
								'ghi'	=> JText::_('CC GHI') ,
								'jkl'	=> JText::_('CC JKL') ,
								'mno'	=> JText::_('CC MNO') ,
								'pqr'	=> JText::_('CC PQR') ,
								'stu'	=> JText::_('CC STU') ,
								'vwx'	=> JText::_('CC VWX') ,
								'yz'	=> JText::_('CC YZ') , 
								'others'=> JText::_('CC OTHERS')
								);
		}
		
		// Check if friend is banned
		$blockModel	=& CFactory::getModel('block');

		$tmpl = new CTemplate();

		$resultRows = array();

		// @todo: preload all friends
		foreach($rows as $row)
		{
			$user = CFactory::getUser($row->id);

			$obj = clone($row);
			$obj->friendsCount  = $user->getFriendCount(); 
			$obj->profileLink	= CUrlHelper::userLink($row->id);
			$obj->isFriend		= true;
			$obj->isBlocked		= $blockModel->getBlockStatus($user->id,$my->id);
			$resultRows[] = $obj;
		}
		unset($rows);

		$tmpl->set( 'isMine'		, $isMine );
		$tmpl->setRef( 'my'			, $my );
		$tmpl->setRef( 'friends'		, $resultRows );

		// Should not show recently added filter to otehr people
 		$sortingHTML	= '';

		if($isMine)
		{
			$sortingHTML	= CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'latest' , $filterItems , 'all' );
		}

		$tmpl->set('sortings', $sortingHTML );
		$tmpl->set( 'config' , CFactory::getConfig() );
		$html = $tmpl->fetch('friends.list');

		$html .= '<div class="pagination-container">';
		$pagination	= $friends->getPagination();
		$html .= $pagination->getPagesLinks();
		$html .= '</div>';

		echo $html;
	}

	function add($data = null){
		
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CC FRIEND ADD'));
		?>
		<div class="app-box">
			<p><?php echo JText::sprintf('CC ADD USER AS FRIEND', $data->name );?></p>
			<form name="addfriend" method="post" action="">
				<div>
					<label><?php echo JText::sprintf('CC ADD PERSONAL MESSAGE TO' , $data->name ); ?></label>
				</div>
				
				<div>
					<textarea name="msg"></textarea>
				</div>
				
				<div>
					<input type="submit" class="button" name="submit" value="<?php echo JText::_('CC BUTTON ADD FRIEND');?>"/>
					<input type="submit" class="button" name="cancel" value="<?php echo JText::_('CC BUTTON CANCEL');?>"/>
				</div>
				<input type="hidden" class="button" name="id" value="<?php echo $data->id; ?>"/>
			</form>
		</div>
		<?php
	}
	
	function online($data = null)
	{
		// Load the toolbar
		$this->showHeader(JText::_('CC ONLINE FRIENDS'), 'generic');
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CC ONLINE FRIENDS TITLE'));
	}
	
	function sent($data = null)
	{
		$mainframe =& JFactory::getApplication();

		// Load window library
		CFactory::load( 'libraries' , 'window' );
		
		// Load necessary window css / javascript headers.
		CWindow::load();
		
		$config	=& CFactory::getConfig();
		$my	=& JFactory::getUser();
		if($my->id == 0)
		{
        	$mainframe->enqueueMessage(JText::_('CC PLEASE LOGIN'), 'error');
        	return; 
		}
		
		$this->addPathway(JText::_('CC FRIENDS'), CRoute::_('index.php?option=com_community&view=friends'));
		$this->addPathway(JText::_("CC TITLE WAITING AUTHORIZATION"), '');
		
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CC TITLE WAITING AUTHORIZATION'));
		$this->showSubMenu();

		$friends 	=& CFactory::getModel( 'friends' );
		
		$rows = !empty($data->sent) ? $data->sent : array();

		for( $i = 0; $i < count( $rows ); $i++ )
		{
			$row	=& $rows[$i];
			$row->user	= CFactory::getUser($row->id );
			$row->user->friendsCount  = $row->user->getFriendCount();
			$row->user->profileLink	= CUrlHelper::userLink($row->id);
		}

		$tmpl	= new CTemplate();
		$tmpl->set( 'my'	, $my );
		$tmpl->set( 'config', $config );
		$tmpl->set( 'rows' 	, $rows );
		echo $tmpl->fetch( 'friends.request' );
	}
	
	function deleteLink($controller,$method,$id){
		$deleteLink = '<a class="remove" onClick="if(!confirm(\'' . JText::_('CC CONFIRM DELETE FRIEND') . '\'))return false;" href="'.CUrl::build($controller,$method).'&fid='.$id.'">&nbsp;</a>';
		return $deleteLink;
	}
	
	/**
	 * Display a list of pending friend requests
	 **/	 	
	function pending($data = null)
	{
		if(!$this->accessAllowed('registered'))	return;	
	
		$mainframe =& JFactory::getApplication();
		$config		=& CFactory::getConfig();
		
		// Load window library
		CFactory::load( 'libraries' , 'window' );
		
		// Load necessary window css / javascript headers.
		CWindow::load();
		
		$my		= CFactory::getUser();
		
		if($my->id == 0)
		{
        	$mainframe->enqueueMessage( JText::_('CC PLEASE LOGIN'), 'error');
        	return; 
		}
		
		// Set pathway
		$this->addPathway(JText::_('CC FRIENDS'), CRoute::_('index.php?option=com_community&view=friends'));
		$this->addPathway(JText::_('CC AWAITING AUTHORIZATION'), '');
		
		// Set document title
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CC AWAITING AUTHORIZATION'));
        
        // Load submenu
		$this->showSubMenu();
		
		$friends 	=& CFactory::getModel( 'friends' );
		
		$rows = !empty($data->pending) ? $data->pending : array();

		for( $i = 0; $i < count( $rows ); $i++ )
		{
			$row	=& $rows[$i];
			$row->user	= CFactory::getUser($row->id );
			$row->user->friendsCount  = $row->user->getFriendCount();
			$row->user->profileLink	= CUrlHelper::userLink($row->id);
			$row->msg = $this->escape($row->msg);
		}
		
		$tmpl	= new CTemplate();
		$tmpl->set( 'rows' , $rows );
		$tmpl->setRef( 'my'	, $my );
		$tmpl->set( 'config' , $config );
		$tmpl->set( 'pagination' , $data->pagination );
		echo $tmpl->fetch( 'friends.pending' );
	}
	
	function addSuccess($data = null)
	{
		$this->addInfo( JText::sprintf( 'CC FRIEND WILL RECEIVE REQUEST', $data->name ) );
	
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CC FRIEND ADDED SUCCESSFULLY TITLE'));
	}
	
	/**
	 * Show the invite window
	 */	 	
	function invite()
	{
		$mainframe =& JFactory::getApplication();
		
		$document	=& JFactory::getDocument();
		$config		=& CFactory::getConfig();
        $document->setTitle(JText::sprintf('CC INVITE FRIENDS TITLE', $config->get('sitename') ));
        
        $my	  = CFactory::getUser();

		$this->showSubmenu();

		$post = (JRequest::getVar('action', '', 'POST') == 'invite') ? JRequest::get('POST') : array('message'=>'','emails'=>'');
		
		$pathway 	=& $mainframe->getPathway();
		$this->addPathway(JText::_('CC FRIENDS'), CRoute::_('index.php?option=com_community&view=friends'));
		$this->addPathway(JText::_('CC INVITE FRIENDS') , '');

		// Process the Suggest Friends
		// Load required filterbar library that will be used to display the filtering and sorting.
		CFactory::load( 'libraries' , 'filterbar' );
		$id			= JRequest::getCmd('userid', $my->id);
		$user		= CFactory::getUser($id);
		$sorted		= JRequest::getVar( 'sort' , 'suggestion' , 'GET' );
		$filter		= JRequest::getVar( 'filter' , 'suggestion' , 'GET' );
		$friends 	=& CFactory::getModel( 'friends' );
		
		$rows 		= $friends->getFriends( $id , $sorted , true , $filter );
		$resultRows = array();
		
		foreach($rows as $row)
		{
			$user = CFactory::getUser($row->id);
			
			$obj = clone($row);
			$obj->friendsCount  = $user->getFriendCount(); 
			$obj->profileLink	= CUrlHelper::userLink($row->id);
			$obj->isFriend		= true;
			$resultRows[] = $obj;
		}
		unset($rows);

		CFactory::load( 'libraries' , 'apps' );
		$app 		=& CAppPlugins::getInstance();
		$appFields	= $app->triggerEvent('onFormDisplay' , array('jsform-friends-invite'));
		$beforeFormDisplay	= CFormElement::renderElements( $appFields , 'before' );
		$afterFormDisplay	= CFormElement::renderElements( $appFields , 'after' );

		$tmpl	= new CTemplate();
		$tmpl->set( 'beforeFormDisplay', $beforeFormDisplay );
		$tmpl->set( 'afterFormDisplay'	, $afterFormDisplay );
		$tmpl->set( 'my' 	, $my );
		$tmpl->set( 'post' , $post );		
		$tmpl->setRef( 'friends'		, $resultRows );
		$tmpl->set( 'config' , CFactory::getConfig() );
		echo $tmpl->fetch( 'friends.invite' );		
	}
	
	function news()
	{
		// Load the toolbar
		$document =& JFactory::getDocument();
        $document->setTitle(JText::_('CCC FRIENDS NEWS'));
	}
}
