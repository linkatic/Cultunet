<?php

/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	controllers/jsjobs.php
 ^ 
 * Description: Controller class for application data
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
		$user	=& JFactory::getUser();
		if ($user->guest) { // redirect user if not login
			$link = 'index.php?option=com_user';
			$this->setRedirect($link);
		} 

		parent :: __construct();
	}


	function jobapply()
	{
		global $mainframe;
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');

		$return_value = $model->jobapply();
		if ($return_value == 1)	{
			$msg = JText :: _('APPLICATION_APPLIED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&uid='.$uid.'&Itemid='.$Itemid;
		}else if ($return_value == 3){
			$msg = JText :: _('JS_ALREADY_APPLY_JOB');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('ERROR_APPLING_APPLICATION');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&uid='.$uid.'&Itemid='.$Itemid;
		}
		///final redirect
		$this->setRedirect($link, $msg);
	}

	
	
	function showImage(){
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$uid=62;
		//$params = & $mainframe->getPageParameters('com_jsjobs');
		//$Itemid =  JRequest::getVar('Itemid');
		
		$company =  $model->getCompanyByUid($uid);	
		header('Content-type: image/jpeg');
      echo $company->logo;
   }
	

	function savejob() //save job
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		
		$return_value = $model->storeJob();
		if ($return_value == 1)	{
			$msg = JText :: _('JOB_SAVED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$Itemid;
		}else if ($return_value == 11){ // start date not in oldate
			$msg = JText :: _('JS_START_DATE_NOT_OLD_DATE');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$Itemid;
		}else if ($return_value == 12){
			$msg = JText :: _('JS_START_DATE_NOT_LESS_STOP_DATE');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('ERROR_SAVING_JOB');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function savecompany() //save company
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		
		$return_value = $model->storeCompany();
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		if ($return_value == 1)	{ $msg = JText :: _('COMPANY_SAVED');
		}else if ($return_value == 2){	
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid='.$Itemid;
		}else if ($return_value == 5){ 
			$msg = JText :: _('JS_ERROR_LOGO_SIZE_LARGER');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid='.$Itemid;
		}else{	$msg = JText :: _('ERROR_SAVING_COMPANY');
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);

	}

	function saveresume()
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');

		$return_value = $model->storeResume();
		if ($return_value == 1)	{
			$msg = JText :: _('EMP_APP_SAVED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&uid='.$uid.'&Itemid='.$Itemid;
$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$Itemid;
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&Itemid='.$Itemid;
		}else if ($return_value == 6){ // file type mismatch
			$msg = JText :: _('JS_FILE_TYPE_ERROR');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&Itemid='.$Itemid;
		}else if ($return_value == 7){ // photo file size 
			$msg = JText :: _('JS_ERROR_PHOTO_SIZE_LARGER');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('ERROR_SAVING_EMP_APP');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&uid='.$uid.'&Itemid='.$Itemid;
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function jobtypes()
	{
		global $mainframe;
		$model = $this->getModel('jsjobs', 'JSJobsModel');
	     $id=JRequest::getVar( 'id');
	     $val=JRequest::getVar( 'val');
	    $fild=JRequest::getVar( 'fild');

		$return_value = $model->jobTypes($id, $val, $fild);
		echo $return_value;
		$mainframe->close();
	}

	function savenewinjsjobs() //save new in jsjobs
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$usertype = $data['usertype'];
		
		$return_value = $model->storeNewinJSJobs();
		if ($usertype == 1) // employer
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$Itemid;
		elseif ($usertype == 2 )// job seeker
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$Itemid;
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SAVE_SETTINGS');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_SETTING');
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function savecoverletter() //save cover letter
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		
		$return_value = $model->storeCoverLetter();
		if ($return_value == 1)	{
			$msg = JText :: _('JS_COVER_LETTER_SAVED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$Itemid;
		}else if ($return_value == 2){
			$msg = JText :: _('JS_FILL_REQ_FIELDS');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_COVER_LETTER');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid='.$Itemid;
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function savefilter() //save filter
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$link = $data['formaction'];
		$return_value = $model->storeFilter();
		
		if ($return_value == 1)	{
			$_SESSION['jsuserfilter'] ='';
			$msg = JText :: _('JS_FILTER_SAVED');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_FILTER');
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function savejobsearch() //save job search
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$user	=& JFactory::getUser();

		$data['uid'] = $user->id;
		$data['jobtitle'] = $_SESSION['jobsearch_title'];	
		$data['category']= $_SESSION['jobsearch_jobcategory'];
		$data['jobtype']= $_SESSION['jobsearch_jobtype'];
		$data['jobstatus']= $_SESSION['jobsearch_jobstatus'];
		$data['heighesteducation']= $_SESSION['jobsearch_heighestfinisheducation'];
		$data['salaryrange']= $_SESSION['jobsearch_jobsalaryrange'];
		$data['shift']= $_SESSION['jobsearch_shift'];	
		$data['experience']= $_SESSION['jobsearch_experience'];	
		$data['durration']= $_SESSION['jobsearch_durration'];	
		$data['startpublishing']= $_SESSION['jobsearch_startpublishing'];	
		$data['stoppublishing']= $_SESSION['jobsearch_stoppublishing'];	
		$data['company']= $_SESSION['jobsearch_company'];	
		$data['country_istext']= 0;
		$data['country']= $_SESSION['jobsearch_country'];
		$data['state_istext']= 0;
		$data['state']= $_SESSION['jobsearch_state'];
		$data['county_istext']= 0;
		$data['county']= $_SESSION['jobsearch_county'];
		$data['city_istext']= 0;
		$data['city']= $_SESSION['jobsearch_city'];
		$data['zipcode_istext']= 0;
		$data['zipcode']= $_SESSION['jobsearch_zipcode'];
		$data['created']= date('Y-m-d H:i:s');
		$data['status']= 1;

//		$link = $data['formaction'];
		$return_value = $model->storeJobSearch($data);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_SAVED');
		}elseif ($return_value == 3){
			$msg = JText :: _('JS_LIMIT_EXCEED_OR_ADMIN_BLOCK_THIS');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_SEARCH');
		}
		// final redirect
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$Itemid;
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function saveresumesearch() //save resume search
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$user	=& JFactory::getUser();

		$data['uid'] = $user->id;
		$data['application_title'] = $_SESSION['resumesearch_title'];
		$data['nationality'] = $_SESSION['resumesearch_nationality'];
		$data['gender'] = $_SESSION['resumesearch_gender'];
		$data['iamavailable'] = $_SESSION['resumesearch_iamavailable'];
		$data['category'] = $_SESSION['resumesearch_jobcategory'];
		$data['jobtype'] = $_SESSION['resumesearch_jobtype'];
		$data['salaryrange'] = $_SESSION['resumesearch_jobsalaryrange'];
		$data['education'] = $_SESSION['resumesearch_heighestfinisheducation'];
		$data['experience'] = $_SESSION['resumesearch_experience'];
		$data['created']= date('Y-m-d H:i:s');
		$data['status']= 1;

//		$link = $data['formaction'];
		$return_value = $model->storeResumeSearch($data);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_SAVED');
		}elseif ($return_value == 3){
			$msg = JText :: _('JS_LIMIT_EXCEED_OR_ADMIN_BLOCK_THIS');
		}else{
			$msg = JText :: _('JS_ERROR_SAVING_SEARCH');
		}
		// final redirect
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$Itemid;
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function deletefilter() //delete filter
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$uid = &JRequest::getString('uid','none');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		$data = JRequest :: get('post');
		$link = $data['formaction'];
		$return_value = $model->deleteUserFilter();
		
		if ($return_value == 1)	{
			$_SESSION['jsuserfilter'] ='';
			$msg = JText :: _('JS_FILTER_DELETED');
		}else{
			$msg = JText :: _('JS_ERROR_DELETING_FILTER');
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function jobshifts()
	{
		global $mainframe;
		$model = $this->getModel('jsjobs', 'JSJobsModel');
	     $id=JRequest::getVar( 'id');
	     $val=JRequest::getVar( 'val');
	     $fild=JRequest::getVar( 'fild');

		$return_value = $model->jobShifts($id, $val, $fild);
		echo $return_value;
		$mainframe->close();
	}

	function deletejobsearch() //delete job search
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$data = JRequest :: get('post');
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=my_jobsearches&Itemid='.$Itemid;
		$searchid =  JRequest::getVar('js');
		$return_value = $model->deleteJobSearch($searchid, $uid);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_DELETED');
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_NOT_YOUR_SEARCH');
		}else{
			$msg = JText :: _('JS_ERROR_DELETING_SEARCH');
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function deleteresumesearch() //delete resume search
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$data = JRequest :: get('post');
		$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=my_resumesearches&Itemid='.$Itemid;
		$searchid =  JRequest::getVar('rs');
		$return_value = $model->deleteResumeSearch($searchid, $uid);
		
		if ($return_value == 1)	{
			$msg = JText :: _('JS_SEARCH_DELETED');
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_NOT_YOUR_SEARCH');
		}else{
			$msg = JText :: _('JS_ERROR_DELETING_SEARCH');
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function deletecoverletter() //delete cover letter
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		
		$coverletterid =  JRequest::getVar('cl');
		$return_value = $model->deleteCoverLetter($coverletterid, $uid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_COVER_LETTER_DELETED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$Itemid;
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_NOT_YOUR_COVER_LETTER');
		}else{
			$msg = JText :: _('JS_ERROR_DELETEING_COVER_LETTER');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid='.$Itemid;
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function deleteresume() //delete resume
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		
		$resumeid =  JRequest::getVar('rd');

		$return_value = $model->deleteResume($resumeid, $uid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_RESUME_DELETED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_RESUME_INUSE_CANNOT_DELETE');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}elseif ($return_value == 3){
			$msg = JText :: _('JS_NOT_YOUR_RESUME');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('JS_ERROR_DELETEING_RESUME');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function deletejob() //delete job
	{
		global $mainframe;
		
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		
		$jobid =  JRequest::getVar('bd');

		$return_value = $model->deleteJob($jobid, $uid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_JOB_DELETED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_JOB_INUSE_CANNOT_DELETE');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}elseif ($return_value == 3){
			$msg = JText :: _('JS_NOT_YOUR_JOB');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('JS_ERROR_DELETEING_JOB');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$Itemid;
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
	}

	function deletecompany() //delete company
	{
		global $mainframe;
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$session = &JFactory::getSession();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$Itemid =  JRequest::getVar('Itemid');
		
		$companyid =  JRequest::getVar('md');
		
		$return_value = $model->deleteCompany($companyid, $uid);
		if ($return_value == 1)	{
			$msg = JText :: _('JS_COMPANY_DELETED');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		}elseif ($return_value == 2){
			$msg = JText :: _('JS_COMPANY_CANNOT_DELETE');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		}elseif ($return_value == 3){
			$msg = JText :: _('JS_NOT_YOUR_COMPANY');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		}else{
			$msg = JText :: _('JS_ERROR_DELETING_COMPANY');
			$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$Itemid;
		}
		$this->setRedirect($link, $msg);
		//$this->setRedirect(JRoute::_($link), $msg);
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

	function listsearchaddressdata()
	  { 
		 global $mainframe;
	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$returnvalue = $model->listSearchAddressData($data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }

	function listfilteraddressdata()
	  { 
		 global $mainframe;
	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$returnvalue = $model->listFilterAddressData($data, $val);
		
		echo $returnvalue;
		$mainframe->close();
	  }

	function listmodsearchaddressdata()
	  { 
		 global $mainframe;
	     $data=JRequest::getVar( 'data');
	     $val=JRequest::getVar( 'val');
	     $for=JRequest::getVar( 'for');
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$returnvalue = $model->listModuleSearchAddressData($data, $val, $for);
		
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
		$viewName = JRequest :: getVar('view', 'resume');
		$layoutName = JRequest :: getVar('layout', 'jobcat');
		$viewType = $document->getType();
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		$view = & $this->getView($viewName, $viewType);
		if (!JError :: isError($model))
		{
			$view->setModel($model, true);
		}
		$view->setLayout($layoutName);
		$view->display();
	}

}
?>
