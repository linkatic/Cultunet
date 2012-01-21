<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.arrayhelper');
jimport( 'joomla.html.html');

class CommunityViewSearch extends CommunityView
{
	function _addSubmenu()
	{
		$mySQLVer	= 0;
		if(JFile::exists(JPATH_COMPONENT.DS.'libraries'.DS.'advancesearch.php'))
		{
			require_once (JPATH_COMPONENT.DS.'libraries'.DS.'advancesearch.php');
			$mySQLVer	= CAdvanceSearch::getMySQLVersion();
		}	
	
		// Only display related links for guests
		$my 		= CFactory::getUser();
		$config		=& CFactory::getConfig();
		
		if( $my->id == 0)
		{
			$this->addSubmenuItem('index.php?option=com_community&view=search', JText::_('CC SEARCH FRIENDS'));
			if($mySQLVer >= 4.1 && $config->get('guestsearch'))
				$this->addSubmenuItem('index.php?option=com_community&view=search&task=advancesearch', JText::_('CC CUSTOM SEARCH'));
		}
		else
		{
			$this->addSubmenuItem('index.php?option=com_community&view=friends', JText::_('CC SHOW ALL FRIENDS'));
			$this->addSubmenuItem('index.php?option=com_community&view=search', JText::_('CC SEARCH FRIENDS'));
			if($mySQLVer >= 4.1 )
				$this->addSubmenuItem('index.php?option=com_community&view=search&task=advancesearch', JText::_('CC CUSTOM SEARCH'));
			$this->addSubmenuItem('index.php?option=com_community&view=friends&task=invite', JText::_('CC INVITE FRIENDS'));
			$this->addSubmenuItem('index.php?option=com_community&view=friends&task=sent', JText::_('CC REQUEST SENT'));
			$this->addSubmenuItem('index.php?option=com_community&view=friends&task=pending', JText::_('CC PENDING APPROVAL'));
		}
	}

	function showSubmenu(){
		$this->_addSubmenu();
		parent::showSubmenu();
	}
	
	function search($data)
	{
		//return $this->search($data);
		
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'profile.php');
		require_once (JPATH_COMPONENT.DS.'helpers'.DS.'friends.php');
		
		$document	=& JFactory::getDocument();		
		$document->setTitle(JText::_('CC TITLE SEARCH FRIENDS'));
		$this->showSubMenu();
		$avatarOnly		= JRequest::getVar( 'avatar' , '' );
		$this->addPathway( JText::_('CC TITLE SEARCH FRIENDS') );
		$my				= CFactory::getUser();
		$friendsModel	=& CFactory::getModel('friends');
		$resultRows 	= array();
		
		$pagination = (!empty($data)) ? $data->pagination : '';
		
		$tmpl		= new CTemplate();
		for($i = 0; $i < count( $data->result ); $i++)
		{
			$row 				=& $data->result[$i];
			$user				= CFactory::getUser( $row->id );
			$row->profileLink	= CRoute::_('index.php?option=com_community&view=profile&userid=' . $row->id );
			$row->friendsCount	= $user->getFriendCount();
			$isFriend 			=  CFriendsHelper::isConnected ( $row->id, $my->id );
			
			$row->user      	= $user;
			$row->addFriend 	= ((! $isFriend) && ($my->id != 0) && $my->id != $row->id) ? true : false;

			$resultRows[] = $row;
		}
		$tmpl->set('data'		, $resultRows);
		$tmpl->set('sortings'	, '');
		$tmpl->set('pagination' , $pagination );

		CFactory::load( 'libraries' , 'tooltip' );
		//JHTML::_('behavior.tooltip');
		
		CFactory::load( 'libraries' , 'featured' );
		$featured		= new CFeatured( FEATURED_USERS );
		$featuredList	= $featured->getItemIds();
		
		$tmpl->set('featuredList' , $featuredList);
		
		CFactory::load( 'helpers' , 'owner' );
		$tmpl->set('isCommunityAdmin', COwnerHelper::isCommunityAdmin() );
		$tmpl->set('showFeaturedList' , false );
		$tmpl->set('my' , $my );
		$resultHTML 	= $tmpl->fetch('people.browse');
		unset( $tmpl );
		
		$tmpl 		= new CTemplate();	
		$tmpl->set( 'avatarOnly'	, $avatarOnly );
		$tmpl->set( 'results'		, $data->result );
		$tmpl->set( 'resultHTML'	, $resultHTML );
		$tmpl->set( 'query'			, $data->query );
		echo $tmpl->fetch( 'search' );
	}

	function browse($data=null)
	{
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'template.php');
		
		$mainframe	=& JFactory::getApplication();
		$document	=& JFactory::getDocument();	
		
		
		// Load required filterbar library that will be used to display the filtering and sorting.
		CFactory::load( 'libraries' , 'filterbar' );
		
		$this->addPathway( JText::_( 'CC BROWSE FRIENDS TITLE' ) , '' );
		
		
		$document->setTitle( JText::_( 'CC BROWSE FRIENDS TITLE' ) );
		
		CFactory::load( 'helpers' , 'friends' );
		CFactory::load( 'libraries' , 'template');
		CFactory::load( 'libraries' , 'tooltip' );
		CFactory::load( 'helpers' , 'owner' );		
		CFactory::load( 'libraries' , 'featured' );
		
		$my				= CFactory::getUser();
		$view			=& CFactory::getView('search');
		$searchModel  	=& CFactory::getModel('search');
		$userModel		=& CFactory::getModel('user');
		$avatar			=& CFactory::getModel('avatar');
		$friends		=& CFactory::getModel('friends');
		
		
		$tmpl		= new CTemplate();
		$sorted		= JRequest::getVar( 'sort' , 'latest' , 'GET' );
		$filter		= JRequest::getWord( 'filter' , 'all' , 'GET' );
		$rows		= $searchModel->getPeople( $sorted , $filter );
		
		$sortItems	=  array(
							'latest' 	=> JText::_('CC SORT LATEST') , 
							'online'	=> JText::_('CC SORT ONLINE') ,
							'alphabetical'	=> JText::_('CC SORT ALPHABETICAL')
							);

		$filterItems	= array();
		$config			= CFactory::getConfig();
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
		$html		= '';
		$totalUser	= $userModel->getMembersCount();
		$resultRows	= array();
		
		for($i = 0; $i < count($rows); $i++)
		{
			$row =& $rows[$i];
			
			$obj = clone($row);
			$user				= CFactory::getUser( $row->id );
			$obj->friendsCount  = $user->getFriendCount();
			$obj->user			= $user;
			$obj->profileLink	= CUrl::build( 'profile' , '' , array( 'userid' => $row->id ) );
			$isFriend =  CFriendsHelper::isConnected( $row->id, $my->id );
			
			$obj->addFriend 	= ((! $isFriend) && $my->id != $row->id) ? true : false;
		
			$resultRows[] = $obj;
		}

		$featured	= new CFeatured( FEATURED_USERS );
		$featuredList	= $featured->getItemIds();
		$config			= CFactory::getConfig();
		
		if( $config->get('alphabetfiltering') )
		{
			$sortingsHTML	= CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'latest' , $filterItems , 'all' );
		}
		else
		{
			$sortingsHTML	= CFilterBar::getHTML( CRoute::getURI(), $sortItems, 'latest' );
		}
		
		$tmpl->set('featuredList'		, $featuredList);		
		$tmpl->set( 'isCommunityAdmin'	, COwnerHelper::isCommunityAdmin() );
		$tmpl->set( 'featuredList'		,  $featuredList );
		$tmpl->set('data'				, $resultRows);
		$tmpl->set('sortings'			, $sortingsHTML );
		$tmpl->set( 'my'				, $my );
		$tmpl->set( 'totalUser'			, $totalUser );
		$tmpl->set( 'showFeaturedList' 	, true );
		$tmpl->set( 'pagination'		, $searchModel->getPagination() );
		echo $tmpl->fetch('people.browse');
	}
	
	function field($data)
	{
		$mainframe =& JFactory::getApplication();
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'template.php');		
		
		$searchFields = JRequest::get('get');
		
		// Remove non-search field
		if(isset($searchFields['option'])) 	unset($searchFields['option']);
		if(isset($searchFields['view'])) 	unset($searchFields['view']); 
		if(isset($searchFields['task'])) 	unset($searchFields['task']);
		if(isset($searchFields['Itemid'])) 	unset($searchFields['Itemid']);
		if(isset($searchFields['format'])) 	unset($searchFields['format']);
		
		$keys = array_keys($searchFields);
		$vals = array_values($searchFields);
		
		CFactory::load( 'helpers' , 'friends' );
		
		$document =& JFactory::getDocument();	
		
		$searchModel	=& CFactory::getModel('search');
		$profileModel	=& CFactory::getModel( 'profile' );
		$profileName	= $profileModel->getProfileName( $keys[0] );
		$profileName	= JText::_( $profileName );
		$document->setTitle( JText::sprintf( 'CC MEMBERS WITH FIELD', $profileName , $vals[0] ) );
		
		$rows = $data->result;
		
		
		$my		= CFactory::getUser();
		
		$resultRows = array();
		$friendsModel =& CFactory::getModel('friends');
		
		$tmpl = new CTemplate();
		for($i = 0; $i < count($rows); $i++){
		
			$row =& $rows[$i];
			
			$userObj			= CFactory::getUser( $row->id );
			$obj				= new stdClass();
			$obj->user			= $userObj;
			$obj->friendsCount  = $userObj->getFriendCount();
			$obj->profileLink	= CRoute::_('index.php?option=com_community&view=profile&userid=' . $row->id );
			$isFriend =  CFriendsHelper::isConnected( $row->id, $my->id );
			
			$obj->addFriend 	= ((! $isFriend) && ($my->id != 0) && $my->id != $row->id) ? true : false;
			
			$resultRows[] = $obj;
		}
		
		$pagination   = $searchModel->getPagination();
		
		$tmpl->set('data'		, $resultRows);
		$tmpl->set('sortings'	, '');
		$tmpl->set('pagination'	, $pagination);
		$tmpl->set('featuredList' , '');
		$tmpl->set('isCommunityAdmin','');
		$tmpl->set('my' , $my);		
		echo $tmpl->fetch('people.browse');
	}
	
	function advanceSearch()
	{		
		CFactory::load('libraries', 'advancesearch');			
		CFactory::load('libraries', 'messaging');		
		CFactory::load('helpers', 'friends');
					
		$document	=& JFactory::getDocument();
		$document->addStyleSheet("includes/js/calendar/calendar-mos.css");
		$document->addScript("includes/js/joomla.javascript.js");
		$document->addScript("includes/js/calendar/calendar_mini.js");
		$document->addScript("includes/js/calendar/lang/calendar-en-GB.js");
				
		$document->setTitle(JText::_('CC TITLE CUSTOM SEARCH'));
		$this->showSubMenu();
		
		$this->addPathway( JText::_('CC TITLE CUSTOM SEARCH') );		
		

		$my 		= CFactory::getUser();		
		$config		=& CFactory::getConfig();
		
		$result	= null;
		$fields = CAdvanceSearch::getFields();
		$data 	= new stdClass();
		
		$post 		= JRequest::get('GET');
		$keyList	= isset($post['key-list']) ? $post['key-list'] : '';
		$avatarOnly	= JRequest::getVar( 'avatar' , '' );
		
        if( JString::strlen($keyList) > 0)
        {

			//formatting the assoc array
			$filter			= array();
			$key			= explode(',', $keyList);
			$joinOperator	= $post['operator'];

			foreach($key as $idx)
			{
				$obj	= new stdClass();
				$obj->field		= $post['field'.$idx];
				$obj->condition	= $post['condition'.$idx];
				$obj->fieldType	= $post['fieldType'.$idx];
				
				if( $obj->fieldType == 'email')
				{
					$obj->condition	= 'equal';
				}
				
				// we need to check whether the value contain start and end kind of values.
				// if yes, make them an array.
				if(isset($post['value'.$idx.'_2']))
				{						
					if($obj->fieldType == 'date')
					{
						$startDate	= (empty($post['value'.$idx])) ? '01/01/1970' : $post['value'.$idx];
						$endDate	= (empty($post['value'.$idx.'_2'])) ? '01/01/1970' : $post['value'.$idx.'_2'];
																		
						$sdate		= explode('/', $startDate);
						$edate		= explode('/', $endDate);
												
						$obj->value		= array($sdate[2] . '-' . intval($sdate[1]) . '-' . $sdate[0] . ' 00:00:00',
												$edate[2] . '-' . intval($edate[1]) . '-' . $edate[0] . ' 23:59:59');
					} 
					else
					{
						$obj->value		= array($post['value'.$idx], $post['value'.$idx.'_2']);	
					}
				}
				else
				{
					if($obj->fieldType == 'date')
					{						
						$startDate	= (empty($post['value'.$idx])) ? '01/01/1970' : $post['value'.$idx];
						$sdate		= explode('/', $startDate);
						$obj->value	= $sdate[2] . '-' . intval($sdate[1]) . '-' . $sdate[0] . ' 00:00:00';
					}
					else if($obj->fieldType == 'checkbox')
					{
						if(empty($post['value'.$idx]))
						{
							//this mean user didnot check any of the option.
							$obj->value		= '';
						}
						else
						{
							$obj->value		= isset($post['value'.$idx]) ? implode(',', $post['value'.$idx]) : '';
						}
					}	
					else
					{
						$obj->value		= isset($post['value'.$idx]) ? $post['value'.$idx] : '';
					}
				}
				
				$filter[]	= $obj;
			}
			
			

			$data->search	= CAdvanceSearch::getResult($filter, $joinOperator, $avatarOnly );
			$data->filter	= $post;
		}
						
		$rows 		= (! empty($data->search)) ? $data->search->result : array();
		$pagination = (! empty($data->search)) ? $data->search->pagination : '';
		$filter 	= (! empty($data->filter)) ? $data->filter : array();
		
		
		$resultRows = array();
		$friendsModel =& CFactory::getModel('friends');		
		
		for($i = 0; $i < count($rows); $i++){
		
			$row =& $rows[$i];
						
			$obj				= new stdClass();
			$obj->user			=& $row;
			$obj->friendsCount  = $row->getFriendCount();
			$obj->profileLink	= CRoute::_('index.php?option=com_community&view=profile&userid=' . $row->id );
			$isFriend =  CFriendsHelper::isConnected( $row->id, $my->id );
			
			$obj->addFriend 	= ((! $isFriend) && ($my->id != 0) && $my->id != $row->id) ? true : false;						
			
			$resultRows[] = $obj;
		}
				
		$tmpl 		= new CTemplate();	
		$tmpl->set( 'fields', $fields);
		$tmpl->set( 'filter', $filter );
		$tmpl->set( 'avatarOnly' , $avatarOnly );
		
		if (class_exists('Services_JSON')) 
		{
			$json = new Services_JSON();				
			$tmpl->set( 'filterJson', $json->encode($filter) );
		}
		else
		{
			require_once (JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'pc_includes'.DS.'JSON.php');
			$json = new Services_JSON();				
			$tmpl->set( 'filterJson', $json->encode($filter) );
		}
		
		$searchForm		= $tmpl->fetch( 'search.advancesearch' );

		if( isset( $post['key-list'] ) )
		{
			//result template
			$tmplResult 		= new CTemplate();
			$tmplResult->set( 'data'		, $resultRows);
			$tmplResult->set( 'sortings'	, '');
			$tmplResult->set( 'pagination', $pagination );
			$tmplResult->set( 'filter', $filter );
		
			CFactory::load( 'libraries' , 'tooltip' );
			CFactory::load( 'helpers' , 'owner' );
			//JHTML::_('behavior.tooltip');
			
			CFactory::load( 'libraries' , 'featured' );
			$featured		= new CFeatured( FEATURED_USERS );
			$featuredList	= $featured->getItemIds();
			
			$tmpl->set('featuredList' , $featuredList);
			$tmplResult->set( 'my', $my );
			$tmplResult->set( 'showFeaturedList' , false );
			$tmplResult->set( 'featuredList' , $featuredList );
			
			$tmplResult->set( 'featuredList', $featuredList );
			$tmplResult->set( 'isCommunityAdmin', COwnerHelper::isCommunityAdmin() );
			$searchForm	.= $tmplResult->fetch('people.browse');
		}
		
		echo $searchForm; 
	}
}

?>
