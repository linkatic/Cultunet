<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/jobseeker/view.html.php
 ^ 
 * Description: HTML view class for applications
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JSJobsViewJobseeker extends JView
{

	function display($tpl = null)
	{
		global $mainframe, $sorton, $sortorder, $option;

		$user	=& JFactory::getUser();
		$uid=$user->id;

		$itemid =  JRequest::getVar('Itemid');
		$layout =  JRequest::getVar('layout');


		//if (($layout != 'controlpanel') && ($layout != 'jobcat') && ($layout != 'list_jobs') && ($layout != 'listnewestjobs') && ($layout != 'jobsearch')/* && ($layout != 'job_searchresults')*/)
			//$needlogin = true;
		//else 
			//$needlogin = false;
		
		//if ($needlogin == true ){
			//if ($user->guest) { // redirect user if not login
				//$mainframe->redirect('index.php?option=com_user&view=login');
			//} 
		//}
		
		$model		= &$this->getModel();
		if($option == '')
			$option='com_jsjobs';
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		$limitstart =  JRequest::getVar('limitstart',0);

		if (isset($_SESSION['jsjobs_layout'])){
			if ($layout != $_SESSION['jsjobs_layout']) {
				$_SESSION['jsjobs_layout'] = $layout;
				$limitstart = 0;
			}
		}else $_SESSION['jsjobs_layout'] = $layout;

		$viewtype = 'html';
		$type = 'offl';
		$params = & $mainframe->getPageParameters('com_jsjobs');
		$id = & $this->get('Id');

		if($layout != 'new_injsjobs'){
			if (isset($_SESSION['jsuserrole'])) $userrole = $_SESSION['jsuserrole']; else $userrole=null;
			//$config = Array();
			if (sizeof($userrole) == 0){
				$userrole =  $model->getUserRole($uid);	
				if (isset($userrole)){ //not empty
					$_SESSION['jsuserrole'] = $userrole;	
				}else{
					if(($needlogin == true) && $layout != 'new_injsjobs'){//echo '<br> new in jsjobs';
						$mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=new_injsjobs&Itemid='.$itemid);
					}	
				}
			}
		}	
		
		// get configurations
		$config = Array();
		if (isset($_SESSION['jsjobconfig'])){
			if($_SESSION['jsjobconfig']) $config = $_SESSION['jsjobconfig'];
		}
		//$config = Array();
		if (sizeof($config) == 0){
			$results =  $model->getConfig();	
			if ($results){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
				$result =  $model->getTypeStatus();	
				$type .= $result[0];
				$value = $result[1];
				if ($config[$type] != 1) $config[$type] = $value;
				$_SESSION['jsjobconfig'] = $config;	
			}
		}
		
		$themevalue = $config['theme'];
		if ($themevalue != 'templatetheme.css'){
			$theme['title'] = 'jppagetitle';
			$theme['heading'] = 'pageheadline';
			$theme['sectionheading'] = 'sectionheadline';
			$theme['sortlinks'] = 'sortlnks';
			$theme['odd'] = 'odd';
			$theme['even'] = 'even';
		}else{
			$theme['title'] = 'componentheading';
			$theme['heading'] = 'contentheading';
			$theme['sectionheading'] = 'sectiontableheader';
			$theme['sortlinks'] = 'sectiontableheader';
			$theme['odd'] = 'sectiontableentry1';
			$theme['even'] = 'sectiontableentry2';
		}

		// get save filter
		if ($uid != ''){
			if (isset($_SESSION['jsuserfilter']))$userfilter = $_SESSION['jsuserfilter'];else $userfilter='';
			//$config = Array();
			if (sizeof($userfilter) == 0){
				$result =  $model->getUserFilter($uid);	
				if (isset($result)){ //not empty
					$userfilter[0] = 1;
					$userfilter[1] = $result;
					$_SESSION['jsuserfilter'] = $userfilter;	
					$filterid = $result->id;
				}else{
					$userfilter[0] = 1;
					$_SESSION['jsuserfilter'] = $userfilter;	
				}
			}
		} else $userfilter = '';

		if(isset($userrole->rolefor)) {
			if ($userrole->rolefor == 1) { // employer
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_NEW_JOB'),0);
				
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_JOBS'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_COMPANIES'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_APPLIED_RESUME'),-1);
			}else{

//		if ($cur_user_allow[1] == 1) { // Emp Allow
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
				
				
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_JOB_CATEGORIES'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_SEARCH_JOB'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_MY_APPLIED_JOBS'),0);
			
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_MY_RESUMES'),-1);
			}
		}else{ // user not logined
			/*	$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_JOB_CATEGORIES'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_SEARCH_JOB'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_MY_APPLIED_JOBS'),0);
			
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_MY_RESUMES'),-1); */
			}
		//$id = & $this->get('Id');
		
		if($layout== 'jobcat'){									//job cat
			
			$cmbfiltercountry = $mainframe->getUserStateFromRequest( $option.'cmbfilter_country', 'cmbfilter_country',	'',	'string' );
			$cmbfilterstate = $mainframe->getUserStateFromRequest( $option.'cmbfilter_state', 'cmbfilter_state',	'',	'string' );
			$cmbfiltercounty = $mainframe->getUserStateFromRequest( $option.'cmbfilter_county', 'cmbfilter_county',	'',	'string' );
			$cmbfiltercity = $mainframe->getUserStateFromRequest( $option.'cmbfilter_city', 'cmbfilter_city',	'',	'string' );
			$txtfiltercountry='';
			$txtfilterstate = $mainframe->getUserStateFromRequest( $option.'txtfilter_state', 'txtfilter_state',	'',	'string' );
			$txtfiltercounty = $mainframe->getUserStateFromRequest( $option.'txtfilter_county', 'txtfilter_county',	'',	'string' );
			$txtfiltercity = $mainframe->getUserStateFromRequest( $option.'txtfilter_city', 'txtfilter_city',	'',	'string' );

			$filterjobsalaryrange = $mainframe->getUserStateFromRequest( $option.'filter_jobsalaryrange', 'filter_jobsalaryrange',	'',	'string' );
			$filterheighesteducation = $mainframe->getUserStateFromRequest( $option.'filter_heighesteducation', 'filter_heighesteducation',	'',	'string' );
			$filterjobcategory = $mainframe->getUserStateFromRequest( $option.'filter_jobcategory', 'filter_jobcategory',	'',	'string' );
			$filterjobtype = $mainframe->getUserStateFromRequest( $option.'filter_jobtype', 'filter_jobtype',	'',	'string' );

			if (isset($userfilter[1])) {
				if(($cmbfiltercountry == '') && ($cmbfilterstate == '') && ($cmbfiltercounty == '') && ($cmbfiltercity == '') 
					&& ($txtfilterstate == '') && ($txtfiltercounty == '') && ($txtfiltercity == '') 
					&& ($filterjobsalaryrange == '') && ($filterheighesteducation == '') && ($filterjobtype == '')
				){
					$filter = $userfilter[1];
					$cmbfiltercountry = $filter->country;
					if( $filter->state_istext == 1) if($filter->state != '') $txtfilterstate = $filter->state; else $cmbfilterstate = $filter->state;
					if( $filter->county_istext == 1) if($filter->county != '') $txtfiltercounty = $filter->county; else $cmbfiltercounty = $filter->county;
					if( $filter->city_istext == 1) if($filter->city != '') $txtfiltercity = $filter->city; else $cmbfiltercity = $filter->city;

					if($filter->salaryrange != 0) $filterjobsalaryrange = $filter->salaryrange;
					if($filter->heighesteducation != 0) $filterheighesteducation = $filter->heighesteducation;
					if($filterjobtype != 0) $filterjobtype = $filter->jobtype;
//					echo '<br>empty';
				}
//				echo '<br>get user filter';
			}
			$result = $model->getJobCat($cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
											,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
											,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype );	


			$application = $result[0];
			$filterlists =  $result[2];
			$filtervalues =  $result[3];
//			$this->assignRef('jobs', $jobs);
			$this->assignRef('filterlists', $filterlists);
			$this->assignRef('filtervalues', $filtervalues);
			$this->assignRef('filterid', $filterid);
		
		}elseif($layout== 'list_jobs'){ 											// list jobs
			$catid='';
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			$sort =  JRequest::getVar('sortby','');
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			
			$fr =  JRequest::getVar('fr');
			if (isset($fr))
				$for = $fr;
			if (isset($for)){
				if ($for == '') {$for='lj';}
			}else{$for='lj';}
			
			
			
			$cmbfiltercountry = $mainframe->getUserStateFromRequest( $option.'cmbfilter_country', 'cmbfilter_country',	'',	'string' );
			$cmbfilterstate = $mainframe->getUserStateFromRequest( $option.'cmbfilter_state', 'cmbfilter_state',	'',	'string' );
			$cmbfiltercounty = $mainframe->getUserStateFromRequest( $option.'cmbfilter_county', 'cmbfilter_county',	'',	'string' );
			$cmbfiltercity = $mainframe->getUserStateFromRequest( $option.'cmbfilter_city', 'cmbfilter_city',	'',	'string' );
			$txtfiltercountry='';
			$txtfilterstate = $mainframe->getUserStateFromRequest( $option.'txtfilter_state', 'txtfilter_state',	'',	'string' );
			$txtfiltercounty = $mainframe->getUserStateFromRequest( $option.'txtfilter_county', 'txtfilter_county',	'',	'string' );
			$txtfiltercity = $mainframe->getUserStateFromRequest( $option.'txtfilter_city', 'txtfilter_city',	'',	'string' );

			$filterjobsalaryrange = $mainframe->getUserStateFromRequest( $option.'filter_jobsalaryrange', 'filter_jobsalaryrange',	'',	'string' );
			$filterheighesteducation = $mainframe->getUserStateFromRequest( $option.'filter_heighesteducation', 'filter_heighesteducation',	'',	'string' );
			$filterjobtype = $mainframe->getUserStateFromRequest( $option.'filter_jobtype', 'filter_jobtype',	'',	'string' );

			//$filterjobcategory = $mainframe->getUserStateFromRequest(option.'', 'filter_jobcategory',	'',	'string' );
			if (isset($_POST['filter_jobcategory']))$filterjobcategory =$_POST['filter_jobcategory'];else $filterjobcategory='';
			if (isset($userfilter[1])) {
				if(($cmbfiltercountry == '') && ($cmbfilterstate == '') && ($cmbfiltercounty == '') && ($cmbfiltercity == '') 
					&& ($txtfilterstate == '') && ($txtfiltercounty == '') && ($txtfiltercity == '') 
					&& ($filterjobsalaryrange == '') && ($filterheighesteducation == '') && ($filterjobtype == '')
				){
					$filter = $userfilter[1];
					$cmbfiltercountry = $filter->country;
					if( $filter->state_istext == 1) $txtfilterstate = $filter->state; else $cmbfilterstate = $filter->state;
					if( $filter->county_istext == 1) $txtfiltercounty = $filter->county; else $cmbfiltercounty = $filter->county;
					if( $filter->city_istext == 1) $txtfiltercity = $filter->city; else $cmbfiltercity = $filter->city;

					if($filter->salaryrange != 0) $filterjobsalaryrange = $filter->salaryrange;
					if($filter->heighesteducation != 0) $filterheighesteducation = $filter->heighesteducation;
					if($filter->jobtype != 0) $filterjobtype = $filter->jobtype;
				}
			}
			if ($filterjobcategory) $catid = $filterjobcategory;

			if ($for == 'lj'){ // list by category 

				$cat_id =  JRequest::getVar('jobcat');
				if ($catid == 0 )  $catid = $cat_id;
				$result =  $model->getJobsbyCategory($catid,$cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
															,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
															,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype 
															,$sortby,$limit,$limitstart);	
			
			}elseif ($for == 'cj'){ // list by company
				$companyid =  JRequest::getVar('cd');
				$result =  $model->getJobsbyCompany($companyid,$cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
														,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
														,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype 
														,$sortby,$limit,$limitstart);	
			}												
			$jobs = $result[0];
			$totalresults = $result[1];
			$filterlists =  $result[2];
			$filtervalues =  $result[3];
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('jobs', $jobs);
			$this->assignRef('totalresults', $totalresults);
			$this->assignRef('filterlists', $filterlists);
			$this->assignRef('filtervalues', $filtervalues);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('sortlinks', $sortlinks);
			$this->assignRef('listfor', $for);
			$this->assignRef('categoryid', $catid);
			$this->assignRef('companyid', $companyid);
			$this->assignRef('filterid', $filterid);
			$this->assignRef('fr', $for);
			$this->assignRef('cm', JRequest::getVar('cm',''));
		}elseif($layout == 'listnewestjobs'){ 											// list newest jobs
			$cmbfiltercountry = $mainframe->getUserStateFromRequest( $option.'cmbfilter_country', 'cmbfilter_country',	'',	'string' );
			$cmbfilterstate = $mainframe->getUserStateFromRequest( $option.'cmbfilter_state', 'cmbfilter_state',	'',	'string' );
			$cmbfiltercounty = $mainframe->getUserStateFromRequest( $option.'cmbfilter_county', 'cmbfilter_county',	'',	'string' );
			$cmbfiltercity = $mainframe->getUserStateFromRequest( $option.'cmbfilter_city', 'cmbfilter_city',	'',	'string' );
			$txtfiltercountry='';
			$txtfilterstate = $mainframe->getUserStateFromRequest( $option.'txtfilter_state', 'txtfilter_state',	'',	'string' );
			$txtfiltercounty = $mainframe->getUserStateFromRequest( $option.'txtfilter_county', 'txtfilter_county',	'',	'string' );
			$txtfiltercity = $mainframe->getUserStateFromRequest( $option.'txtfilter_city', 'txtfilter_city',	'',	'string' );

			$filterjobsalaryrange = $mainframe->getUserStateFromRequest( $option.'filter_jobsalaryrange', 'filter_jobsalaryrange',	'',	'string' );
			$filterheighesteducation = $mainframe->getUserStateFromRequest( $option.'filter_heighesteducation', 'filter_heighesteducation',	'',	'string' );
			$filterjobcategory = $mainframe->getUserStateFromRequest( $option.'filter_jobcategory', 'filter_jobcategory',	'',	'string' );
			$filterjobtype = $mainframe->getUserStateFromRequest( $option.'filter_jobtype', 'filter_jobtype',	'',	'string' );

			if (isset($userfilter[1])) {
				if(($cmbfiltercountry == '') && ($cmbfilterstate == '') && ($cmbfiltercounty == '') && ($cmbfiltercity == '') 
					&& ($txtfilterstate == '') && ($txtfiltercounty == '') && ($txtfiltercity == '') 
					&& ($filterjobsalaryrange == '') && ($filterheighesteducation == '') && ($filterjobtype == '')
				){
					$filter = $userfilter[1];
					$cmbfiltercountry = $filter->country;
					if( $filter->state_istext == 1) if($filter->state != '') $txtfilterstate = $filter->state; else $cmbfilterstate = $filter->state;
					if( $filter->county_istext == 1) if($filter->county != '') $txtfiltercounty = $filter->county; else $cmbfiltercounty = $filter->county;
					if( $filter->city_istext == 1) if($filter->city != '') $txtfiltercity = $filter->city; else $cmbfiltercity = $filter->city;

					if($filter->salaryrange != 0) $filterjobsalaryrange = $filter->salaryrange;
					if($filter->heighesteducation != 0) $filterheighesteducation = $filter->heighesteducation;
					if($filterjobtype != 0) $filterjobtype = $filter->jobtype;
				}
			}
			$result = $model->getJobCat($cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
											,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
											,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype );	
			$result =  $model->getListNewestJobs($cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
											,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
											,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype
											,$limit,$limitstart);	
			
			$jobs = $result[0];
			$totalresults = $result[1];
			$filterlists =  $result[2];
			$filtervalues =  $result[3];
			$this->assignRef('jobs', $jobs);
			$this->assignRef('totalresults', $totalresults);
			$this->assignRef('filterlists', $filterlists);
			$this->assignRef('filtervalues', $filtervalues);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
		}elseif($layout== 'jobsearch'){ 														// job search 
			$searchoptions =  $model->getSearchOptions();	
			$this->assignRef('searchoptions', $searchoptions);
		}elseif($layout== 'job_searchresults'){ 															// job search results
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			if (isset($_POST['isjobsearch'])){
				if ($_POST['isjobsearch'] == '1'){
					$_SESSION['jobsearch_title'] = $_POST['title'];
					$_SESSION['jobsearch_jobcategory'] = $_POST['jobcategory'];
					$_SESSION['jobsearch_jobtype'] = $_POST['jobtype'];
					$_SESSION['jobsearch_jobstatus'] = $_POST['jobstatus'];
					if(isset($_POST['jobsalaryrange']))
						$_SESSION['jobsearch_jobsalaryrange'] = $_POST['jobsalaryrange'];
					$_SESSION['jobsearch_heighestfinisheducation'] = $_POST['heighestfinisheducation'];
					$_SESSION['jobsearch_shift'] = $_POST['shift'];
					$_SESSION['jobsearch_experience'] = $_POST['experience'];
					$_SESSION['jobsearch_durration'] = $_POST['durration'];
					$_SESSION['jobsearch_startpublishing'] = $_POST['startpublishing'];
					$_SESSION['jobsearch_stoppublishing'] = $_POST['stoppublishing'];
					$_SESSION['jobsearch_company'] = $_POST['company'];
					$_SESSION['jobsearch_country'] = $_POST['country'];
					$_SESSION['jobsearch_state'] = $_POST['state'];
					$_SESSION['jobsearch_county'] = $_POST['county'];
					$_SESSION['jobsearch_city'] = $_POST['city'];
					$_SESSION['jobsearch_zipcode'] = $_POST['zipcode'];
				}
			}	
			$title = $_SESSION['jobsearch_title'];
			$jobcategory = $_SESSION['jobsearch_jobcategory'];
			$jobtype = $_SESSION['jobsearch_jobtype'];
			$jobstatus = $_SESSION['jobsearch_jobstatus'];
			$jobsalaryrange = $_SESSION['jobsearch_jobsalaryrange'];
			$education = $_SESSION['jobsearch_heighestfinisheducation'];
			$shift = $_SESSION['jobsearch_shift'];
			$experience = $_SESSION['jobsearch_experience'];
			$durration = $_SESSION['jobsearch_durration'];
			$startpublishing = $_SESSION['jobsearch_startpublishing'];
			$stoppublishing = $_SESSION['jobsearch_stoppublishing'];
			$company = $_SESSION['jobsearch_company'];
			$country = $_SESSION['jobsearch_country'];
			$state = $_SESSION['jobsearch_state'];
			$county = $_SESSION['jobsearch_county'];
			$city = $_SESSION['jobsearch_city'];
			$zipcode = $_SESSION['jobsearch_zipcode'];
			
			$result =  $model->getJobSearch($title,$jobcategory,$jobtype,$jobstatus,$jobsalaryrange,$education
			,$shift, $experience, $durration, $startpublishing, $stoppublishing	
			,$company,$country,$state,$county,$city,$zipcode,$sortby,$limit,$limitstart);	
			$options =  $this->get('Options');
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$application = $result[0];
			$totalresults = $result[1];
			$this->assignRef('totalresults', $totalresults);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'job_apply'){ 											// job apply
			$jobid = $_GET['bi'];
			$jobresult =  $model->getJobbyIdforJobApply($jobid);	
			$result =  $model->getMyResumes($uid);	
			$this->assignRef('job', $jobresult[0]);
			$this->assignRef('myresumes', $result[0]);
			$this->assignRef('totalresume', $result[1]);
			$this->assignRef('jobcat', JRequest::getVar('jobcat',''));
			$this->assignRef('aj', JRequest::getVar('aj',''));
			$this->assignRef('fr', JRequest::getVar('fr',''));
		}elseif($layout== 'myappliedjobs'){											//my applied jobs
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
		//	if ($limit != '') {	$_SESSION['limit']=$limit;
		//	}else if ($limit == '') {$limit=$_SESSION['limit'];	}
			$result =  $model->getMyAppliedJobs($uid,$sortby,$limit,$limitstart);	
			$application = $result[0];
			$totalresults = $result[1];
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('totalresults', $totalresults);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'myresumes'){												// my resumes
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){	if ($sort == '') 	{$sort='createddesc';}
			}else	{$sort='createddesc';}
			$sortby = $this->getResumeListOrdering($sort);
			$result =  $model->getMyResumesbyUid($uid,$sortby,$limit,$limitstart);	
			$this->assignRef('resumes', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$sortlinks = $this->getResumeListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'formresume'){												// form resume
			if (isset($_GET['rd'])) $resumeid = $_GET['rd']; else $resumeid = '';
			$resumeid =  JRequest::getVar('rd','');	
			$result =  $model->getResumebyId($resumeid, $uid);	
			$resumelists =  $this->get('EmpOptions');
			$this->assignRef('resume', $result[0]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('canaddnewresume', $result[4]);
			$this->assignRef('resumelists', $resumelists);
			$this->assignRef('vm', JRequest::getVar('vm',''));
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'mycoverletters'){												// my cover letters
			$result =  $model->getMyCoverLettersbyUid($uid,$limit,$limitstart);	
			$this->assignRef('coverletters', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
		}elseif($layout== 'view_coverletters'){												// view cover letters
			$userid = $_GET['clu'];
			$userid =  JRequest::getVar('clu','');	
			$result =  $model->getMyCoverLettersbyUid($userid,$limit,$limitstart);	
			$this->assignRef('coverletters', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('vts', JRequest::getVar('vts',''));
			$this->assignRef('rd', JRequest::getVar('rd',''));
			$this->assignRef('bd', JRequest::getVar('bd',''));
		}elseif($layout== 'my_jobsearches'){												// my job searches
			$result =  $model->getMyJobSearchesbyUid($uid,$limit,$limitstart);	
			$this->assignRef('jobsearches', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
		}elseif($layout== 'viewjobsearch'){												// view job seach
			$id = $_GET['js'];
			$id =  JRequest::getVar('js','');	
			$search =  $model->getJobSearchebyId($id);	
			if (isset ($search)){
				$_SESSION['jobsearch_title'] = $search->jobtitle;
				if ($search->category != 0) $_SESSION['jobsearch_jobcategory'] = $search->category; else $_SESSION['jobsearch_jobcategory'] = '';
				if ($search->jobtype != 0) $_SESSION['jobsearch_jobtype'] = $search->jobtype; else $_SESSION['jobsearch_jobtype'] = '';
				if ($search->jobstatus != 0) $_SESSION['jobsearch_jobstatus'] = $search->jobstatus; else $_SESSION['jobsearch_jobstatus'] = '';
				if ($search->salaryrange != 0) $_SESSION['jobsearch_jobsalaryrange'] = $search->salaryrange; else $_SESSION['jobsearch_jobsalaryrange'] = '';
				if ($search->heighesteducation != 0) $_SESSION['jobsearch_heighestfinisheducation'] = $search->heighesteducation; else $_SESSION['jobsearch_heighestfinisheducation'] = '';
				if ($search->shift != 0) $_SESSION['jobsearch_shift'] = $search->shift; else $_SESSION['jobsearch_shift'] = '';
				$_SESSION['jobsearch_experience'] = $search->experience;
				$_SESSION['jobsearch_durration'] = $search->durration;
				if ($search->startpublishing != '0000-00-00 00:00:00') $_SESSION['jobsearch_startpublishing'] = $search->startpublishing; else $_SESSION['jobsearch_startpublishing'] = '';
				if ($search->stoppublishing != '0000-00-00 00:00:00') $_SESSION['jobsearch_stoppublishing'] = $search->stoppublishing; else $_SESSION['jobsearch_stoppublishing'] = '';
				if ($search->company != 0) $_SESSION['jobsearch_company'] = $search->company; else $_SESSION['jobsearch_company'] = '';
				$_SESSION['jobsearch_country'] = $search->country;
				$_SESSION['jobsearch_state'] = $search->state;
				$_SESSION['jobsearch_county'] = $search->county;
				$_SESSION['jobsearch_city'] = $search->city;
				$_SESSION['jobsearch_zipcode'] = $search->zipcode;
			
			}
			$mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$itemid);
		}elseif($layout== 'formcoverletter'){												// form cover letter
			if (isset($_GET['cl'])) $letterid = $_GET['cl']; else $letterid = null;
			$letterid =  JRequest::getVar('cl','');	
			$result =  $model->getCoverLetterbyId($letterid, $uid);	
			$this->assignRef('coverletter', $result[0]);
			$this->assignRef('canaddnewcoverletter', $result[4]);
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'view_coverletter'){												// view cover letter
			//$letterid = $_GET['cl'];
			$letterid =  JRequest::getVar('cl','');	
			$result =  $model->getCoverLetterbyId($letterid,null);	
			$this->assignRef('coverletter', $result[0]);
			$this->assignRef('vct', JRequest::getVar('vct',''));
		}elseif($layout== 'view_resume'){										// view resume
			if (isset($_GET['id']))
				$empid = $_GET['id'];
			else
				$empid = '';
			if ($empid != ''){
				$application =  $model->getEmpApplicationbyid($empid);	
			}else{
				//$resumeid = $_GET['rd'];
				$resumeid =  JRequest::getVar('rd','');	
				$result =  $model->getResumeViewbyId($resumeid);	
				$this->assignRef('resume', $result[0]);
				$this->assignRef('resume2', $result[1]);
				$this->assignRef('resume3', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
				$this->assignRef('vm', JRequest::getVar('vm',''));
				$this->assignRef('bd', JRequest::getVar('bd',''));
			}
		}elseif(($layout== 'resume_download') || ($layout== 'resume_view')){	// resume view & download
			$empid = $_GET['rq'];
			$application =  $model->getEmpApplicationbyid($empid);	
		}elseif($layout== 'new_injsjobs'){												// new in jsjobs
			$result =  $model->getUserType($uid);	
			$this->assignRef('usertype', $result[0]);
			$this->assignRef('lists', $result[1]);
		}
		
		$this->assignRef('application', $application);
		$this->assignRef('config', $config);
		$this->assignRef('theme', $theme);
		$this->assignRef('userrole', $userrole);
//		$options =  $this->get('Options');
		$this->assignRef('options', $options);
		$this->assignRef('params', $params);
		$this->assignRef('viewtype', $viewtype);
		$this->assignRef('jobseekerlinks', $jobseekerlinks);
		$this->assignRef('employerlinks', $employerlinks);
		$this->assignRef('uid', $uid);
		$this->assignRef('id', $id);
		$this->assignRef('Itemid', $itemid);
		$this->assignRef('pdflink', $pdflink);
		$this->assignRef('printlink', $printlink);
		
		
		//Incluimos la vista y el helper necesario del componente de JomSocial
		//para mostrar el menú privado en este componente
		
		//require_once (JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'views'.DS.'views.php');
		//require_once( JPATH_ROOT .DS.'components'.DS.'com_community'.DS.'helpers'.DS.'owner.php' );
		
		//$toolbar	= new CommunityView();
		
		//Mostramos el menu privado
		//$toolbar->showToolbar();
		

		// Obtenemos el grupo al que pertenece el usuario
		$db =& JFactory::getDBO();
 
		$query = "SELECT * FROM #__core_acl_groups_aro_map ag 
		INNER JOIN #__core_acl_aro a  
		INNER JOIN #__users u WHERE u.id=a.value AND a.id=ag.aro_id AND u.id=".$uid;
		$db->setQuery($query);
		$result = $db->loadObject();

        $this->assignRef( 'group_id', $result->group_id );


		parent :: display($tpl);
		//if($result->group_id==33) parent :: display($tpl);	
		//else die('SOLAMENTE AUTORIZADO PARA USUARIOS PARTICULARES REGISTRADOS');
	}
	
	function getJobListSorting( $sort ) {
		$sortlinks['title'] = $this->getSortArg("title",$sort);
		$sortlinks['category'] = $this->getSortArg("category",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['jobstatus'] = $this->getSortArg("jobstatus",$sort);
		$sortlinks['company'] = $this->getSortArg("company",$sort);
		$sortlinks['salaryrange'] = $this->getSortArg("salaryrange",$sort);
		$sortlinks['country'] = $this->getSortArg("country",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		$sortlinks['apply_date'] = $this->getSortArg("apply_date",$sort);
		
		return $sortlinks;
	}

	function getEmpListSorting( $sort ) {
		$sortlinks['name'] = $this->getSortArg("name",$sort);
		$sortlinks['category'] = $this->getSortArg("category",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['jobsalaryrange'] = $this->getSortArg("jobsalaryrange",$sort);
		$sortlinks['apply_date'] = $this->getSortArg("apply_date",$sort);
		$sortlinks['email'] = $this->getSortArg("email",$sort);
		
		return $sortlinks;
	}

	function getResumeListSorting( $sort ) {
		$sortlinks['application_title'] = $this->getSortArg("application_title",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['salaryrange'] = $this->getSortArg("salaryrange",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		
		return $sortlinks;
	}

	function getJobListOrdering( $sort ) {
		global $sorton, $sortorder;
		switch ( $sort ) {
			case "titledesc": $ordering = "job.title DESC"; $sorton = "title"; $sortorder="DESC"; break;
			case "titleasc": $ordering = "job.title ASC";  $sorton = "title"; $sortorder="ASC"; break;
			case "categorydesc": $ordering = "cat.cat_title DESC"; $sorton = "category"; $sortorder="DESC"; break;
			case "categoryasc": $ordering = "cat.cat_title ASC";  $sorton = "category"; $sortorder="ASC"; break;
			case "jobtypedesc": $ordering = "job.jobtype DESC";  $sorton = "jobtype"; $sortorder="DESC"; break;
			case "jobtypeasc": $ordering = "job.jobtype ASC";  $sorton = "jobtype"; $sortorder="ASC"; break;
			case "jobstatusdesc": $ordering = "job.jobstatus DESC";  $sorton = "jobstatus"; $sortorder="DESC"; break;
			case "jobstatusasc": $ordering = "job.jobstatus ASC";  $sorton = "jobstatus"; $sortorder="ASC"; break;
			case "companydesc": $ordering = "job.company DESC";  $sorton = "company"; $sortorder="DESC"; break;
			case "companyasc": $ordering = "job.company ASC";  $sorton = "company"; $sortorder="ASC"; break;
			case "salaryrangedesc": $ordering = "salary.rangeend DESC";  $sorton = "salaryrange"; $sortorder="DESC"; break;
			case "salaryrangeasc": $ordering = "salary.rangestart ASC";  $sorton = "salaryrange"; $sortorder="ASC"; break;
			case "countrydesc": $ordering = "country.name DESC";  $sorton = "country"; $sortorder="DESC"; break;
			case "countryasc": $ordering = "country.name ASC";  $sorton = "country"; $sortorder="ASC"; break;
			case "createddesc": $ordering = "job.created DESC";  $sorton = "created"; $sortorder="DESC"; break;
			case "createdasc": $ordering = "job.created ASC";  $sorton = "created"; $sortorder="ASC"; break;
			case "apply_datedesc": $ordering = "apply.apply_date DESC";  $sorton = "apply_date"; $sortorder="DESC"; break;
			case "apply_dateasc": $ordering = "apply.apply_date ASC";  $sorton = "apply_date"; $sortorder="ASC"; break;
			default: $ordering = "job.id DESC";
		}
		return $ordering;
	}

	function getResumeListOrdering( $sort ) {
		global $sorton, $sortorder;
		switch ( $sort ) {
			case "application_titledesc": $ordering = "resume.application_title DESC"; $sorton = "application_title"; $sortorder="DESC"; break;
			case "application_titleasc": $ordering = "resume.application_title ASC";  $sorton = "application_title"; $sortorder="ASC"; break;
			case "jobtypedesc": $ordering = "resume.jobtype DESC";  $sorton = "jobtype"; $sortorder="DESC"; break;
			case "jobtypeasc": $ordering = "resume.jobtype ASC";  $sorton = "jobtype"; $sortorder="ASC"; break;
			case "salaryrangedesc": $ordering = "salary.rangeend DESC";  $sorton = "salaryrange"; $sortorder="DESC"; break;
			case "salaryrangeasc": $ordering = "salary.rangestart ASC";  $sorton = "salaryrange"; $sortorder="ASC"; break;
			case "createddesc": $ordering = "resume.create_date DESC";  $sorton = "created"; $sortorder="DESC"; break;
			case "createdasc": $ordering = "resume.create_date ASC";  $sorton = "created"; $sortorder="ASC"; break;
			default: $ordering = "resume.id DESC";
		}
		return $ordering;
	}

	function getEmpListOrdering( $sort ) {
		global $sorton, $sortorder;
		switch ( $sort ) {
			case "namedesc": $ordering = "app.first_name DESC"; $sorton = "name"; $sortorder="DESC"; break;
			case "nameasc": $ordering = "app.first_name ASC";  $sorton = "name"; $sortorder="ASC"; break;
			case "categorydesc": $ordering = "cat.cat_title DESC"; $sorton = "category"; $sortorder="DESC"; break;
			case "categoryasc": $ordering = "cat.cat_title ASC";  $sorton = "category"; $sortorder="ASC"; break;
			case "jobtypedesc": $ordering = "app.jobtype DESC";  $sorton = "jobtype"; $sortorder="DESC"; break;
			case "jobtypeasc": $ordering = "app.jobtype ASC";  $sorton = "jobtype"; $sortorder="ASC"; break;
			case "jobsalaryrangedesc": $ordering = "salary.rangestart DESC";  $sorton = "jobsalaryrange"; $sortorder="DESC"; break;
			case "jobsalaryrangeasc": $ordering = "salary.rangestart ASC";  $sorton = "jobsalaryrange"; $sortorder="ASC"; break;
			case "apply_datedesc": $ordering = "apply.apply_date DESC";  $sorton = "apply_date"; $sortorder="DESC"; break;
			case "apply_dateasc": $ordering = "apply.apply_date ASC";  $sorton = "apply_date"; $sortorder="ASC"; break;
			case "emaildesc": $ordering = "app.email_address DESC";  $sorton = "email"; $sortorder="DESC"; break;
			case "emailasc": $ordering = "app.email_address ASC";  $sorton = "email"; $sortorder="ASC"; break;
			default: $ordering = "job.id DESC";
		}
		return $ordering;
	}

	function getSortArg( $type, $sort ) {
		$mat = array();
		if ( preg_match( "/(\w+)(asc|desc)/i", $sort, $mat ) ) {
			if ( $type == $mat[1] ) {
				return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
			} else {
				return $type . $mat[2];
			}
		}
		return "iddesc";
	}


}
?>
