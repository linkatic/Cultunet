<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin/views/application/view.html.php
 ^ 
 * Description: View class for single record in the admin
 ^ 
 * History:		NONE
 * 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JSJobsViewApplication extends JView
{
	function display($tpl = null)
	{
		$model		= &$this->getModel();
		
		$msg = JRequest :: getVar('msg');
		$cur_layout = $_SESSION['cur_layout'];
		$isNew = true;
		$user	=& JFactory::getUser();
		$uid=$user->id;
		// get configurations
		$config = Array();
		if (isset($_SESSION['jsjobconfig'])) $config = $_SESSION['jsjobconfig']; else $config=null;
		//$config = Array();
		if (sizeof($config) == 0){
			$results =  $model->getConfig();	
		//	if ($results){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
				$_SESSION['jsjobconfig'] = $config;	
		//	}
		}

		$theme['title'] = 'jppagetitle';
		$theme['heading'] = 'pageheadline';
		$theme['sectionheading'] = 'sectionheadline';
		$theme['sortlinks'] = 'sortlnks';
		$theme['odd'] = 'odd';
		$theme['even'] = 'even';

		if ($cur_layout == 'categories'){										// categories
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$application =  $model->getCategorybyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_CATEGORY') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif ($cur_layout == 'jobtypes'){										// jobtypes
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$application =  $model->getJobTypebyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_JOB_TYPE') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif ($cur_layout == 'jobstatus'){										// job status
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$application =  $model->getJobStatusbyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_JOB_STATUS') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif ($cur_layout == 'shifts'){										// shifts
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$application =  $model->getShiftbyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_SHIFT') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif ($cur_layout == 'highesteducations'){										// highest educations
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$application =  $model->getHighestEducationbyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_HIGHEST_EDUCATION') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif (($cur_layout == 'companies') || ($cur_layout == 'companiesqueue')){		// companies
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else	$c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$result =  $model->getCompanybyId($c_id);	
			$this->assignRef('company', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('uid', $uid);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_COMPANY') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif (($cur_layout == 'jobs') || ($cur_layout == 'jobqueue')){		// jobs
			if (isset($_GET['cid'][0]))
				$c_id= $_GET['cid'][0];	
			else
				$c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			
			//echo 'c_id: '.$c_id.' <br/>uid: '.$uid;die();
			
			$result =  $model->getJobbyId($c_id, $uid);	
			$this->assignRef('job', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_JOB') . ': <small><small>[ ' . $text . ' ]</small></small>');

		}elseif (($cur_layout == 'empapps') || ($cur_layout == 'appqueue')){			//resume
			if (isset($_GET['cid'][0]))
				$c_id= $_GET['cid'][0];	
			else
				$c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$result =  $model->getEmpAppbyId($c_id);	
//			$result =  $model->getEmpApplicationbyuid($uid);	
	//		$resumelists =  $this->get('EmpOptions');
			$this->assignRef('resume', $result[0]);
			$this->assignRef('fieldsordering', $result[3]);
			$resumelists =  $model->getEmpOptions();	
			$this->assignRef('resumelists', $resumelists);
			//$this->assignRef('empoptions', $empoptions);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_RESUME') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif(($cur_layout == 'jobappliedresume') || ($cur_layout == 'view_resume')){										// view resume
			//	echo 'adfasdf';
				$resumeid = $_GET['rd'];
				$jobid = $_GET['oi'];
				$result =  $model->getResumeViewbyId($resumeid);	
				$this->assignRef('resume', $result[0]);
				$this->assignRef('resume2', $result[1]);
				$this->assignRef('resume3', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
				$this->assignRef('jobid', $jobid);
			JToolBarHelper :: title(JText :: _('JS_VIEW_RESUME') );
			$isNew = false;
		}elseif ($cur_layout == 'salaryrange'){							// salary range
			if (isset($_GET['cid'][0]))
				$c_id= $_GET['cid'][0];	
			else
				$c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$application =  $model->getSalaryRangebyId($c_id);
			// get configurations
			$config = Array();
			$results =  $model->getConfig();	
			if ($results){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
			}
			$this->assignRef('config', $config);
			
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_SALARY_RANGE') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif ($cur_layout == 'roles'){							// roles
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$result =  $model->getRolebyId($c_id);
			$this->assignRef('role', $result[0]);
			$this->assignRef('lists', $result[1]);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_ROLE') . ': <small><small>[ ' . $text . ' ]</small></small>');
		}elseif ($cur_layout == 'users'){							// users - change role
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$result =  $model->getChangeRolebyId($c_id);
			$this->assignRef('role', $result[0]);
			$this->assignRef('lists', $result[1]);
			JToolBarHelper :: title(JText :: _('JS_CHANGE_ROLE'));
		}elseif ($cur_layout == 'userfields'){						// user fields
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$result =  $model->getUserFieldbyId($c_id);
			if (isset($_GET['ff'])) $fieldfor = $_GET['ff']; else $fieldfor = '';
			if ($fieldfor) $_SESSION['ffusr'] = $fieldfor; else $fieldfor = $_SESSION['ffusr'];
			$this->assignRef('userfield', $result[0]);
			$this->assignRef('fieldvalues', $result[1]);
			$this->assignRef('fieldfor', $fieldfor);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
		}elseif ($cur_layout == 'countries'){										// countries
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			$country =  $model->getCountrybyId($c_id);	
			if ( isset($country->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_COUNTRY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('country', $country);
		}elseif ($cur_layout == 'states'){										// states
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; else $countrycode=null;
			$state =  $model->getStatebyId($c_id);	
			if ( isset($state->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_STATE') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('state', $state);
			$this->assignRef('countrycode', $countrycode);
			
		}elseif ($cur_layout == 'counties'){										// counties
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; else $countrycode=null;
			if (isset($_SESSION['js_statecode'])) $statecode = $_SESSION['js_statecode']; else $statecode=null;
			$county =  $model->getCountybyId($c_id);	
			if ( isset($county->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_COUNTY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('county', $county);
			$this->assignRef('countrycode', $countrycode);
			$this->assignRef('statecode', $statecode);
		}elseif ($cur_layout == 'cities'){										// cities
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; else $countrycode=null;
			if (isset($_SESSION['js_statecode'])) $statecode = $_SESSION['js_statecode']; else $statecode=null;
			if (isset($_SESSION['js_countycode'])) $countycode = $_SESSION['js_countycode']; else $countycode=null;
			$city =  $model->getCitybyId($c_id);	
			if ( isset($city->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_CITY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('city', $city);
			$this->assignRef('countrycode', $countrycode);
			$this->assignRef('statecode', $statecode);
			$this->assignRef('countycode', $countycode);
		}//else
//			$application = & $this->get('Application');
			
		//$options = & $this->get('Options');
		if ($cur_layout == 'info')
			JToolBarHelper :: title(JText :: _('Job Posting and Employment Application') );
		elseif ($cur_layout == 'userfields')
			JToolBarHelper :: title(JText :: _('JS_USER_FIELD') . ': <small><small>[ ' . $text . ' ]</small></small>');
		if (($cur_layout != 'jobappliedresume') && ($cur_layout != 'view_resume')) JToolBarHelper :: save();
		if ($isNew)
		{
			JToolBarHelper :: cancel();
		}
		else
		{
			JToolBarHelper :: cancel('cancel', 'Close');

		}

		$this->assignRef('config', $config);
		$this->assignRef('application', $application);
		$this->assignRef('theme', $theme);
		$this->assignRef('options', $options);
		$this->assignRef('uid', $uid);
		$this->assignRef('msg', $msg);
		
		parent :: display($tpl);
	}

}
?>
