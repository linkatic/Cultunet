<?php
/**
 * Check Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	NOIX ACL
 * @since 1.5
 */
class AccessLevelsController extends JController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

                /**
                 * Register Extra Tasks
                 */
		$this->registerTask( 'apply', 	'save'  );
		
	}
	
	function add(){
		JRequest::setVar('cid','-1');
		JRequest::setVar('view','accesslevel');
		$this->display();
	}
	
	function edit(){
		JRequest::setVar('view','accesslevel');
		$this->display();
	}
	
	function remove()
	{
		global $option;
		$db 			= JFactory::getDBO();
		$cid 			= JRequest::getVar( 'cid', array(), '', 'array' );

		JArrayHelper::toInteger( $cid );

		if( count( $cid ) < 1 ){
			JError::raiseError(500, JText::_( 'NOIXACL_LEVELS_SELECT_LEVEL_DELETE', true ) );
		}
		
		foreach($cid as $id){
			$levelID = intval( $id );

			if( $levelID > 2 ){
				$query = "DELETE FROM #__groups WHERE id = {$levelID}";
				$db->setQuery( $query );		
				if( $db->query() ){
					$msg = JText::_("NOIXACL_LEVELS_LEVEL_REMOVED_SUCESSFULL");
				}
				else{
					$msg = JText::_("NOIXACL_LEVELS_LEVEL_REMOVE_ERROR");
				}				
			}			
		}

		$this->setRedirect( "index.php?option={$option}&controller=accesslevels", $msg);
	}
	
	function save(){
		global $option, $mainframe;
		
		$accessLevels = Array(
			'id' => JRequest::getINT('id'),
			'name' => JRequest::getWord('name')
		);
		
		$tableAccessLevels = JTable::getInstance('accesslevels','table');
		$tableAccessLevels->bind( $accessLevels );
		$tableAccessLevels->check();
		if( !$tableAccessLevels->store() ){
			$msg = JText::_('NOIXACL_VIEW_ACCESSLEVELS_SAVE_ERROR');
		}
		
		$tableGroupLevels = JTable::getInstance('grouplevel','table');
		$tableGroupLevels->remove( intval($tableAccessLevels->id) );
		
		$groups = JRequest::getVar('multigroups');
		if( !empty($groups) ){
			foreach($groups as $group){
				$tableGroupLevels = JTable::getInstance('grouplevel','table');
				$tableGroupLevels->bind( $group,$tableAccessLevels->id );
				$tableGroupLevels->save();
			}
		}
				
		switch( $this->getTask() ){
			case 'save':
				if( isset($msg) ){
					$msg = JText::sprintf( 'NOIXACL_VIEW_ACCESSLEVELS_SAVE_SUCCESS', $groupName );
				}
				
				$url = "index.php?option={$option}&controller=accesslevels";
				break;
			case 'apply':
				if( $msg ){
					$msg = JText::sprintf( 'NOIXACL_VIEW_ACCESSLEVELS_APPLY_SUCCESS' ) ." ".$groupName;
				}
				
				$url = "index.php?option={$option}&controller=accesslevels&task=edit&cid[]={$tableAccessLevels->id}";
				break;
		}
		
		$this->setRedirect($url,$msg);
	}
	
	/**
	 * Displays a view
	 */
	function display(){
		parent::display();
	}
}