<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/applications/view.html.php
 ^ 
 * Description: HTML view of all applications 
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JSJobsViewApplications extends JView
{
	function display($tpl = null)
	{
		$model		= &$this->getModel();
		global $mainframe, $option;

		// get configurations
		$config = Array();
		$results =  $model->getConfig();	
		if ($results){ //not empty
			foreach ($results as $result){
				$config[$result->configname] = $result->configvalue;
			}
		}

		$layoutName = JRequest :: getVar('layout', '');
		if ($layoutName == ''){
				$layoutName = $_SESSION['cur_layout'];
		}
		$_SESSION['cur_layout']=$layoutName;
		if(($layoutName == 'controlpanel') || ($layoutName == 'companiesqueue') || ($layoutName == 'jobqueue')
			|| ($layoutName == 'appqueue') || ($layoutName == 'updates') || ($layoutName == 'fieldsordering')  || ($layoutName == 'loadaddressdata')
			|| ($layoutName == 'appliedresumes') )
			$layoutName =$layoutName; //do nothing
		elseif(( $layoutName == 'jobappliedresume')){ // only cancel
			JToolBarHelper :: cancel();
		}elseif(( $layoutName == 'conf')  || ($layoutName == 'emailtemplate')){
				JToolBarHelper :: save();
		}elseif( $layoutName == 'users') {
			JToolBarHelper :: editListX();
		}else{
				if($layoutName != 'empapps')
					JToolBarHelper :: addNewX();

			JToolBarHelper :: editListX();
			JToolBarHelper :: deleteList();
		JToolBarHelper :: cancel();
		}
		JToolBarHelper :: spacer(10);
//		JToolBarHelper :: preferences("com_jsjobs",'400');

		jimport('joomla.html.pagination');
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		//$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', 5, 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		if (isset($_SESSION['js_cur_page'])) $cur_page = $_SESSION['js_cur_page']; else $cur_page = null;

		if($layoutName == 'controlpanel'){								//control panel
			JToolBarHelper :: title('JS Jobs');
			$result =  $model->getAllCategories($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'categories'){								//categories
			JToolBarHelper :: title(JText::_('CATEGORIES'));
			$result =  $model->getAllCategories($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'jobtypes'){								//job types
			JToolBarHelper :: title(JText::_('JS_JOB_TYPES'));
			$result =  $model->getAllJobTypes($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'jobstatus'){								//job status
			JToolBarHelper :: title(JText::_('JS_JOB_STATUS'));
			$result =  $model->getAllJobStatus($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'shifts'){								//shifts
			JToolBarHelper :: title(JText::_('JS_SHIFTS'));
			$result =  $model->getAllShifts($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'highesteducations'){								//highest educations
			JToolBarHelper :: title(JText::_('JS_HIGHEST_EDUCATIONS'));
			$result =  $model->getAllHighestEducations($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'companies'){				//companies
			JToolBarHelper :: title(JText::_('JS_COMPANIES'));
			if ($cur_page != 'companies'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'companies';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $option.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );

			$result =  $model->getAllCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'companiesqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_COMPANIES_QUEUE'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $option.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );

			$result =  $model->getAllUnapprovedCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'jobs'){								//jobs
			JToolBarHelper :: title(JText::_('JS_JOBS'));
			$form = 'com_jsjobs.jobs.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobstatus = $mainframe->getUserStateFromRequest( $form.'searchjobstatus', 'searchjobstatus',	'',	'string' );
//echo '<br> cat '.$searchjobcategory;
			$result =  $model->getAllJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'jobqueue'){										// job queue
			JToolBarHelper :: title(JText::_('JS_JOBS_APPROVAL_QUEUE'));
			$form = 'com_jsjobs.jobqueue.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobstatus = $mainframe->getUserStateFromRequest( $form.'searchjobstatus', 'searchjobstatus',	'',	'string' );
			$result =  $model->getAllUnapprovedJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'appliedresumes'){								//applied resumes
			JToolBarHelper :: title(JText::_('JS_APPLIED_RESUME'));
			$form = 'com_jsjobs.appliedresumes.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobstatus = $mainframe->getUserStateFromRequest( $form.'searchjobstatus', 'searchjobstatus',	'',	'string' );

			$result =  $model->getAppliedResume($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'jobappliedresume'){								//job applied resume
			JToolBarHelper :: title(JText::_('JS_APPLIED_RESUME'));
			//$jobid = $_GET['oi'];
			$jobid = JRequest::getVar( 'oi');
			$form = 'com_jsjobs.jobappliedresume.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );

			$result =  $model->getJobAppliedResume($jobid, $searchname, $searchjobtype, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
			$this->assignRef('oi', $jobid);
		}elseif($layoutName == 'empapps'){								//employment applications
			JToolBarHelper :: title(JText::_('JS_RESUME'));
			$form = 'com_jsjobs.empapps.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobsalaryrange = $mainframe->getUserStateFromRequest( $form.'searchjobsalaryrange', 'searchjobsalaryrange',	'',	'string' );
			$result =  $model->getAllEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'appqueue'){		//app queue
			JToolBarHelper :: title(JText::_('JS_RESUME_APPROVAL_QUEUE'));
			$form = 'com_jsjobs.appqueue.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobsalaryrange = $mainframe->getUserStateFromRequest( $form.'searchjobsalaryrange', 'searchjobsalaryrange',	'',	'string' );
			$result =  $model->getAllUnapprovedEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'salaryrange'){									// salary range
			JToolBarHelper :: title(JText::_('JS_SALARY_RANGE'));
			$result =  $model->getAllSalaryRange($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'updates'){										// roles
			JToolBarHelper :: title(JText::_('JS_ACTIVATE_UPDATES'));
			$configur =  $model->getConfigur();	
			$this->assignRef('configur', $configur);
		}elseif($layoutName == 'roles'){										// roles
			JToolBarHelper :: title(JText::_('JS_ROLES'));
			$result =  $model->getAllRoles($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'users'){										// users
			JToolBarHelper :: title(JText::_('USERS'));
			$form = 'com_jsjobs.users.list.';
			$searchname	= $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );
			$result =  $model->getAllUsers($searchname, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'userfields'){										// user field
			JToolBarHelper :: title(JText::_('JS_USER_FIELDS'));
			//$searchname	= $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );
			$fieldfor = $_GET['ff'];
			if ($fieldfor) $_SESSION['ffusr'] = $fieldfor; else $fieldfor = $_SESSION['ffusr'];
			$result =  $model->getUserFields($fieldfor, $limitstart, $limit);	// 1 for company
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'info'){
			JToolBarHelper :: title(JText::_('Information'));
		}elseif($layoutName == 'conf'){											// configurations
			JToolBarHelper :: title(JText::_('CONFIG'));
			$result =  $model->getConfigurationsForForm();	
			$this->assignRef('lists', $result[1]);
		}elseif($layoutName == 'fieldsordering'){										// field ordering
			JToolBarHelper :: title(JText::_('JS_FIELDS'));
			$fieldfor = $_GET['ff'];
			if ($fieldfor) $_SESSION['fford'] = $fieldfor; else $fieldfor = $_SESSION['fford'];
			$result =  $model->getFieldsOrdering($fieldfor, $limitstart, $limit);	// 1 for company
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'emailtemplate'){										// email template
			$templatefor = $_GET['tf'];
			switch($templatefor){
				case 'cm-ap' : $text = JText::_('JS_COMPANY_APPROVAL'); break;
				case 'cm-rj' : $text = JText::_('JS_COMPANY_REJECTING'); break;
				case 'ob-ap' : $text = JText::_('JS_JOB_APPROVAL'); break;
				case 'ob-rj' : $text = JText::_('JS_JOB_REJECTING'); break;
				case 'rm-ap' : $text = JText::_('JS_RESUME_APPROVAL'); break;
				case 'rm-rj' : $text = JText::_('JS_RESUME_REJECTING'); break;
				case 'ba-ja' : $text = JText::_('JS_JOB_APPLY'); break;
			}
			JToolBarHelper :: title(JText::_('JS_EMAIL_TEMPLATES').' <small><small>['.$text.'] </small></small>');
			$template =  $model->getTemplate($templatefor);	
			$this->assignRef('template', $template);
		}elseif($layoutName == 'countries'){										// countries
			JToolBarHelper :: title(JText::_('JS_COUNTRIES'));
			if ($cur_page != 'countries'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'countries';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$result =  $model->getAllCountries($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'states'){										// states
			$countrycode = $_GET['ct'];
			$_SESSION['js_countrycode'] = $countrycode;
			JToolBarHelper :: title(JText::_('JS_STATES'));
			if ($cur_page != 'states'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'states';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$result =  $model->getAllCountryStates($countrycode, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'counties'){										// counties
			$statecode = $_GET['sd'];
			$_SESSION['js_statecode'] = $statecode;
			JToolBarHelper :: title(JText::_('JS_COUNTIES'));
			if ($cur_page != 'counties'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'counties';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$result =  $model->getAllStateCounties($statecode, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'cities'){										// cities
			$countycode = $_GET['co'];
			$_SESSION['js_countycode'] = $countycode;
			JToolBarHelper :: title(JText::_('JS_CITIES'));
			if ($cur_page != 'cities'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'cities';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$result =  $model->getAllCountyCities($countycode, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'loadaddressdata'){										// load address data
			JToolBarHelper :: title(JText::_('JS_LOAD_ADDRESS_DATA'));
			$error = 0;
			if (isset($_GET['er'])) $error = $_GET['er'];
			$this->assignRef('error', $error);
		}else{
			$items = & $this->get('Data');
			JToolBarHelper :: title(JText::_('JS_JOBS'));
		}
		$this->assignRef('items', $items);
		$this->assignRef('config', $config);
		
		$this->assignRef('pagination',	$pagination);

		parent :: display($tpl);
	}
}
?>
