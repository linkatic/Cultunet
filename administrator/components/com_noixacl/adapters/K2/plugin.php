<?php
/**
 * No Direct Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PluginK2 extends Adapters
{
    function administrator(){
        $db = & JFactory::getDBO();
        $catid = JRequest::getInt('catid');

        //get id from content
        $cid			= JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$id				= JRequest::getVar( 'id', $cid[0], '', 'int' );

        $sqlContent = "SELECT catid FROM #__k2_items WHERE id = {$id}";
        $db->setQuery( $sqlContent );
        $rowCatid = $db->loadResult();
        
        if( !empty($rowCatid) ){
        	$catid = $rowCatid;
        }

		$task = JRequest::getCMD('task');
		$view = JRequest::getCMD('view');
		switch($view){
			case 'item':
				if( empty($task) ) $task = 'edit';
				break;
		}
		
        $result = array(
            'task' => $task,
            'params' => array(
                '$catid' => $catid
            )
        );

        return $result;
    }
}