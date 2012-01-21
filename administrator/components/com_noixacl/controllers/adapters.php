<?php
/**
 * Check Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Adapters Component Controller
 *
 * @package		Joomla
 * @subpackage	NOIX ACL
 * @since 1.5
 */
class AdaptersController extends JController
{
	/**
	 * Enable a Adapter
	 */
	function enable()
	{
		$cid = JRequest::getVar('cid',  0, '', 'array');
		$idAdapter = intval($cid[0]);

        $this->changeAdapterState($idAdapter,1);
	}
	
	/**
	 * Disable a Adapter
	 */
	function disable()
	{
		$cid = JRequest::getVar('cid',  0, '', 'array');
		$idAdapter = intval($cid[0]);

        $this->changeAdapterState($idAdapter,0);
	}

    function changeAdapterState($idAdapter,$state)
    {
        $limitstart = JRequest::getInt('limitstart');

		$tableAdapter = JTable::getInstance('adapters','table');
		$tableAdapter->load( $idAdapter );
		$tableAdapter->enabled = $state;
		if( !$tableAdapter->store() ){
			$msg = JText::_( 'NOIXACL_ADAPTERS_ERROR_ENABLE' )
                 .$tableAdapter->title;
		}

        switch($state){
            case 0:
                $action = 'NOIXACL_ADAPTERS_ADAPTER_DISABLED';
                break;
            case 1:
                $action = 'NOIXACL_ADAPTERS_ADAPTER_ENABLED';
                break;
        }

		$msg = JText::_($action);

		$link = "index.php?option=com_noixacl&controller=adapters";
        if( $limitstart ){
            $link .= "&limitstart={$limitstart}";
        }

		$this->setRedirect($link, $msg);
    }
	
	/**
	 * Uninstall a Adapter
	 */
	function remove()
	{
        /**
         * Getting Limitstart
         */
        $limitstart = JRequest::getInt('limitstart');
        
        /**
         * Get Number ID of Adapter
         */
        $cid = JRequest::getInt('cid');
        if( !$cid ){
            JError::raiseError(JText::_('noixACL'),JText::_("NOIXACL_ADAPTERS_CID_ERROR"));
            return false;
        }

        /**
         * 1) Load Table; 2) Get Adapter Folder Name
         */
        $tableAdapter = JTable::getInstance('adapters','table');
        $tableAdapter->load( $cid );


        /**
         * Remove Rules of Adapter
         */
        $AdaptersController = new Adapters();
        $AdaptersController->deleteAdaptersRules( $tableAdapter->adapter );

        
        /**
         * Delete Folder of Adapter
         */
        $deleteFolder = JFolder::delete(JPATH_COMPONENT_ADMINISTRATOR.DS.'adapters'.DS."{$tableAdapter->adapter}");
        if( !$deleteFolder ){
            JError::raiseError(JText::_('noixACL'),JText::_( "NOIXACL_ADAPTERS_DONT_EXISTS" ));
            return false;
        }

        /**
         * Remove Adapter From DataBase
         */
        if ( !$tableAdapter->delete($cid) ) {
            JError::raiseError(JText::_('noixACL'),JText::_( "NOIXACL_ADAPTERS_EXECUTE_QUERY_ERROR" ));
            return false;
        }

        $link = "index.php?option=com_noixacl&controller=adapters";
        $msg  = JText::_( "NOIXACL_ADAPTERS_UNINSTALL_SUCESSFULL" );
        if( $limitstart ){
            $link .= "&limitstart={$limitstart}";
        }
        $this->setRedirect($link, $msg);
	}
	
	/**
	 * Install a Adapter
	 */
	function install()
	{
        JRequest::setVar('installtype','upload');
        $this->addModelPath(JPATH_ADMINISTRATOR.DS."components".DS."com_installer".DS."models");
        $modelIntaller = $this->getModel('install','InstallerModel');
        $modelIntaller->_type = 'adapter';
        $modelIntaller->install();
        
        $msg = JText::_( "NOIXACL_ADAPTERS_INSTALL_SUCESSFULL" );
        $this->setRedirect("index.php?option=com_noixacl&controller=adapters",$msg);
	}
	
	/**
	 * Displays a view
	 */
	function display()
	{
		parent::display();
	}
}