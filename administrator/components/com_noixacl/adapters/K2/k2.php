<?php
/**
 * No direct access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	Adapters
 * @since 1.5
 */
class K2Controller extends Adapters
{
	/**
	 * Save Adapter
	 */
	function save($groupName)
	{
        /**
         * Checking post of adapter menu
         */
            if( !empty($_POST['k2']) ){
            $post = $_POST['k2'];

            /**
             * Saving admin rules
             */
            if( !empty($post['Admin']) ){
                foreach($post['Admin'] as $catid => $content){
                    $extraParams = array(
                        '$catid' => $catid
                    );

                        /**
                         * Saving Rule
                         */
						 // MIKE Change site in Admin
                        $resultRuleAdmin = $this->saveRule("k2","admin",$groupName,$content['tasks'],$extraParams);
                }
            }
            
            /**
             * Verify result of save
             */
			// MIKE change && in ||
            if( $resultRuleAdmin == TRUE || $resultRuleSite == TRUE ){
                return true;
            }

            return false;
        }
	}
	
	/**
	 * Delete Adapter
	 */
	function delete($groupName)
	{
            $this->deleteRules("com_k2","category",$groupName);
	}
	
	/**
	 * Displays a view
	 */
	function display()
	{
		parent::display();
	}
}