<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/controllers/jsjobs.php
 ^ 
 * Description: Controller class for admin site
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class JSJobsControllerJsjobs extends JController
{

	function __construct()
	{
		parent :: __construct();

		$this->registerTask('add', 'edit');
	}
	function edit()
	{
		$cur_layout = $_SESSION['cur_layout'];
		JRequest :: setVar('view', 'application');
		JRequest :: setVar('hidemainmenu', 1);
		if ($cur_layout == 'categories')
			JRequest :: setVar('layout', 'formcategory');
		elseif ($cur_layout == 'jobtypes') JRequest :: setVar('layout', 'formjobtype');
		elseif ($cur_layout == 'jobstatus') JRequest :: setVar('layout', 'formjobstatus');
		elseif ($cur_layout == 'shifts') JRequest :: setVar('layout', 'formshift');
		elseif ($cur_layout == 'highesteducations') JRequest :: setVar('layout', 'formhighesteducation');
		elseif (($cur_layout == 'companies') || ($cur_layout == 'companiesqueue'))
			JRequest :: setVar('layout', 'formcompany');	
		elseif (($cur_layout == 'jobs') || ($cur_layout == 'jobqueue'))
			JRequest :: setVar('layout', 'formjob');	
		elseif (($cur_layout == 'empapps') || ($cur_layout == 'appqueue'))
			JRequest :: setVar('layout', 'formresume');	
		elseif ($cur_layout == 'salaryrange')
			JRequest :: setVar('layout', 'formsalaryrange');	
		elseif ($cur_layout == 'userfields')
			JRequest :: setVar('layout', 'formuserfield');	
		elseif ($cur_layout == 'roles')	JRequest :: setVar('layout', 'formrole');	
		elseif ($cur_layout == 'users')	JRequest :: setVar('layout', 'changerole');	
		elseif ($cur_layout == 'countries')	JRequest :: setVar('layout', 'formcountry');	
		elseif ($cur_layout == 'states')	JRequest :: setVar('layout', 'formstate');	
		elseif ($cur_layout == 'counties')	JRequest :: setVar('layout', 'formcounty');	
		elseif ($cur_layout == 'cities')	JRequest :: setVar('layout', 'formcity');	
		
		// Display based on the set variables
		//parent :: display();
		$this->display();
	}
	
	function companyapprove()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$companyid = $cid[0];
		$return_value = $model->companyApprove($companyid);
		if ($return_value == 1)	{
			$msg = JText::_('JS_COMPANY_APPROVED');
		}else
			$msg = JText::_('JS_ERROR_IN_APPROVING_COMPANY');
			
		$link = 'index.php?option=com_jsjobs&task=view&layout=companiesqueue';
		$this->setRedirect($link, $msg);
	}

	function companyreject()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$companyid = $cid[0];
		$return_value = $model->companyReject($companyid);
		if ($return_value == 1)	{
			$msg = JText::_('JS_COMPANY_REJECTED');
		}else
			$msg = JText::_('JS_ERROR_IN_REJECTING_COMPANY');
			
		$link = 'index.php?option=com_jsjobs&task=view&layout=companiesqueue';
		$this->setRedirect($link, $msg);
	}

	function jobapprove()
	{
		echo 'job approve';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		//$jobid = $_GET['jobid'];
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$jobid = $cid[0];
		//echo 'jid'.$jobid;
		$return_value = $model->jobApprove($jobid);
		if ($return_value == 1)	{
			$msg = JText::_('JOB_APPROVED');
		}else
			$msg = JText::_('ERROR_IN_APPROVING_JOB');
			
		$link = 'index.php?option=com_jsjobs&task=view&layout=jobqueue';
		$this->setRedirect($link, $msg);
	}

	function jobreject()
	{
		echo 'job reject';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		//$jobid = $_GET['jobid'];
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$jobid = $cid[0];
		//echo 'jid'.$jobid;
		$return_value = $model->jobReject($jobid);
		if ($return_value == 1)	{
			$msg = JText::_('JOB_REJECTED');
		}else
			$msg = JText::_('ERROR_IN_REJECTING_JOB');
			
		$link = 'index.php?option=com_jsjobs&task=view&layout=jobqueue';
		$this->setRedirect($link, $msg);
	}

	function empappapprove()
	{
		echo 'emp app approve';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		//$jobid = $_GET['jobid'];
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$appid = $cid[0];
		//echo 'jid'.$jobid;
		$return_value = $model->empappApprove($appid);
		if ($return_value == 1)	{
			$msg = JText::_('EMP_APP_APPROVED');
		}else
			$msg = JText::_('ERROR_IN_APPROVING_EMP_APP');
			
		$link = 'index.php?option=com_jsjobs&task=view&layout=appqueue';
		$this->setRedirect($link, $msg);
	}

	function empappreject()
	{
		echo 'job reject';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$appid = $cid[0];
		$return_value = $model->empappReject($appid);
		if ($return_value == 1)	{
			$msg = JText::_('EMP_APP_REJECTED');
		}else
			$msg = JText::_('ERROR_IN_REJECTING_EMP_APP');
			
		$link = 'index.php?option=com_jsjobs&task=view&layout=appqueue';
		$this->setRedirect($link, $msg);
	}

	function fieldpublished()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$fieldid = $cid[0];
		$return_value = $model->fieldPublished($fieldid, 1);//published
		$link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
		$this->setRedirect($link, $msg);
	}

	function fieldunpublished()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$fieldid = $cid[0];
		$return_value = $model->fieldPublished($fieldid, 0); // unpublished
		$link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
		$this->setRedirect($link, $msg);
	}

	function fieldorderingup()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$fieldid = $cid[0];
		$return_value = $model->fieldOrderingUp($fieldid);
		$link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
		$this->setRedirect($link, $msg);
	}

	function fieldorderingdown()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cid 	= JRequest::getVar( 'cid', array(), ''		, 'array' );
		$fieldid = $cid[0];
		$return_value = $model->fieldOrderingDown($fieldid);
		$link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$cur_layout = $_SESSION['cur_layout'];
		if ($cur_layout == 'categories'){
			$returnvalue = $model->deleteCategory();
			if ($returnvalue == 1){
				$msg = JText::_('CATEGORY_DELETED');
			}else{
				$msg = $returnvalue -1 .' '. JText::_('ERROR_CATEGORY_COULD_NOT_DELETE');
			}
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=categories', $msg);

			}elseif ($cur_layout == 'jobtypes'){
			$returnvalue = $model->deleteJobType();
			if ($returnvalue == 1)	$msg = JText::_('JS_JOB_TYPE_DELETED');
			else	$msg = $returnvalue -1 .' '. JText::_('JS_ERROR_JOB_TYPE_COULD_NOT_DELETE');
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobtypes', $msg);
		}elseif ($cur_layout == 'jobstatus'){
			$returnvalue = $model->deleteJobStatus();
			if ($returnvalue == 1)	$msg = JText::_('JS_JOB_STATUS_DELETED');
			else	$msg = $returnvalue -1 .' '. JText::_('JS_ERROR_JOB_STATUS_COULD_NOT_DELETE');
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobstatus', $msg);
		}elseif ($cur_layout == 'shifts'){
			$returnvalue = $model->deleteShift();
			if ($returnvalue == 1)	$msg = JText::_('JS_SHIFT_DELETED');
			else	$msg = $returnvalue -1 .' '. JText::_('JS_ERROR_SHIFT_COULD_NOT_DELETE');
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=shifts', $msg);
		}elseif ($cur_layout == 'highesteducations'){
			$returnvalue = $model->deleteHighestEducation();
			if ($returnvalue == 1)	$msg = JText::_('JS_HIGHEST_EDUCATION_DELETED');
			else	$msg = $returnvalue -1 .' '. JText::_('JS_ERROR_HIGHEST_EDUCATION_COULD_NOT_DELETE');
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=highesteducations', $msg);
		}elseif ($cur_layout == 'salaryrange'){
			$returnvalue = $model->deleteSalaryRange();
			if ($returnvalue == 1){
				$msg = JText::_('SALARY_RANGE_DELETED');
			}else{
				$msg = $returnvalue -1 .' '. JText::_('ERROR_RANGE_COULD_NOT_DELETE');
			}
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=salaryrange', $msg);
		}elseif ($cur_layout == 'empapps'){
		echo '<br> empapps';
			$returnvalue = $model->deleteResume();
			if ($returnvalue == 1){
				$msg = JText::_('EMP_APP_DELETED');
			}else{
				$msg = $returnvalue -1 .' '. JText::_('ERROR_EMP_APP_COULD_NOT_DELETE');
			}
			$this->setRedirect('index.php?option=com_jsjobs&task=view', $msg);
		/*	if (!$model->deleteEmpApp()){
				$msg = JText::_('ERROR_EMP_APP_COULD_NOT_DELETE');
			}else{
				$msg = JText::_('EMP_APP_DELETED');
			}
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=empapps', $msg);
		*/
		}elseif ($cur_layout == 'companies'){
			$returnvalue = $model->deleteCompany();
			if ($returnvalue == 1){
				$msg = JText::_('COMPANY_DELETED');
			}else{
				$msg = $returnvalue -1 .' '. JText::_('COMPANY_COULD_NOT_DELETE');
			}
			$this->setRedirect('index.php?option=com_jsjobs&task=view', $msg);
		}elseif ($cur_layout == 'jobs'){
			$returnvalue = $model->deleteJob();
			if ($returnvalue == 1){
				$msg = JText::_('JOB_DELETED');
			}else{
				$msg = $returnvalue -1 .' '. JText::_('JOB_COULD_NOT_DELETE');
			}
			$this->setRedirect('index.php?option=com_jsjobs&task=view', $msg);
		}elseif ($cur_layout == 'roles'){
			$returnvalue = $model->deleteRole();
			if ($returnvalue == 1){
				$msg = JText::_('ROLE_DELETED');
			}else{
				$msg = $returnvalue -1 .' '. JText::_('ROLE_COULD_NOT_DELETE');
			}
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=roles', $msg);
		}
		elseif ($cur_layout == 'userfields') $this->deleteuserfield();
		elseif ($cur_layout == 'countries')	$this->deletecountry();
		elseif ($cur_layout == 'states')	$this->deletestate();
		elseif ($cur_layout == 'counties')	$this->deletecounty();
		elseif ($cur_layout == 'cities')	$this->deletecity();
	}

	function deleteuserfield(){
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->deleteUserField();
		if ($return_value == 1){
			$msg = JText::_('JS_USER_FIELD_DELETE');
		} else {
				$msg = $returnvalue -1 .' '. JText::_('JS_USER_FIELD_COULD_NOT_DELETE');
		}
		$link = 'index.php?option=com_jsjobs&task=view&layout=userfields';
		$this->setRedirect($link, $msg);
	}

	function deletecountry(){
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->deleteCountry();
		if ($return_value == 1){
			$msg = JText::_('JS_COUNTRY_DELETE');
		} else {
				$msg = $returnvalue -1 .' '. JText::_('JS_COUNTRY_COULD_NOT_DELETE');
		}
		$link = 'index.php?option=com_jsjobs&task=view&layout=countries';
		$this->setRedirect($link, $msg);
	}

	function deletestate(){
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; 
		$return_value = $model->deleteState();
		if ($return_value == 1){
			$msg = JText::_('JS_STATE_DELETE');
		} else {
				$msg = $returnvalue -1 .' '. JText::_('JS_STATE_COULD_NOT_DELETE');
		}
		$link = 'index.php?option=com_jsjobs&task=view&layout=states&ct='.$countrycode;
		$this->setRedirect($link, $msg);
		
	}

	function deletecounty(){
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		if (isset($_SESSION['js_statecode'])) $statecode = $_SESSION['js_statecode']; 
		$return_value = $model->deleteCounty();
		if ($return_value == 1){
			$msg = JText::_('JS_COUNTY_DELETE');
		} else {
				$msg = $returnvalue -1 .' '. JText::_('JS_COUNTY_COULD_NOT_DELETE');
		}
		$link = 'index.php?option=com_jsjobs&task=view&layout=counties&sd='.$statecode;
		$this->setRedirect($link, $msg);
	}

	function deletecity(){
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		if (isset($_SESSION['js_countycode'])) $countycode = $_SESSION['js_countycode']; 
		$return_value = $model->deleteCity();
		if ($return_value == 1){
			$msg = JText::_('JS_CITY_DELETE');
		} else {
				$msg = $returnvalue -1 .' '. JText::_('JS_CITY_COULD_NOT_DELETE');
		}
		$link = 'index.php?option=com_jsjobs&task=view&layout=cities&co='.$countycode;
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_('OPERATION_CANCELLED');
		$cur_layout = $_SESSION['cur_layout'];
		if ($cur_layout == 'categories')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=categories', $msg);
		elseif ($cur_layout == 'jobtypes')	$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobtypes', $msg);
		elseif ($cur_layout == 'jobstatus')	$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobstatus', $msg);
		elseif ($cur_layout == 'shifts')	$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=shifts', $msg);
		elseif ($cur_layout == 'highesteducations')	$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=highesteducations', $msg);
		elseif ($cur_layout == 'companies')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=companies', $msg);
		elseif ($cur_layout == 'jobs')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobs', $msg);
		elseif ($cur_layout == 'jobqueue')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobqueue', $msg);
		elseif ($cur_layout == 'jobappliedresume')	$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=appliedresumes', $msg);
		elseif ($cur_layout == 'view_resume'){
			$jobid = JRequest::getVar( 'oi');
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobappliedresume&oi='.$jobid, $msg);
		}	
		elseif ($cur_layout == 'empapps')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=empapps', $msg);
		elseif ($cur_layout == 'salaryrange')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=salaryrange', $msg);
		elseif ($cur_layout == 'userfields')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=userfields', $msg);
		elseif ($cur_layout == 'roles')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=roles', $msg);
		elseif ($cur_layout == 'users')
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=users', $msg);
		elseif ($cur_layout == 'countries')	$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=countries', $msg);
		elseif ($cur_layout == 'states'){
			if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; ;
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=states&ct='.$countrycode, $msg);
		}elseif ($cur_layout == 'counties'){
			if (isset($_SESSION['js_statecode'])) $statecode = $_SESSION['js_statecode']; 
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=counties&sd='.$statecode, $msg);
		}elseif ($cur_layout == 'cities'){
			if (isset($_SESSION['js_countycode'])) $countycode = $_SESSION['js_countycode']; 
			$this->setRedirect('index.php?option=com_jsjobs&task=view&layout=cities&co='.$countycode, $msg);
		}	
	}

	function save()
	{
		$cur_layout = $_SESSION['cur_layout'];
		//echo '<br>save'.$cur_layout;
		if ($cur_layout == 'categories')
			$this->savecategory();
		elseif ($cur_layout == 'jobtypes')	$this->savejobtype();
		elseif ($cur_layout == 'jobstatus')	$this->savejobstatus();
		elseif ($cur_layout == 'shifts')	$this->saveshift();
		elseif ($cur_layout == 'highesteducations')	$this->savehighesteducation();
		elseif ($cur_layout == 'companies')
			$this->saveCompany();
		elseif ($cur_layout == 'jobs') $this->savejob();
		elseif ($cur_layout == 'jobqueue') $this->savejob();
		elseif ($cur_layout == 'empapps') $this->saveresume();
		elseif ($cur_layout == 'appqueue') $this->saveresume();
		elseif ($cur_layout == 'conf')
			$this->saveconf();
		elseif ($cur_layout == 'salaryrange')
			$this->savesalaryrange();
		elseif ($cur_layout == 'roles')
			$this->saverole();
		elseif ($cur_layout == 'users')
			$this->saveuserrole();
		elseif ($cur_layout == 'userfields')
			$this->saveuserfield();
		elseif ($cur_layout == 'emailtemplate')
			$this->saveemailtemplate();
		elseif ($cur_layout == 'countries')	$this->savecountry();
		elseif ($cur_layout == 'states')	$this->savestate();
		elseif ($cur_layout == 'counties')	$this->savecounty();
		elseif ($cur_layout == 'cities')	$this->savecity();

	}
	function saveconf(){

		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeConfig();

		if ($return_value == 1){
			$msg = JText::_('The Configuration Details have been updated');
		} else {
			$msg = JText::_('ERRORCONFIGFILE');
		}
		$link = 'index.php?option=com_jsjobs&task=view&layout=conf';
		$this->setRedirect($link, $msg);
		
	}

	function savecompany()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeCompany();
		if ($return_value == 1)
		{
			$msg = JText::_('COMPANY_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=companies';
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('ERROR_SAVING_COMPANY');
			$link = 'index.php?option=com_jsjobs&task=view&layout=companies';
			$this->setRedirect($link, $msg);
		}
	}

	function savejob()
	{

		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeJob();
		if ($return_value == 1)
		{
			$msg = JText::_('JOB_POST_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
			$this->setRedirect($link, $msg);
		}
		else if ($return_value == 2){
			$msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
			JRequest :: setVar('view', 'application');
			JRequest :: setVar('hidemainmenu', 1);
				JRequest :: setVar('layout', 'formjob');
				JRequest :: setVar('msg', $msg);
			
			// Display based on the set variables
			parent :: display();
		}else 
		{
			$msg = JText::_('ERROR_SAVING_JOB');
			$link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
			$this->setRedirect($link, $msg);
		}
	}

	function saveresume()
	{

		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeResume();
		if ($return_value == 1)
		{
			$msg = JText::_('EMP_APP_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
			$this->setRedirect($link, $msg);
		}
		else if ($return_value == 2){
			$msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
			JRequest :: setVar('view', 'application');
			JRequest :: setVar('hidemainmenu', 1);
				JRequest :: setVar('layout', 'formemp');
				JRequest :: setVar('msg', $msg);
			
			// Display based on the set variables
			parent :: display();
		}else 
		{
			$msg = JText::_('ERROR_SAVING_EMP_APP');
			$link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
			$this->setRedirect($link, $msg);
		}
	}

	function savecategory()
	{
		//echo 'save category';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeCategory();
		//echo '<br> rv '.$return_value;
		if ($return_value == 1)
		{
			$msg = JText::_('CATEGORY_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=categories';
			$this->setRedirect($link, $msg);
		}
		else if ($return_value == 2){
			$msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
			JRequest :: setVar('view', 'application');
			JRequest :: setVar('hidemainmenu', 1);
				JRequest :: setVar('layout', 'formcategory');
				JRequest :: setVar('msg', $msg);
			
			// Display based on the set variables
			parent :: display();
			//$link = 'index.php?option=com_jsjobs&c=application&layout=categories';
		}else if ($return_value == 3){
			$msg = JText::_('CATEGORY_ALREADY_EXIST');
			JRequest :: setVar('view', 'application');
			JRequest :: setVar('hidemainmenu', 1);
			JRequest :: setVar('layout', 'formcategory');
			JRequest :: setVar('msg', $msg);
			parent :: display();
		}else 
		{
			$msg = JText::_('ERROR_SAVING_CATEGORY');
			$link = 'index.php?option=com_jsjobs&task=view&layout=categories';
			$this->setRedirect($link, $msg);
		}

		// Check the table in so it can be edited.... we are done with it anyway
		//$link = 'index.php?option=com_jsjobs&c=application&layout=categories';
	}

	function savejobtype()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeJobType();
		$link = 'index.php?option=com_jsjobs&task=view&layout=jobtypes';
		if ($return_value == 1)	{
			$msg = JText::_('JS_JOB_TYPE_SAVED');
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('JS_ERROR_SAVING_JOB_TYPE');
			$this->setRedirect($link, $msg);
		}
	}

	function savejobstatus()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeJobStatus();
		$link = 'index.php?option=com_jsjobs&task=view&layout=jobstatus';
		if ($return_value == 1)	{
			$msg = JText::_('JS_JOB_STATUS_SAVED');
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('JS_ERROR_SAVING_JOB_STATUS');
			$this->setRedirect($link, $msg);
		}
	}

	function saveshift()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeShift();
		$link = 'index.php?option=com_jsjobs&task=view&layout=shifts';
		if ($return_value == 1)	{
			$msg = JText::_('JS_SHIFT_SAVED');
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('JS_ERROR_SAVING_SHIFT');
			$this->setRedirect($link, $msg);
		}
	}

	function savehighesteducation()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeHighestEducation();
		$link = 'index.php?option=com_jsjobs&task=view&layout=highesteducations';
		if ($return_value == 1)	{
			$msg = JText::_('JS_HIGHEST_EDUCATION_SAVED');
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('JS_ERROR_SAVING_HIGHEST_EDUCATION');
			$this->setRedirect($link, $msg);
		}
	}
	function savesalaryrange()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeSalaryRange();
		if ($return_value == 1)
		{
			$msg = JText::_('SALARY_RANGE_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=salaryrange';
			$this->setRedirect($link, $msg);
		}else if ($return_value == 2){
			$msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
			JRequest :: setVar('view', 'application');
			JRequest :: setVar('hidemainmenu', 1);
				JRequest :: setVar('layout', 'formsalaryrange');
				JRequest :: setVar('msg', $msg);
			
			// Display based on the set variables
			parent :: display();
			//$link = 'index.php?option=com_jsjobs&c=application&layout=categories';
		}else if ($return_value == 3){
			$msg = JText::_('RANGE_ALREADY_EXIST');
			JRequest :: setVar('view', 'application');
			JRequest :: setVar('hidemainmenu', 1);
			JRequest :: setVar('layout', 'formsalaryrange');
			JRequest :: setVar('msg', $msg);
			parent :: display();
		}else {
			$msg = JText::_('ERROR_SAVING_RANGE');
			$link = 'index.php?option=com_jsjobs&task=view&layout=salaryrange';
			$this->setRedirect($link, $msg);
		}
		$link = 'index.php?option=com_jsjobs&c=application&layout=salaryrange';
	}

	function saveactivate()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeActivate();
		if ($return_value == 1)	{
			$msg = JText::_('JS_JOBS_ACTIVATED');
		}elseif ($return_value == 3) {
			$msg = JText::_('JS_INVALID_ACTIVATION_KEY');
		}elseif ($return_value == 4) {
			$msg = JText::_('ERROR_CAN_NOT_ACTIVATE_JS_JOBS');
		}else {
			$msg = JText::_('ERROR_CAN_NOT_ACTIVATE_JS_JOBS');
		}
		$link = 'index.php?option=com_jsjobs&task=view&layout=updates';
		$this->setRedirect($link, $msg);
	}

	function saverole()
	{
		//echo 'save salaryrange';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeRole();
		//echo '<br> rv '.$return_value;
		if ($return_value == 1)
		{
			$msg = JText::_('ROLE_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=roles';
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('ERROR_SAVING_ROLE');
			$link = 'index.php?option=com_jsjobs&task=view&layout=roles';
			$this->setRedirect($link, $msg);
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_jsjobs&c=application&layout=roles';
	}

	function saveuserrole()
	{
		//echo 'save salaryrange';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeUserRole();
		//echo '<br> rv '.$return_value;
		if ($return_value == 1)	{
			$msg = JText::_('ROLE_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=users';
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('ERROR_SAVING_ROLE');
			$link = 'index.php?option=com_jsjobs&task=view&layout=users';
			$this->setRedirect($link, $msg);
		}

	}

	function saveuserfield()
	{
		//echo 'save salaryrange';
		echo '<br> save user field';
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$return_value = $model->storeUserField();
		//echo '<br> rv '.$return_value;
		if ($return_value == 1)
		{
			$msg = JText::_('USER_FIELD_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=userfields';
			$this->setRedirect($link, $msg);
		}
		else if ($return_value == 2){
			$msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
			JRequest :: setVar('view', 'application');
			JRequest :: setVar('hidemainmenu', 1);
				JRequest :: setVar('layout', 'formuserfield');
				JRequest :: setVar('msg', $msg);
		
			// Display based on the set variables
			parent :: display();
		}else 
		{
			$msg = JText::_('ERROR_SAVING_USER_FIELD');
			$link = 'index.php?option=com_jsjobs&task=view&layout=formuserfield';
			$this->setRedirect($link, $msg);
		}
	}

	function saveemailtemplate()
	{
//echo '<br>save template';
	$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$data = JRequest :: get('post');
		$templatefor = $data['templatefor'];
		$return_value = $model->storeEmailTemplate();
		switch($templatefor) {
			case 'company-approval' : $tempfor = 'cm-ap'; break;
			case 'company-rejecting' : $tempfor = 'cm-rj'; break;
			case 'job-approval' : $tempfor = 'ob-ap'; break;
			case 'job-rejecting' : $tempfor = 'ob-rj'; break;
			case 'resume-approval' : $tempfor = 'rm-ap'; break;
			case 'resume-rejecting' : $tempfor = 'rm-rj'; break;
			case 'jobapply-jobapply' : $tempfor = 'ba-ja'; break;
		}
		if ($return_value == 1)
		{
			$msg = JText::_('JS_EMAIL_TEMPATE_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf='.$tempfor;
			$this->setRedirect($link, $msg);
		}else {
			$msg = JText::_('ERROR_SAVING_EMAIL_TEMPLATE');
			$link = 'index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf='.$tempfor;
			$this->setRedirect($link, $msg);
		}
	}

	function savecountry()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$data = JRequest :: get('post');
		$return_value = $model->storeCountry();
		$link = 'index.php?option=com_jsjobs&task=view&layout=countries';
		if ($return_value == 1)	{
			$msg = JText::_('JS_COUNTRY_SAVED');
		}elseif ($return_value == 3)	{
			$msg = JText::_('JS_COUNTRY_EXIST');
		}else {
			$msg = JText::_('JS_ERROR_SAVING_COUNTRY');
		}
		$this->setRedirect($link, $msg);
	}

	function savestate()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$data = JRequest :: get('post');
		$countrycode = $data['countrycode']; 
		$return_value = $model->storeState();
		$link = 'index.php?option=com_jsjobs&task=view&layout=states&ct='.$countrycode;
		if ($return_value == 1)	{
			$msg = JText::_('JS_STATE_SAVED');
		}elseif ($return_value == 3)	{
			$msg = JText::_('JS_STATE_EXIST');
		}else {
			$msg = JText::_('JS_ERROR_SAVING_STATE');
		}
		$this->setRedirect($link, $msg);
	}

	function savecounty()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$data = JRequest :: get('post');
		$statecode = $data['statecode']; 
		$return_value = $model->storeCounty();
		$link = 'index.php?option=com_jsjobs&task=view&layout=counties&sd='.$statecode;
		if ($return_value == 1)	{
			$msg = JText::_('JS_COUNTY_SAVED');
		}elseif ($return_value == 3)	{
			$msg = JText::_('JS_COUNTY_EXIST');
		}else {
			$msg = JText::_('JS_ERROR_SAVING_COUNTY');
		}
		$this->setRedirect($link, $msg);
	}

	function savecity()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$data = JRequest :: get('post');
		$countycode = $data['countycode']; 
		$return_value = $model->storeCity();
		$link = 'index.php?option=com_jsjobs&task=view&layout=cities&co='.$countycode;
		if ($return_value == 1)	{
			$msg = JText::_('JS_CITY_SAVED');
		}elseif ($return_value == 3)	{
			$msg = JText::_('JS_CITY_EXIST');
		}else {
			$msg = JText::_('JS_ERROR_SAVING_CITY');
		}
		$this->setRedirect($link, $msg);
	}

	function loadaddressdata()
	{
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		$data = JRequest :: get('post');
		$return_value = $model->loadAddressData();
		$link = 'index.php?option=com_jsjobs&task=view&layout=loadaddressdata&er=2';
		if ($return_value == 1)	{
			$msg = JText::_('JS_ADDRESS_DATA_SAVED');
			$link = 'index.php?option=com_jsjobs&task=view&layout=loadaddressdata';
		}elseif ($return_value == 3){ // file mismatch
			$msg = JText::_('JS_ADDRESS_DATA_COULD_NOT_SAVE');
		}elseif ($return_value == 3){ // file mismatch
			$msg = JText::_('JS_FILE_TYPE_ERROR');
		}elseif ($return_value == 5){ // state alredy exist 
			$msg = JText::_('JS_STATES_EXIST');
		}elseif ($return_value == 8){ // county alredy exist 
			$msg = JText::_('JS_COUNTIES_EXIST');
		}elseif ($return_value == 11){ // state and county alredy exist 
			$msg = JText::_('JS_STATES_COUNTIES_EXIST');
		}elseif ($return_value == 7){ // city alredy exist 
			$msg = JText::_('JS_CITIES_EXIST');
		}elseif ($return_value == 6){ // state and city alredy exist 
			$msg = JText::_('JS_STATES_CITIES_EXIST');
		}elseif ($return_value == 9){ // county and city alredy exist 
			$msg = JText::_('JS_COUNTIES_CITIES_EXIST');
		}elseif ($return_value == 12){ // state, counnty and city alredy exist 
			$msg = JText::_('JS_STATES_COUNTIES_CITIES_EXIST');
		}else {
			$msg = JText::_('JS_ADDRESS_DATA_COULD_NOT_SAVE');
		}
//		echo 're val '.$return_value;
		$this->setRedirect($link, $msg);
	}

	function listaddressdata()
	  { 
		 global $mainframe;
	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$returnvalue = $model->listAddressData($data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }

	function listempaddressdata()
	  { 
		 global $mainframe;
		 
	     $name=JRequest::getVar( 'name');
	     $myname=JRequest::getVar( 'myname');
	     $nextname=JRequest::getVar( 'nextname');

	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');

		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$returnvalue = $model->listEmpAddressData($name, $myname, $nextname, $data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }
	
	function display()
	{
		$document = & JFactory :: getDocument();
		$viewName = JRequest :: getVar('view', 'applications');
		$layoutName = JRequest :: getVar('layout', '');
		if ($layoutName == '')	
			if (isset($_SESSION['cur_layout']))$layoutName = $_SESSION['cur_layout'];
		if ($layoutName == '') {
			//$layoutName = 'jobs';
			$layoutName = 'controlpanel';
			$_SESSION['cur_layout'] = $layoutName;
		}

		$viewType = $document->getType();
		
		$view = & $this->getView($viewName, $viewType);
		$model = & $this->getModel('jsjobs', 'JSJobsModel');
		if (!JError :: isError($model))
		{
			$view->setModel($model, true);
		}
		$view->setLayout($layoutName);
		$view->display();
	}

}
?>
