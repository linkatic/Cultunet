<?php
/**
 * @category	Library
 * @package		JomSocial
 * @subpackage	user 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );

class CAdvanceSearch {

	var $_filter		= null;
	var $_data			= null;
	var $_pagination	= null;
	
	
	function &getFields()
	{
		$model		=& CFactory::getModel('profile');
		$fields		=& $model->getAllFields();
		
		$lang		=& JFactory::getLanguage();
		$lang->load( 'com_user', JPATH_ROOT );
		// we need to get the user name / email seprately as these data did not
		// exists in custom profile fields.
		
		$nameOptGroup				= new stdClass();
		$nameOptGroup->type 		= "group";
        $nameOptGroup->published 	= 1;
        $nameOptGroup->name 		= JText::_("Name");
        $nameOptGroup->visible 		= 1;
        
        $fields[JText::_("Name")] = $nameOptGroup;
				        
        $obj 			= new stdClass();
		$obj->type 		= "text";
        $obj->published = 1;
        $obj->name 		= JText::_("NAME");
        $obj->visible 	= 1;
        $obj->fieldcode = "username";
        $fields[$nameOptGroup->name]->fields[]		= $obj;
        
        $obj 			= new stdClass();
		$obj->type 		= "email";
        $obj->published = 1;
        $obj->name 		= JText::_("EMAIL");
        $obj->visible 	= 1;
        $obj->fieldcode = "useremail";
		$fields[$nameOptGroup->name]->fields[]		= $obj;
		
		return $fields;
	}
		
	function &getFieldList($fieldId)
	{
		$model		=& CFactory::getModel('search');
		$fieldList	= $model->getFieldList($fieldId);
		return $fieldList;
	}
	
	function getResult($filter = array(), $join='and' , $avatarOnly = '' )
	{
		$model	=& CFactory::getModel('search');
		$result		= $model->getAdvanceSearch($filter, $join , $avatarOnly );
		$pagination	= $model->getPagination();
		
		$obj = new stdClass();
		$obj->result	 = $result;
		$obj->pagination = $pagination;
		$obj->operator 	 = $join;
		
		return $obj;
	}
	
	function setFilter()
	{	
	}	
	
	/**
	 * method used to return the required condition selection.
	 * param	- field type - string
	 * return	- assoc array 	 	 
	 */	 	
	function &getFieldCondition($type)
	{
		$cond	= array();
		
		switch($type)
		{
			case 'date'		:
				$cond	= array(
							'between'	=> JText::_('CC CUSTOM BETWEEN'),
							'equal'		=> JText::_('CC CUSTOM EQUAL'),
							'notequal'	=> JText::_('CC CUSTOM NOT EQUAL'),
							'lessthanorequal'	=> JText::_('CC CUSTOM LESS THAN OR EQUAL'),
							'greaterthanorequal'	=> JText::_('CC CUSTOM GREATER THAN OR EQUAL')								
							);			
				break;
			case 'checkbox'	:
			case 'radio'	:
			case 'select'	:
			case 'singleselect'	:
			case 'list'		:
				$cond	= array(
							'equal'		=> JText::_('CC CUSTOM EQUAL'),
							'notequal'	=> JText::_('CC CUSTOM NOT EQUAL')								
							);
				break;
			case 'email'		:
				$cond	= array(
							'equal'		=> JText::_('CC CUSTOM EQUAL')
							);
				break;				
			case 'textarea'	:
			case 'text'		:
			default			:
				$cond	= array(
							'contain'	=> JText::_('CC CUSTOM CONTAIN'),
							'equal'		=> JText::_('CC CUSTOM EQUAL'),
							'notequal'	=> JText::_('CC CUSTOM NOT EQUAL')							
							);			
				break;
		}
		
		return $cond;
	}
	
	/**
	 * Method used to return the current MySQL version that running
	 * return - float
	 */	 	 	
	function getMySQLVersion()
	{
		$db	=& JFactory::getDBO();
		
		$query	= 'SELECT VERSION()';
		$db->setQuery($query);
		$result	= $db->loadResult();
		
		preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $result, $version); 
										
		if(function_exists('floatval'))
		{
			return floatval($version[0]);
		}
		else
		{
			return doubleval($version[0]);
		}		
	}
		
	
}
