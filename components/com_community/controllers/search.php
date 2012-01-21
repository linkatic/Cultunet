<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.utilities.date');

class CommunitySearchController extends CommunityBaseController
{
	var $_icon = 'search';

	public function ajaxRemoveFeatured( $memberId )
	{
		$objResponse	= new JAXResponse();
		CFactory::load( 'helpers' , 'owner' );
		
		$my			= CFactory::getUser();
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		if( COwnerHelper::isCommunityAdmin() )
		{
			$model	=& CFactory::getModel('Featured');

			CFactory::load( 'libraries' , 'featured' );
			$featured	= new CFeatured( FEATURED_USERS );
			$my			= CFactory::getUser();
			
			if($featured->delete($memberId))
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC USER REMOVED FROM FEATURED'));	
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC ERROR REMOVING USER FROM FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons	= '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
	public function ajaxAddFeatured( $memberId )
	{
		$objResponse	= new JAXResponse();
		CFactory::load( 'helpers' , 'owner' );
		
		$my			= CFactory::getUser();
		if($my->id == 0)
		{
			return $this->ajaxBlockUnregister();
		}
		
		if( COwnerHelper::isCommunityAdmin() )
		{
			$model	=& CFactory::getModel('Featured');
			
			if( !$model->isExists( FEATURED_USERS , $memberId ) )
			{
				CFactory::load( 'libraries' , 'featured' );
			$featured	= new CFeatured( FEATURED_USERS );
				$member		= CFactory::getUser($memberId);
				$featured->add( $memberId , $my->id );
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::sprintf('CC MEMBER IS FEATURED', $member->getDisplayName() ));
			}
			else
			{
				$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC USER ALREADY FEATURED'));
			}
		}
		else
		{
			$objResponse->addAssign('cWindowContent', 'innerHTML', JText::_('CC NOT ALLOWED TO ACCESS SECTION'));
		}
		$buttons	= '<input type="button" class="button" onclick="window.location.reload();" value="' . JText::_('CC BUTTON CLOSE') . '"/>';
		
		$objResponse->addScriptCall( 'cWindowActions' , $buttons );
		return $objResponse->sendResponse();
	}
	
	public function display()
	{
		$this->search();
	}
	
	/**
	 * Old advance search.
	 */	 		
	public function advsearch()
	{
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'profile.php');
		
	
		global $option,$context;
		$mainframe =& JFactory::getApplication();
		
		$data	= new stdClass();
		$view	=& $this->getView ('search');
		$model	=& $this->getModel('search');
		$profileModel =& $this->getModel('profile');

		$document	=& JFactory::getDocument();

		$fields	=& $profileModel->getAllFields();
		
		$search = JRequest::get('get');
		
		//prefill the seach values.
		if(isset($search)){
			foreach($fields as $group)
			{
				$field = $group->fields;
			
				for($i = 0; $i <count($field); $i++){
					$fieldid	= $field[$i]->id;
					if(!empty($search['field'.$fieldid])){
						$tmpEle = $search['field'.$fieldid];
						if(is_array($tmpEle)){
							$tmpStr = "";
							foreach($tmpEle as $ele){
								$tmpStr .= $ele.',';
							}
							$field[$i]->value = $tmpStr;
						} else {
							$field[$i]->value = $search['field'.$fieldid];
						}
					}
				}//end for i
			}//end foreach
		}
		
		$data->fields		=& $fields;
		
		if(isset($search)){
			$model =& $this->getModel('search');
			$data->result	= $model->searchPeople( $search );
		}
		
		$data->pagination	=& $model->getPagination();
		
		echo $view->get('search',$data);	
	}
	
	public function search()
	{
		CFactory::load( 'libraries' , 'profile' );
	
		$mainframe =& JFactory::getApplication();
		
		$data			= new stdClass();
		$view			= $this->getView ('search');
		$model			= $this->getModel('search');
		$profileModel	= $this->getModel('profile');

		$fields			= $profileModel->getAllFields();
		
		$search			= JRequest::get('REQUEST');
		$data->query	= JRequest::getVar( 'q', '', 'REQUEST' );
		$avatarOnly		= JRequest::getVar( 'avatar' , '' );
		
		//prefill the seach values.
		if(isset($search))
		{
			foreach($fields as $group)
			{
				$field = $group->fields;
				
				for($i = 0; $i <count($field); $i++)
				{
					$fieldid    = $field[$i]->id;
					if(!empty($search['field'.$fieldid]))
					{
						$tmpEle = $search['field'.$fieldid];
					
						if(is_array($tmpEle))
						{
							$tmpStr = "";
							foreach($tmpEle as $ele)
							{
								$tmpStr .= $ele.',';
							}
							$field[$i]->value = $tmpStr;
						}
						else
						{
							$field[$i]->value = $search['field'.$fieldid];
						}
					}
				}//end for i
			}//end foreach
		}
		
		$data->fields		=& $fields;
		
		if(isset($search))
		{
			$model =& $this->getModel('search');
			$data->result	= $model->searchPeople( $search , $avatarOnly );
			
			//pre-load cuser.
			$ids	= array();
			if(! empty($data->result))
			{
				foreach($data->result as $item)
				{
					$ids[]	= $item->id;
				}
				
				CFactory::loadUsers($ids);
			}
		}
		
		$data->pagination 	= $model->getPagination();
		
		echo $view->get('search',$data);	
	}
	
	
	/**
	 * Site wide people browser
	 */	 	
	public function browse(){
		$view =& $this->getView ('search');
		echo $view->get(__FUNCTION__, null);
	}

	// search by a single field
	public function field()
	{
		require_once (JPATH_COMPONENT.DS.'libraries'.DS.'profile.php');

		global $option,$context;
		$mainframe =& JFactory::getApplication();
		
		$data	= new stdClass();
		$view	=& $this->getView ('search');
		$searchModel	=& $this->getModel('search');
		$profileModel	=& $this->getModel('profile');
		
		$document	=& JFactory::getDocument();

		$fields		=& $profileModel->getAllFields();
		$searchFields = JRequest::get('get');
		
		// Remove non-search field
		if(isset($searchFields['option'])) 	unset($searchFields['option']);
		if(isset($searchFields['view'])) 	unset($searchFields['view']); 
		if(isset($searchFields['task'])) 	unset($searchFields['task']);
		if(isset($searchFields['Itemid'])) 	unset($searchFields['Itemid']);
		if(isset($searchFields['format'])) 	unset($searchFields['format']);
		
		if(count($searchFields) > 0)
		{
			$keys	= array_keys($searchFields);
			$model	=& CFactory::getModel( 'Profile' );
			$table	=& JTable::getInstance( 'ProfileField' , 'CTable' );
			$table->load( $model->getFieldId( $keys[0] ) );
			
			if( !$table->visible || !$table->published )
			{
				$mainframe->enqueueMessage( JText::_('CC FIELD NOT SEARCHABLE') , 'error' );
				return;
			}
			$data->result = $searchModel->searchByFieldCode($searchFields);

			echo $view->get('field', $data);	
		}

	}
	
	/**
	 * New custom search which renamed to advance search.
	 */	 	
	public function advanceSearch()
	{
		$view	=& $this->getView('search');
		$my		= CFactory::getUser();
		$config	=& CFactory::getConfig();
		
		if($my->id == 0 && !$config->get('guestsearch'))
		{
			return $this->blockUnregister();
		}

		echo $view->get('advanceSearch');
	}
}