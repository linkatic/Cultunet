<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/employer/view.html.php
 ^ 
 * Description: HTML view class for employer
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JSJobsViewEmployer extends JView
{

	function display($tpl = null)
	{
		global $mainframe, $sorton, $sortorder, $option;

		$user	=& JFactory::getUser();
		$uid=$user->id;
		$itemid =  JRequest::getVar('Itemid');
		
		$layout =  JRequest::getVar('layout');

		$needlogin = false;
		if (($layout== 'controlpanel') || ($layout== 'mycompanies') || ($layout== 'formcompany')
			|| ($layout== 'myjobs') || ($layout== 'alljobsappliedapplications') || ($layout== 'formjob') 
			|| ($layout== 'resumesearch') || ($layout== 'resume_searchresults')
			|| ($layout== 'my_resumesearches')|| ($layout== 'viewresumesearch')
			|| ($layout== 'job_appliedapplications'))
		{
			if ($user->guest) { // redirect user if not login
				$needlogin = true;
				$mainframe->redirect('index.php?option=com_user&view=login');
			} 
		}
		$model		= &$this->getModel();
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
		
		// get user role
		if (isset($_SESSION['jsuserrole'])) $userrole = $_SESSION['jsuserrole']; else $userrole=null;
		//$config = Array();
		if (sizeof($userrole) == 0){
			$userrole =  $model->getUserRole($uid);	
			if (isset($userrole)){ //not empty
				$_SESSION['jsuserrole'] = $userrole;	
			}else{
				if ($layout != 'view_job') {
					if (! $user->guest){//echo '<br> new in jsjobs';
						$mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=new_injsjobs&Itemid='.$itemid);
					}	
				}	
			}
		}
		// get configurations
		$config = Array();
		if (isset($_SESSION['jsjobconfig'])) $config = $_SESSION['jsjobconfig']; else $config = null;
		//$config = Array();
		if (sizeof($config) == 0){
			$results =  $model->getConfig();	
			if (isset($results)){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
				$result =  $model->getTypeStatus();	
				$type .= $result[0];
				$value = $result[1];
				$config[$type] = $value;
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
		
		//Mod by Vicente Gimeno
		//No mostramos la barra privada del usuario si no está logado
		
				/* $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$itemid;
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
		$id = & $this->get('Id');
		
		if($layout== 'myjobs'){ 							// my jobs
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			if ($limit != '') {	$_SESSION['limit']=$limit;
			}else if ($limit == '') {$limit=$_SESSION['limit'];	}
			$result =  $model->getMyJobs($uid,$sortby,$limit,$limitstart);	
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('jobs', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'mycompanies'){ 							// my companies
			$result =  $model->getMyCompanies($uid,$limit,$limitstart);	
			$companies = $result[0];
			$totalresults = $result[1];
			$this->assignRef('companies', $companies);
			$this->assignRef('totalresults', $totalresults);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
		}elseif($layout== 'alljobsappliedapplications'){				 // all jobs applied application
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			$result =  $model->getJobsAppliedResume($uid,$sortby,$limit,$limitstart);	
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('jobs', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'job_appliedapplications'){									 // job applied applications
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='apply_datedesc';}
			}else
				{$sort='apply_datedesc';}
			$sortby = $this->getEmpListOrdering($sort);
			//$jobid = $_GET['jobid'];
			$jobid =  JRequest::getVar('bd','');	

			$result =  $model->getJobAppliedResume($uid,$jobid,$sortby,$limit,$limitstart);	
			$application = $result[0];
			$totalresults = $result[1];
			$sortlinks = $this->getEmpListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('resume', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'view_company'){ 															// view company
			if (isset($_GET['md']))
				$companyid = $_GET['md'];
			if (!isset($companyid)) $companyid='';
			
			$companyid =  JRequest::getVar('md','');	
			$result =  $model->getCompanybyId($companyid);	
			$this->assignRef('company', $result[0]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('vm', JRequest::getVar('vm',''));
			$this->assignRef('jobcat', JRequest::getVar('jobcat',''));
		}elseif($layout== 'view_job'){ 															// view job
			if ($user->guest) { // redirect user if not login
				$needlogin = true;
				$mainframe->redirect('index.php?option=com_user&view=login');
			} else
			{
				if (isset($_GET['oi']))
					$jobid = $_GET['oi'];
				if (!isset($jobid)) $jobid='';
				
				$jobid =  JRequest::getVar('oi','');	
				$result =  $model->getJobbyId($jobid);	
				$convocante =  $model->getConvocante($jobid);
				if($convocante) $this->assignRef('convocante', $convocante);
				$this->assignRef('job', $result[0]);
				$this->assignRef('userfields', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
				$this->assignRef('vj', JRequest::getVar('vj',''));
				$this->assignRef('jobcat', JRequest::getVar('jobcat',''));
				$this->assignRef('fr', JRequest::getVar('fr',''));
			}

		}elseif($layout== 'formjob'){												// form job
			if (isset($_GET['bd']))
				$jobid = $_GET['bd'];
			if (!isset($jobid)) $jobid='';
			$result =  $model->getJobforForm($jobid, $uid);	
			$this->assignRef('job', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('canaddnewjob', $result[4]);
			$this->assignRef('empresa', $result[5]);
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'formcompany'){											// form company
			if (isset($_GET['md']))
				$companyid = $_GET['md'];
			$companyid =  JRequest::getVar('md','');	
			if (!isset($companyid)) $companyid='';
			
			$result =  $model->getCompanybyIdforForm($companyid, $uid);	
			$this->assignRef('company', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('canaddnewcompany', $result[4]);
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'resumesearch'){											// resume search
			$searchoptions =  $model->getResumeSearchOptions();	
			$this->assignRef('searchoptions', $searchoptions);
		}elseif($layout== 'resume_searchresults'){ 															// resume search results
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='create_datedesc';}
			}else
				{$sort='create_datedesc';}
			$sortby = $this->getResumeListOrdering($sort);
			if ($limit != '') {	$_SESSION['limit']=$limit;
			}else if ($limit == '') {$limit=$_SESSION['limit'];	}
			if (isset($_POST['isresumesearch'])){
				if ($_POST['isresumesearch'] == '1'){
					$_SESSION['resumesearch_title'] = $_POST['title'];
					$_SESSION['resumesearch_name'] = $_POST['name'];
					$_SESSION['resumesearch_nationality'] = $_POST['nationality'];
					$_SESSION['resumesearch_gender'] = $_POST['gender'];
					if (isset($_SESSION['resumesearch_iamavailable'])) $_SESSION['resumesearch_iamavailable'] = $_POST['iamavailable'];
					$_SESSION['resumesearch_jobcategory'] = $_POST['jobcategory'];
					$_SESSION['resumesearch_jobtype'] = $_POST['jobtype'];
					if(isset($_POST['jobsalaryrange']))
						$_SESSION['resumesearch_jobsalaryrange'] = $_POST['jobsalaryrange'];
					$_SESSION['resumesearch_heighestfinisheducation'] = $_POST['heighestfinisheducation'];
					$_SESSION['resumesearch_experience'] = $_POST['experience'];
				}
			}
			$jobstatus='';
			$title = $_SESSION['resumesearch_title'];
			$name = $_SESSION['resumesearch_name'];
			$nationality = $_SESSION['resumesearch_nationality'];
			$gender = $_SESSION['resumesearch_gender'];
			$iamavailable = '';//$_SESSION['resumesearch_iamavailable'];
			$jobcategory = $_SESSION['resumesearch_jobcategory'];
			$jobtype = $_SESSION['resumesearch_jobtype'];
			$jobsalaryrange = $_SESSION['resumesearch_jobsalaryrange'];
			$education = $_SESSION['resumesearch_heighestfinisheducation'];
			$experience = $_SESSION['resumesearch_experience'];
			
			$result =  $model->getResumeSearch($title,$name,$nationality,$gender,$iamavailable,$jobcategory,$jobtype,$jobstatus,$jobsalaryrange,$education
			, $experience,$sortby,$limit,$limitstart);	
			$options =  $this->get('Options');
			$sortlinks = $this->getResumeListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('resumes', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'my_resumesearches'){												// my resume searches
			$result =  $model->getMyResumeSearchesbyUid($uid,$limit,$limitstart);	
			$this->assignRef('jobsearches', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
		}elseif($layout== 'viewresumesearch'){												// view resume seach
			//$id = $_GET['rs'];
			$id =  JRequest::getVar('rs','');	
			$search =  $model->getResumeSearchebyId($id);	
			if (isset ($search)){
			$_SESSION['resumesearch_title'] = $search->application_title;
			if($search->nationality != 0) $_SESSION['resumesearch_nationality'] = $search->nationality;
			if($search->gender != 0)$_SESSION['resumesearch_gender'] = $search->gender;
			if($search->iamavailable != 0)$_SESSION['resumesearch_iamavailable'] = $search->iamavailable;
			if($search->category != 0)$_SESSION['resumesearch_jobcategory'] = $search->category;
			if($search->jobtype != 0) $_SESSION['resumesearch_jobtype'] = $search->jobtype;
			if($search->salaryrange != 0) $_SESSION['resumesearch_jobsalaryrange'] = $search->salaryrange;
			if($search->education != 0) $_SESSION['resumesearch_heighestfinisheducation'] = $search->education;
			$_SESSION['resumesearch_experience'] = $search->experience;
			
			}
			$mainframe->redirect( JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$itemid));
		}
		
		$this->assignRef('userrole', $userrole);
		$this->assignRef('config', $config);
		$this->assignRef('theme', $theme);
		$this->assignRef('params', $params);
		$this->assignRef('viewtype', $viewtype);
		$this->assignRef('employerlinks', $employerlinks);
		$this->assignRef('jobseekerlinks', $jobseekerlinks);
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

		parent :: display($tpl);
		//if($result->group_id==32) parent :: display($tpl);	
		//else die('SOLAMENTE AUTORIZADO PARA EMPRESAS');
		
		
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

	function getResumeListSorting( $sort ) {
		$sortlinks['application_title'] = $this->getSortArg("application_title",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['salaryrange'] = $this->getSortArg("salaryrange",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		
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
