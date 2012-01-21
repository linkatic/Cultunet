<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class CronController extends JController{
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerDefaultTask('cron');
		JRequest::setVar('tmpl','component');
	}
	function cron(){
		$cronHelper = acymailing::get('helper.cron');
		$cronHelper->report = true;
		$cronHelper->cron();
		$cronHelper->report();
		exit;
	}
}