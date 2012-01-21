<?php
/**
 * No Direct Access
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	Adapters
 * @since 1.5
 */
class ModulesController extends Adapters
{
	/**
	 * Save Adapter
	 */
	function save($groupName)
	{
        /**
         * Checking post of adapter menu
         */
            if( !empty($_POST['modules']) ){
                $post = $_POST['modules'];
                
                /**
                 * Saving site rules
                 */
                if( !empty($post['Site']) ){
                    foreach($post['Site'] as $module => $moduleData){
                        $extraParams = array(
                            '$module' => $module
                        );
                        
                        foreach($moduleData as $moduleName => $view){
                        	$extraParams['$moduletitle'] = $moduleName;
                        	
							/**
							 * Saving Rule
							 */
							$resultRuleSite = $this->saveRule("modules","site",$groupName,$view['tasks'],$extraParams);
                        }
                    }
                }

                return $resultRuleSite;
            }
	}

	/**
	 * Delete Adapter
	 */
	function delete($groupName='')
	{
		$this->deleteRules("com_modules","block",$groupName);
	}

	/**
	 * Displays a view
	 */
	function display()
	{
		parent::display();
	}
}