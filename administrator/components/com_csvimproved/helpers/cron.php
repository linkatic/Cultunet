<?php
/**
 * Cron handler
 *
 * @package CSVImproved
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: cron.php 947 2009-08-05 08:00:22Z Roland $
 */

 
/**
 * Cron handler
 */
/* Get the Joomla framework */
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', substr(str_ireplace('administrator/components/com_csvimproved/helpers/cron.php', '', str_ireplace('\\', '/', __FILE__)), 0, -1));
define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_csvimproved');
define('JPATH_COMPONENT', JPATH_COMPONENT_ADMINISTRATOR);
$_SERVER['REQUEST_METHOD'] = 'post';
require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE.DS.'administrator'.DS.'includes'.DS.'toolbar.php' );
/* Create the Application */
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();

/**
 * Handles all cron requests
 *
 * @package CSVImproved
 */
class CsviCron {
	
	/** @var $basepath string the base of the installation */
	var $basepath = '';
	
	/**
	 * Initialise some settings
	 */
	function CsviCron() {
		$db = JFactory::getDBO();
		
		$this->CollectVariables();
		/* First check if we deal with a valid user */
		if ($this->Login()) {
			/* Second check if any template is set to process */
			$template_name = JRequest::getString('template_name', '');
			if ($template_name) {
				/* There is a template name, get some details to streamline processing */
				$q = "SELECT template_id, template_type 
					FROM #__csvi_templates 
					WHERE template_name = ".$db->Quote($template_name);
				$db->setQuery($q);
				$row = $db->loadObject();
				if (is_object($row)) {
					if ($row->template_type) {
						/* Set the export type */
						if (stristr($row->template_type, 'export')) {
							JRequest::setVar('task', 'exportfile');
							JRequest::setVar('controller', 'exportfile');
						}
						else {
							JRequest::setVar('task', 'importfile');
							JRequest::setVar('controller', 'importfile');
						}
						/* Set the template ID */
						JRequest::setVar('template_id', $row->template_id);
						
						/* Set the file details */
						/* Set output to file as we cannot download anything */
						JRequest::setVar('exportto', 'tofile');
						
						/* Get the filename from the user input */
						if (JRequest::getCmd('filename', false)) {
							JRequest::setVar('local_csv_file', JRequest::getString('filename'));
							/* Tell the system it is a local file */
							JRequest::setVar('selectfile', 2);
						}
						else {
							/* Tell the system there is no file */
							JRequest::setVar('selectfile', 0);
						}
						$this->ExecuteJob();
					}
				}
				else echo 'No template found with the name '.$template_name;
			}
			else echo 'No template name specified';
		}
		else {
			$error = JError::getError();
			echo $error->message;
		}
		
		/* Done, lets log the user out*/
		$this->UserLogout();
	}
	
	/**
	 * Collect the variables
	 *
	 * Running from the command line, the variables are stored in $argc and $argv.
	 * Here we put them in $_REQUEST so that they are available to the script
	 */
	private function CollectVariables() {
		/* Take the argument values and put them in $_REQUEST */
		if (isset($_SERVER['argv'])) {
			foreach ($_SERVER['argv'] as $key => $argument) {
				if ($key > 0) {
					list($name, $value) = split("=", $argument);
					if (strpos($value, '|')) $value = explode('|', $value);
					JRequest::setVar($name, $value);
				}
			}
		}
	}
	
	/**
	 * Check if the user exists
	 */
	private function Login() {
		global $mainframe;
		
		$credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
		$credentials['password'] = JRequest::getVar('passwd', '', 'method', 'string');
		
		$result = $mainframe->login($credentials, array('entry_url' => ''));
		
		if (!JError::isError($result)) {
			return true;
		}
		else return false;
	}
	 
	/**
	 * Process the requested job
	 */
	function ExecuteJob() {
		JRequest::setVar('cron', true);
		require(JPATH_COMPONENT_ADMINISTRATOR.DS.'csvimproved.php');
	}
	
	/**
	 * Log the user out
	 */
	private function UserLogout() {
		global $mainframe;
		ob_start();
		$error = $mainframe->logout();
		
		if(JError::isError($error)) {
			ob_end_clean();
			echo JText::_('PROBLEM_LOGOUT_USER');
		}
		else {
			ob_end_clean();
			echo JText::_('USER_LOGGED_OUT');
		}
	}
}
$csvicron = new CsviCron;
?>