<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	models/jsjobs.php
 ^ 
 * Description: Model class for jsjobs data
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.html.html');

$option = JRequest :: getVar('option', 'com_jsjobs');


class JSJobsModelJsjobs extends JModel
{
	var $_id = null;
	var $_uid = null;
	var $_job = null;
	var $_application = null;
	var $_applications = array();
	var $_options = null;
	var $_empoptions = null;
	var $_searchoptions = null;
	var $_config = null;
	var $_jobtype = null;
	var $_jobstatus = null;
	var $_heighesteducation = null;
	var $_shifts = null;
	var $_defaultcountry = null;
	var $_job_editor = null;
	var $_comp_editor = null;
	var $_filterlists = null;
	
	function __construct()
	{
		parent :: __construct();

		$user	=& JFactory::getUser();
//echo '<br> constractor';
		
	}
	function &getJobSearch($title,$jobcategory,$jobtype,$jobstatus,$jobsalaryrange,$education
							,$shift, $experience, $durration, $startpublishing, $stoppublishing
							,$company,$country,$state,$county,$city,$zipcode,$sortby,$limit,$limitstart)
	{
		$result = array();
		$db = &$this->getDBO();
		// here AlexeyR patch to more flexible SQL request for Title,Company,City fields
		$wherequery = '';
		if ($title != '') $wherequery .= " AND job.title LIKE '%".str_replace("'","",$db->Quote($title))."%'";
		if ($jobcategory != '') $wherequery .= " AND job.jobcategory = ".$jobcategory;
		if ($jobtype != '') $wherequery .= " AND job.jobtype = ".$jobtype;
		if ($jobstatus != '') $wherequery .= " AND job.jobstatus = ".$jobstatus;
		if ($jobsalaryrange != '') $wherequery .= " AND job.jobsalaryrange = ".$jobsalaryrange;
		if ($education != '') $wherequery .= " AND job.heighestfinisheducation = ".$education;
		if ($shift != '') $wherequery .= " AND job.shift = ".$shift;
		if ($experience != '') $wherequery .= " AND job.experience LIKE ".$db->Quote($experience);
		if ($durration != '') $wherequery .= " AND job.durration LIKE ".$db->Quote($durration);
		if ($startpublishing != '') $wherequery .= " AND job.startpublishing >= ".$db->Quote($startpublishing);
		if ($stoppublishing != '') $wherequery .= " AND job.stoppublishing <= ".$db->Quote($stoppublishing);
		if ($company != '') $wherequery .= " AND job.companyid = ".$company;
		if ($country != '') $wherequery .= " AND country2.code LIKE ".$db->Quote($country);
		if ($state != '') $wherequery .= " AND job.state = state.code AND (state.code = ".$db->Quote($state)." OR LOWER(state.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $state, true ).'%', false )." ) ";
		if ($county != '') $wherequery .= " AND job.county = county.code AND (county.code = ".$db->Quote($county)." OR LOWER(county.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $county, true ).'%', false )." ) ";
		if ($city != '') $wherequery .= " AND job.city = city.code AND (city.code = ".$db->Quote($city)." OR LOWER(city.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $city, true ).'%', false )." ) ";
//		if ($county != '') $wherequery .= " AND county.name LIKE ".$db->Quote($county);
//		if ($city != '') $wherequery .= " AND city.name LIKE ".$db->Quote($city);
		if ($zipcode != '') $wherequery .= " AND job.zipcode = ".$db->Quote($zipcode);

		$curdate = date('Y-m-d H:i:s');
		$query = "SELECT count(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job, ".$db->nameQuote('#__js_job_categories')." AS cat ";
			if ($country != '') $query .= " , ".$db->nameQuote('#__js_job_countries')." AS country2 ";
			if ($state != '') $query .= " , ".$db->nameQuote('#__js_job_states')." AS state ";
			if ($county != '') $query .= " , ".$db->nameQuote('#__js_job_counties')." AS county ";
			if ($city != '') $query .= " , ".$db->nameQuote('#__js_job_cities')." AS city ";
			$query .= "	WHERE job.jobcategory = cat.id AND job.status = 1 
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
		$query .= $wherequery; 
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		$query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle 
				, salary.rangestart, salary.rangeend, country.name AS countryname
				, company.name AS companyname, company.url
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				, ".$db->nameQuote('#__js_job_categories')." AS cat "; 
			if ($country != '') $query .= " , ".$db->nameQuote('#__js_job_countries')." AS country2 ";
			if ($state != '') $query .= " , ".$db->nameQuote('#__js_job_states')." AS state ";
			if ($county != '') $query .= " , ".$db->nameQuote('#__js_job_counties')." AS county ";
			if ($city != '') $query .= " , ".$db->nameQuote('#__js_job_cities')." AS city ";
			$query .= "WHERE job.jobcategory = cat.id AND job.status = 1 AND (job.jobstatus BETWEEN 1 AND 5) 
						AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
		$query .= $wherequery;
		$query .= " ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$result[0] = $this->_applications;
		$result[1] = $total;
		
		return $result;
	}

	function &getResumeSearch($title,$name,$nationality,$gender,$iamavailable,$jobcategory,$jobtype,$jobstatus,$jobsalaryrange,$education
								, $experience,$sortby,$limit,$limitstart)
	{
		$result = array();
		$db = &$this->getDBO();
		// here AlexeyR patch to more flexible SQL request for Title,Company,City fields
		$wherequery = '';
		if ($title != '') $wherequery .= " AND resume.application_title LIKE '%".str_replace("'","",$db->Quote($title))."%'";
		if ($name) {
			$query .= " AND (";
				$query .= " LOWER(app.first_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $name, true ).'%', false );
				$query .= " OR LOWER(app.last_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $name, true ).'%', false );
				$query .= " OR LOWER(app.middle_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $name, true ).'%', false );
			$query .= " )";
		}	
		
		if ($nationality != '') $wherequery .= " AND resume.nationality = ".$nationality;
		if ($gender != '') $wherequery .= " AND resume.gender = ".$gender;
		if ($iamavailable != '') $wherequery .= " AND resume.iamavailable = ".$iamavailable;
		if ($jobcategory != '') $wherequery .= " AND resume.job_category = ".$jobcategory;
		if ($jobtype != '') $wherequery .= " AND resume.jobtype = ".$jobtype;
		if ($jobsalaryrange != '') $wherequery .= " AND resume.jobsalaryrange = ".$jobsalaryrange;
		if ($education != '') $wherequery .= " AND resume.heighestfinisheducation = ".$education;
		if ($experience != '') $wherequery .= " AND resume.total_experience LIKE ".$db->Quote($experience);

		$query = "SELECT count(resume.id) FROM ".$db->nameQuote('#__js_job_resume')." AS resume, ".$db->nameQuote('#__js_job_categories')." AS cat 
				WHERE resume.job_category = cat.id AND resume.status = 1 AND resume.searchable = 1  ";
		$query .= $wherequery; 
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		$query = "SELECT resume.*, cat.cat_title, jobtype.title AS jobtypetitle 
				, salary.rangestart, salary.rangeend
				FROM ".$db->nameQuote('#__js_job_resume')." AS resume
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON resume.jobtype = jobtype.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON resume.jobsalaryrange = salary.id 
				, ".$db->nameQuote('#__js_job_categories')." AS cat "; 
			$query .= "WHERE resume.job_category = cat.id AND resume.status = 1 AND resume.searchable = 1";
		$query .= $wherequery;
		$query .= " ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		//$db->setQuery($query);
		$db->setQuery($query, $limitstart, $limit);
		//$this->_applications = $db->loadObjectList();

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		
		return $result;
	}

	function &getMyCompanies($u_id,$limit,$limitstart)
	{
		$result = array();
		$db = &$this->getDBO();
		
		if (is_numeric($u_id) == false) return false;
		if (($u_id == 0) || ($u_id == '')) return false;
		$query = "SELECT count(company.id) 
		FROM ".$db->nameQuote('#__js_job_companies')." AS company
		WHERE company.uid = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		$query = "SELECT company.*, cat.cat_title
			FROM ".$db->nameQuote('#__js_job_companies')." AS company
			JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON company.category = cat.id
		WHERE company.uid = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
//		$this->_applications = $db->loadObjectList();

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		
		return $result;
	}

	function &getMyJobs($u_id,$sortby,$limit,$limitstart)
	{
		$result = array();
		$db = &$this->getDBO();

		if (is_numeric($u_id) == false) return false;
		if (($u_id == 0) || ($u_id == '')) return false;
		$query = "SELECT count(job.id) 
		FROM ".$db->nameQuote('#__js_job_jobs')." AS job
		WHERE job.uid = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		$query = "SELECT job.*, cat.cat_title 
				, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
				, company.name AS companyname, company.url
				, salary.rangestart, salary.rangeend, country.name AS countryname
		FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				, ".$db->nameQuote('#__js_job_categories')." AS cat 
		WHERE job.jobcategory = cat.id AND job.uid = ".$u_id." ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$result[0] = $this->_applications;
		$result[1] = $total;
		
		return $result;
	}

	function &getJobforForm($job_id, $uid) 
	{
		$db = &$this->getDBO();
		if (is_numeric($uid) == false) return false;
		if (($job_id != '') && ($job_id != 0)){
			if (is_numeric($job_id) == false) return false;
			$query = "SELECT job.*, cat.cat_title, salary.rangestart, salary.rangeend
			FROM ".$db->nameQuote('#__js_job_jobs')." AS job 
			JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id 
			LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
			WHERE job.id = ".$job_id;
			//echo $query;
			$db->setQuery($query);
			$this->_job = $db->loadObject();
		}
		$countries = $this->getCountries('');

		if ( isset($this->_job) ){
			$states = $this->getStates($this->_job->country, '');
			$counties = $this->getCounties($this->_job->state, '');
			$cities = $this->getCities($this->_job->county, '');
			$lists['companies'] = JHTML::_('select.genericList', $this->getCompanies($uid), 'companyid', 'class="inputbox required" '. '', 'value', 'text', $this->_job->companyid);
			$lists['jobcategory'] = JHTML::_('select.genericList', $this->getCategories(''), 'jobcategory', 'class="inputbox" '. '', 'value', 'text', $this->_job->jobcategory);
			$lists['jobtype'] = JHTML::_('select.genericList', $this->getJobType(''), 'jobtype', 'class="inputbox" '. '', 'value', 'text', $this->_job->jobtype);
			$lists['jobstatus'] = JHTML::_('select.genericList', $this->getJobStatus(''), 'jobstatus', 'class="inputbox required" '. '', 'value', 'text', $this->_job->jobstatus);
			$lists['heighesteducation'] = JHTML::_('select.genericList', $this->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', $this->_job->heighestfinisheducation);
			$lists['shift'] = JHTML::_('select.genericList', $this->getShift(''), 'shift', 'class="inputbox" '. '', 'value', 'text', $this->_job->shift);
			$lists['jobsalaryrange'] = JHTML::_('select.genericList', $this->getJobSalaryRange(''), 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->_job->jobsalaryrange);
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_job->country);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', $this->_job->state);
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', $this->_job->county);
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', $this->_job->city);
		}else{
			if(! isset($this->_config)){ $this->getConfig();}	
			if(isset($this->_defaultcountry))$states = $this->getStates($this->_defaultcountry, '');
			$lists['companies'] = JHTML::_('select.genericList', $this->getCompanies($uid), 'companyid', 'class="inputbox required" '. '', 'value', 'text', '');
			$lists['jobcategory'] = JHTML::_('select.genericList', $this->getCategories(''), 'jobcategory', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['jobtype'] = JHTML::_('select.genericList', $this->getJobType(''), 'jobtype', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['jobstatus'] = JHTML::_('select.genericList', $this->getJobStatus(''), 'jobstatus', 'class="inputbox required" '. '', 'value', 'text', '');
			$lists['heighesteducation'] = JHTML::_('select.genericList', $this->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['shift'] = JHTML::_('select.genericList', $this->getShift(''), 'shift', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['jobsalaryrange'] = JHTML::_('select.genericList', $this->getJobSalaryRange(''), 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_defaultcountry);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', '');
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', '');
		}
		
		
		$result[0] = $this->_job;
		$result[1] = $lists;
		$result[2] = $this->getUserFields(2, $job_id); // job fields , ref id
		$result[3] = $this->getFieldsOrdering(2); // job fields
		if ($job_id) // not new
			$result[4] = 1;
		else // new
			$result[4] = $this->canAddNewJob($uid); // job fields
			
		$user =& JFactory::getUser();
		//Nombre de la empresa logada
		$query = "SELECT *
			FROM ".$db->nameQuote('#__js_job_companies')." 
			WHERE uid = ".$user->id;
		//echo $query;
		$db->setQuery($query);
		$company = $db->loadObject();
		$empresa = array();
		$empresa[id] = $company->id;
		$empresa[name] = $company->name;
		$result[5]=$empresa;
			
		return $result;
	}

	function canAddNewJob($uid) 
	{
		$db = &$this->getDBO();
		if (($uid == 0) || ($uid == '')) return false;
		$query = "SELECT COUNT(jobs.id) AS totaljobs, role.jobs
		FROM ".$db->nameQuote('#__js_job_roles')." AS role
		JOIN ".$db->nameQuote('#__js_job_userroles')." AS userrole ON userrole.role = role.id
		LEFT JOIN ".$db->nameQuote('#__js_job_jobs')." AS jobs ON userrole.uid = jobs.uid 
		WHERE userrole.uid = ".$uid." GROUP BY role.jobs";
		//echo $query;
		$db->setQuery($query);
		$job = $db->loadObject();
		if ($job){
			if ($job->jobs == -1) return 1;
			else{
					if ($job->totaljobs < $job->jobs ) return 1;
					else return 0;
				}
		}
		return 0;
	}

	function canAddNewCompany($uid) 
	{
		$db = &$this->getDBO();
		if (($uid == 0) || ($uid == '')) return false;
		$query = "SELECT COUNT(companies.id) AS totalcompanies, role.companies
		FROM ".$db->nameQuote('#__js_job_roles')." AS role
		JOIN ".$db->nameQuote('#__js_job_userroles')." AS userrole ON userrole.role = role.id
		LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS companies ON userrole.uid = companies.uid 
		WHERE userrole.uid = ".$uid." GROUP BY role.companies";
		//echo $query;
		$db->setQuery($query);
		$company = $db->loadObject();
		if ($company){
			if ($company->companies == -1) return 1;
			else{
					if ($company->totalcompanies < $company->companies ) return 1;
					else return 0;
				}
		}
		return 0;
	}

	function canAddNewResume($uid) 
	{
		$db = &$this->getDBO();
		if (($uid == 0) || ($uid == '')) return false;
		$query = "SELECT COUNT(resume.id) AS totalresumes, role.resumes
		FROM ".$db->nameQuote('#__js_job_roles')." AS role
		JOIN ".$db->nameQuote('#__js_job_userroles')." AS userrole ON userrole.role = role.id
		LEFT JOIN ".$db->nameQuote('#__js_job_resume')." AS resume ON userrole.uid = resume.uid  
		WHERE userrole.uid = ".$uid." GROUP BY role.resumes ";
		//echo $query;
		$db->setQuery($query);
		$resume = $db->loadObject();
		if ($resume){
			if ($resume->resumes == -1) return 1;
			else{
					if ($resume->totalresumes < $resume->resumes ) return 1;
					else return 0;
				}
		}
		return 0;
	}

	function canAddNewCoverLetter($uid) 
	{
		$db = &$this->getDBO();
		if (($uid == 0) || ($uid == '')) return false;
		$query = "SELECT COUNT(letter.id) AS totalletters, role.coverletters
		FROM ".$db->nameQuote('#__js_job_roles')." AS role
		JOIN ".$db->nameQuote('#__js_job_userroles')." AS userrole ON userrole.role = role.id
		LEFT JOIN ".$db->nameQuote('#__js_job_coverletters')." AS letter ON userrole.uid = letter.uid 
		WHERE userrole.uid = ".$uid." GROUP BY role.coverletters";
		//echo $query;
		$db->setQuery($query);
		$letter = $db->loadObject();
		if ($letter){
			if ($letter->coverletters == -1) return 1;
			else{
					if ($letter->totalletters < $letter->coverletters) return 1;
					else return 0;
				}
		}
		return 0;
	}

	function &getCompanybyIdforForm($id, $uid) 
	{
		$db = &$this->getDBO();
		if (is_numeric($uid) == false) return false;
		if (($id != '') && ($id != 0)){
			if (is_numeric($id) == false) return false;
			$query = "SELECT company.*
			FROM ".$db->nameQuote('#__js_job_companies')." AS company 
			WHERE company.id = ".$id;
			//echo $query;
			$db->setQuery($query);
			$company = $db->loadObject();
		}
		$countries = $this->getCountries('');

		if ( isset($company) ){
			$states = $this->getStates($company->country, '');
			$counties = $this->getCounties($company->state, '');
			$cities = $this->getCities($company->county, '');
			$lists['jobcategory'] = JHTML::_('select.genericList', $this->getCategories(''), 'category', 'class="inputbox required" '. '', 'value', 'text', $company->category);
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $company->country);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', $company->state);
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', $company->county);
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', $company->city);
		}else{
			if(! isset($this->_config)){ $this->getConfig();}	
			if(isset($this->_defaultcountry))$states = $this->getStates($this->_defaultcountry, '');
			$lists['jobcategory'] = JHTML::_('select.genericList', $this->getCategories(''), 'category', 'class="inputbox required" '. '', 'value', 'text', '');
			$lists['companies'] = JHTML::_('select.genericList', $this->getCompanies($uid), 'company', 'class="inputbox required" '. '', 'value', 'text', '');
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_defaultcountry);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', '');
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', '');
		}
		if (isset($company)) $result[0] = $company; else $result[0] = null;
		$result[1] = $lists;
//		$result[2] = $this->getUserFields(1, $company->id); // company fields, id
		$result[2] = $this->getUserFields(1, $id); // company fields, id
		$result[3] = $this->getFieldsOrdering(1); // company fields
		if ($id) // not new
			$result[4] = 1;
		else // new
			$result[4] = $this->canAddNewCompany($uid); 

		return $result;
	}

	function &getJobCat($cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
						,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
						,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype )
	{
		$db = &$this->getDBO();

		if(! isset($this->_config)){
			$this->getConfig();
		}	
		foreach ($this->_config as $conf){
			if ($conf->configname == 'filter_address_fields_width')
				$address_fields_width = $conf->configvalue;
		}
		$wherequery = '';
		if ($cmbfiltercountry != '') $wherequery .= " AND job.country = ".$db->Quote($cmbfiltercountry);
		elseif ($txtfiltercountry != '') $wherequery .= " AND country.name LIKE ".$db->Quote($txtfiltercountry);
		//elseif ($txtfiltercountry != '') $wherequery .= " AND country.name LIKE ".$db->Quote('%'.$db->getEscaped( $txtfiltercountry, true ).'%', false);
		if ($cmbfilterstate != '') $wherequery .= " AND job.state = ".$db->Quote($cmbfilterstate);
		elseif ($txtfilterstate != '') $wherequery .= " AND state.name LIKE ".$db->Quote($txtfilterstate);
		if ($cmbfiltercounty != '') $wherequery .= " AND job.county = ".$db->Quote($cmbfiltercounty);
		elseif ($txtfiltercounty != '') $wherequery .= " AND county.name LIKE ".$db->Quote($txtfiltercounty);
		if ($cmbfiltercity != '') $wherequery .= " AND job.city = ".$db->Quote($cmbfiltercity);
		elseif ($txtfiltercity != '') $wherequery .= " AND city.name LIKE ".$db->Quote($txtfiltercity);
		
		//if ($filterjobcategory != '') $wherequery .= " AND job.jobcategory = ".$filterjobcategory;
		if ($filterjobtype != '') $wherequery .= " AND job.jobtype = ".$filterjobtype;
		if ($filterjobsalaryrange != '') $wherequery .= " AND job.jobsalaryrange = ".$filterjobsalaryrange;
		if ($filterheighesteducation != '') $wherequery .= " AND job.heighestfinisheducation = ".$filterheighesteducation;

		$curdate = date('Y-m-d H:i:s');
		$inquery =  " (SELECT COUNT(job.id) from ".$db->nameQuote('#__js_job_jobs')."  AS job WHERE cat.id = job.jobcategory AND job.status = 1 AND job.jobstatus BETWEEN 1 AND 5 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
		$inquery .= $wherequery. " ) as catinjobs";

		$query =  "SELECT  DISTINCT cat.id, cat.cat_title, ";
		$query .= $inquery;
		$query .=  " FROM ".$db->nameQuote('#__js_job_categories')." AS cat 
					LEFT JOIN ".$db->nameQuote('#__js_job_jobs')."  AS job ON cat.id = job.jobcategory
					LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
					LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code 
					LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code 
					LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code 
					WHERE cat.isactive = 1 ";
		$query .=  " ORDER BY cat.cat_title ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$this->_applications = $db->loadObjectList();

		$jobtype = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
		$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
		$heighesteducation = $this->getHeighestEducation(JText::_('JS_SELECT_EDUCATION'));

		$jobcategories[] =  array('value' => '','text' => JText::_('JS_ALL_CATEGORIES'));
		$job_categories = $jobcategories;
			
		$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY'));
		$countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
		if($cmbfiltercountry != '') $states = $this->getStates($cmbfiltercountry, JText::_('JS_SELECT_STATE'));
		if ($cmbfilterstate != '') $counties = $this->getCounties($cmbfilterstate, JText::_('JS_SELECT_COUNTY'));
		if ($cmbfiltercounty != '') $cities = $this->getCities($cmbfiltercounty, JText::_('JS_SELECT_CITY'));
		
		
		$filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country','class="inputbox" style="width:'.$address_fields_width.'px;" '.'onChange="filter_dochange(\'filter_state\', this.value)"', 'value', 'text', $cmbfiltercountry);
		if ( isset($states[1]) ) if ($states[1] != '') $filterlists['state'] = JHTML::_('select.genericList', $states, 'cmbfilter_state', 'class="inputbox"  style="width:'.$address_fields_width.'px;"'. 'onChange="filter_dochange(\'county\', this.value)"', 'value', 'text', $cmbfilterstate);
		if ( isset($counties[1]) ) if ($counties[1] != '') $filterlists['county'] = JHTML::_('select.genericList', $counties, 'cmbfilter_county', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. 'onChange="filter_dochange(\'city\', this.value)"', 'value', 'text', $cmbfiltercounty);
		if ( isset($cities[1]) ) if ($cities[1] != '') $filterlists['city'] = JHTML::_('select.genericList', $cities, 'cmbfilter_city', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. '', 'value', 'text', $cmbfiltercity);

		$filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" '. '', 'value', 'text', $filterjobcategory);
		$filterlists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'filter_jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $filterjobsalaryrange);
		$filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" '. '', 'value', 'text', $filterjobtype);
		$filterlists['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'filter_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $filterheighesteducation);

		$filtervalues['state'] = $txtfilterstate;
		$filtervalues['county'] = $txtfiltercounty;
		$filtervalues['city'] = $txtfiltercity;
		
		$result[0] = $this->_applications;
		$result[1] = '';
		$result[2] = $filterlists;
		$result[3] = $filtervalues;
		
		return $result;
	}
	
	function &getUserFields($fieldfor, $id)
	{
		$db = &$this->getDBO();
		//if (isset($id) == false) return false;
		$result;
		//if (is_numeric($id) == false) return $result;
		$field = array();
		$result = array();
		$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfields')." 
					WHERE published = 1 AND fieldfor = ". $fieldfor;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$i = 0;
		foreach ($rows as $row){
			$field[0] = $row;
			if ($id != ""){
				$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfield_data')." WHERE referenceid = ".$id." AND field = ". $row->id;
				$db->setQuery($query);
				$data = $db->loadObject();
				$field[1] = $data;
			}
			if ($row->type == "select"){
				$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfieldvalues')." WHERE field = ". $row->id;
				$db->setQuery($query);
				$values = $db->loadObjectList();
				$field[2] = $values;
			}
			$result[] = $field;
			$i++;
		}
		return $result;
	}
	
	function &getUserFieldsForView($fieldfor, $id)
	{
		$db = &$this->getDBO();
		//if (isset($id) == false) return false;
		$result;
		//if (is_numeric($id) == false) return $result;
		$field = array();
		$result = array();
		$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfields')." 
					WHERE published = 1 AND fieldfor = ". $fieldfor;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$i = 0;
		foreach ($rows as $row){
			$field[0] = $row;
			if ($id != ""){
				$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfield_data')." WHERE referenceid = ".$id." AND field = ". $row->id;
				$db->setQuery($query);
				$data = $db->loadObject();
				$field[1] = $data;
			}
			if ($row->type == "select"){
				$query =  "SELECT  value.* FROM ".$db->nameQuote('#__js_job_userfieldvalues')." value
				JOIN ".$db->nameQuote('#__js_job_userfield_data')." udata ON udata.data = value.id
				WHERE value.field = ". $row->id;
				$db->setQuery($query);
				//echo '<br> sql '.$query;
				$value = $db->loadObject();
				$field[2] = $value;
			}
			$result[] = $field;
			$i++;
		}
		return $result;
	}
	
	function &getFieldsOrdering($fieldfor)
	{
		$db = &$this->getDBO();
		$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_fieldsordering')." 
					WHERE published = 1 AND fieldfor =  ". $fieldfor
					." ORDER BY ordering";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$fields = $db->loadObjectList();
		return $fields;
	}
	
	function &getJobsbyCategory($cat_id,$cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
										,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
										,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype 
										,$sortby,$limit,$limitstart)
	{
		$db = &$this->getDBO();
		$result = array();
		if (is_numeric($cat_id) == false) return false;

		if(! isset($this->_config)){
			$this->getConfig();
		}	
		foreach ($this->_config as $conf){
			if ($conf->configname == 'filter_address_fields_width')
				$address_fields_width = $conf->configvalue;
		}
		$wherequery = '';
		if ($cmbfiltercountry != '') $wherequery .= " AND job.country = ".$db->Quote($cmbfiltercountry);
		elseif ($txtfiltercountry != '') $wherequery .= " AND country.name LIKE ".$db->Quote($txtfiltercountry);
		if ($cmbfilterstate != '') $wherequery .= " AND job.state = ".$db->Quote($cmbfilterstate);
		elseif ($txtfilterstate != '') $wherequery .= " AND state.name LIKE ".$db->Quote($txtfilterstate);
		if ($cmbfiltercounty != '') $wherequery .= " AND job.county = ".$db->Quote($cmbfiltercounty);
		elseif ($txtfiltercounty != '') $wherequery .= " AND county.name LIKE ".$db->Quote($txtfiltercounty);
		if ($cmbfiltercity != '') $wherequery .= " AND job.city = ".$db->Quote($cmbfiltercity);
		elseif ($txtfiltercity != '') $wherequery .= " AND city.name LIKE ".$db->Quote($txtfiltercity);

		//if ($filterjobcategory != '') $wherequery .= " AND job.jobcategory = ".$filterjobcategory;
		if ($filterjobtype != '') $wherequery .= " AND job.jobtype = ".$filterjobtype;
		if ($filterjobsalaryrange != '') $wherequery .= " AND job.jobsalaryrange = ".$filterjobsalaryrange;
		if ($filterheighesteducation != '') $wherequery .= " AND job.heighestfinisheducation = ".$filterheighesteducation;

		$curdate = date('Y-m-d H:i:s');

		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code 
				WHERE job.jobcategory = cat.id AND job.status = 1 AND (job.jobstatus BETWEEN 1 AND 5) AND cat.id = ".$cat_id." 
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
		$query .= $wherequery;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		
		$query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
					, company.id AS companyid, company.name AS companyname, company.url, salary.rangestart, salary.rangeend, country.name AS countryname
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id
				LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code 
				WHERE job.status = 1  AND cat.id = ".$cat_id ." 
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
				
		$query .= $wherequery." ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$jobtype = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
		$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
		$heighesteducation = $this->getHeighestEducation(JText::_('JS_SELECT_EDUCATION'));

		$job_categories = $this->getCategories(JText::_('JS_SELECT_CATEGORY'));
		$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY'));
		$countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
		if($cmbfiltercountry != '') $states = $this->getStates($cmbfiltercountry, JText::_('JS_SELECT_STATE'));
		if ($cmbfilterstate != '') $counties = $this->getCounties($cmbfilterstate, JText::_('JS_SELECT_COUNTY'));
		if ($cmbfiltercounty != '') $cities = $this->getCities($cmbfiltercounty, JText::_('JS_SELECT_CITY'));
		
		
		$filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country','class="inputbox"  style="width:'.$address_fields_width.'px;" '.'onChange="filter_dochange(\'filter_state\', this.value)"', 'value', 'text', $cmbfiltercountry);
		if ( isset($states[1]) ) if ($states[1] != '') $filterlists['state'] = JHTML::_('select.genericList', $states, 'cmbfilter_state', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. 'onChange="filter_dochange(\'county\', this.value)"', 'value', 'text', $cmbfilterstate);
		if ( isset($counties[1]) ) if ($counties[1] != '') $filterlists['county'] = JHTML::_('select.genericList', $counties, 'cmbfilter_county', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. 'onChange="filter_dochange(\'city\', this.value)"', 'value', 'text', $cmbfiltercounty);
		if ( isset($cities[1]) ) if ($cities[1] != '') $filterlists['city'] = JHTML::_('select.genericList', $cities, 'cmbfilter_city', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. '', 'value', 'text', $cmbfiltercity);

		$filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" '. '', 'value', 'text', $filterjobcategory);
		$filterlists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'filter_jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $filterjobsalaryrange);
		$filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" '. '', 'value', 'text', $filterjobtype);
		$filterlists['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'filter_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $filterheighesteducation);

		$filtervalues['state'] = $txtfilterstate;
		$filtervalues['county'] = $txtfiltercounty;
		$filtervalues['city'] = $txtfiltercity;
		
		$result[0] = $this->_applications;
		$result[1] = $total;
		$result[2] = $filterlists;
		$result[3] = $filtervalues;
		
		return $result;
	}

	function &getJobsbyCompany($companyid,$cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
										,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
										,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype 
										,$sortby,$limit,$limitstart)
	{
		$db = &$this->getDBO();
		$result = array();
		if (is_numeric($companyid) == false) return false;

		if(! isset($this->_config)){
			$this->getConfig();
		}	
		foreach ($this->_config as $conf){
			if ($conf->configname == 'filter_address_fields_width')
				$address_fields_width = $conf->configvalue;
		}
		$wherequery = '';
		if ($cmbfiltercountry != '') $wherequery .= " AND job.country = ".$db->Quote($cmbfiltercountry);
		elseif ($txtfiltercountry != '') $wherequery .= " AND country.name LIKE ".$db->Quote($txtfiltercountry);
		if ($cmbfilterstate != '') $wherequery .= " AND job.state = ".$db->Quote($cmbfilterstate);
		elseif ($txtfilterstate != '') $wherequery .= " AND state.name LIKE ".$db->Quote($txtfilterstate);
		if ($cmbfiltercounty != '') $wherequery .= " AND job.county = ".$db->Quote($cmbfiltercounty);
		elseif ($txtfiltercounty != '') $wherequery .= " AND county.name LIKE ".$db->Quote($txtfiltercounty);
		if ($cmbfiltercity != '') $wherequery .= " AND job.city = ".$db->Quote($cmbfiltercity);
		elseif ($txtfiltercity != '') $wherequery .= " AND city.name LIKE ".$db->Quote($txtfiltercity);

		//if ($filterjobcategory != '') $wherequery .= " AND job.jobcategory = ".$filterjobcategory;
		if ($filterjobtype != '') $wherequery .= " AND job.jobtype = ".$filterjobtype;
		if ($filterjobsalaryrange != '') $wherequery .= " AND job.jobsalaryrange = ".$filterjobsalaryrange;
		if ($filterheighesteducation != '') $wherequery .= " AND job.heighestfinisheducation = ".$filterheighesteducation;

		$curdate = date('Y-m-d H:i:s');

		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code 
				WHERE job.jobcategory = cat.id AND job.status = 1  AND job.companyid = ".$companyid ." 
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
	$query .= $wherequery;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		
		$query = "SELECT job.*, cat.cat_title, company.name AS companyname, company.url, jobtype.title AS jobtype, jobstatus.title AS jobstatus
					, salary.rangestart, salary.rangeend, country.name AS countryname
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code 
				WHERE job.status = 1 AND job.companyid = ".$companyid ." 
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
			
		$query .= $wherequery." ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$jobtype = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
		$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
		$heighesteducation = $this->getHeighestEducation(JText::_('JS_SELECT_EDUCATION'));

		$job_categories = $this->getCategories('');
		$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY'));
		$countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
		if($cmbfiltercountry != '') $states = $this->getStates($cmbfiltercountry, JText::_('JS_SELECT_STATE'));
		if ($cmbfilterstate != '') $counties = $this->getCounties($cmbfilterstate, JText::_('JS_SELECT_COUNTY'));
		if ($cmbfiltercounty != '') $cities = $this->getCities($cmbfiltercounty, JText::_('JS_SELECT_CITY'));
		
		
		$filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country','class="inputbox"  style="width:'.$address_fields_width.'px;" '.'onChange="filter_dochange(\'filter_state\', this.value)"', 'value', 'text', $cmbfiltercountry);
		if ( isset($states[1]) ) if ($states[1] != '') $filterlists['state'] = JHTML::_('select.genericList', $states, 'cmbfilter_state', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. 'onChange="filter_dochange(\'county\', this.value)"', 'value', 'text', $cmbfilterstate);
		if ( isset($counties[1]) ) if ($counties[1] != '') $filterlists['county'] = JHTML::_('select.genericList', $counties, 'cmbfilter_county', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. 'onChange="filter_dochange(\'city\', this.value)"', 'value', 'text', $cmbfiltercounty);
		if ( isset($cities[1]) ) if ($cities[1] != '') $filterlists['city'] = JHTML::_('select.genericList', $cities, 'cmbfilter_city', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. '', 'value', 'text', $cmbfiltercity);

		$filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" '. '', 'value', 'text', $filterjobcategory);
		$filterlists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'filter_jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $filterjobsalaryrange);
		$filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" '. '', 'value', 'text', $filterjobtype);
		$filterlists['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'filter_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $filterheighesteducation);

		$filtervalues['state'] = $txtfilterstate;
		$filtervalues['county'] = $txtfiltercounty;
		$filtervalues['city'] = $txtfiltercity;
		
		$result[0] = $this->_applications;
		$result[1] = $total;
		$result[2] = $filterlists;
		$result[3] = $filtervalues;
		
		return $result;
	}

	function &getCompanybyId($companyid)
	{
		$db = &$this->getDBO();
		if (is_numeric($companyid) == false) return false;
		$query = "SELECT company.*, cat.cat_title, country.name AS countryname, state.name AS statename
					, county.name AS countyname, city.name AS cityname
		FROM ".$db->nameQuote('#__js_job_companies')." AS company
		JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON company.category = cat.id
		LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON company.country = country.code
		LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON company.state = state.code
		LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON company.county = county.code
		LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON company.city = city.code
		WHERE  company.id = " . $companyid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$result[0] = $db->loadObject();

		$query = "UPDATE ".$db->nameQuote('#__js_job_companies')." SET hits = hits+1 WHERE id = ".$companyid;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			//return false;
		}
		$result[2] = $this->getUserFieldsForView(1, $companyid); // company fields, id
		$result[3] = $this->getFieldsOrdering(1); // company fields
		return $result;
	}

	function &getListNewestJobs($cmbfiltercountry,$cmbfilterstate,$cmbfiltercounty,$cmbfiltercity
											,$txtfiltercountry,$txtfilterstate,$txtfiltercounty,$txtfiltercity
											,$filterjobsalaryrange,$filterheighesteducation,$filterjobcategory,$filterjobtype
											,$limit,$limitstart){
		$db = &$this->getDBO();
		$result = array();
		if(! isset($this->_config)){
			$this->getConfig();
		}	
		foreach ($this->_config as $conf){
			if ($conf->configname == 'filter_address_fields_width')
				$address_fields_width = $conf->configvalue;
		}
		$wherequery = '';
		if ($cmbfiltercountry != '') $wherequery .= " AND job.country = ".$db->Quote($cmbfiltercountry);
		elseif ($txtfiltercountry != '') $wherequery .= " AND country.name LIKE ".$db->Quote($txtfiltercountry);
		if ($cmbfilterstate != '') $wherequery .= " AND job.state = ".$db->Quote($cmbfilterstate);
		elseif ($txtfilterstate != '') $wherequery .= " AND state.name LIKE ".$db->Quote($txtfilterstate);
		if ($cmbfiltercounty != '') $wherequery .= " AND job.county = ".$db->Quote($cmbfiltercounty);
		elseif ($txtfiltercounty != '') $wherequery .= " AND county.name LIKE ".$db->Quote($txtfiltercounty);
		if ($cmbfiltercity != '') $wherequery .= " AND job.city = ".$db->Quote($cmbfiltercity);
		elseif ($txtfiltercity != '') $wherequery .= " AND city.name LIKE ".$db->Quote($txtfiltercity);
		
		if ($filterjobtype != '') $wherequery .= " AND job.jobtype = ".$filterjobtype;
		if ($filterjobsalaryrange != '') $wherequery .= " AND job.jobsalaryrange = ".$filterjobsalaryrange;
		if ($filterheighesteducation != '') $wherequery .= " AND job.heighestfinisheducation = ".$filterheighesteducation;

		$curdate = date('Y-m-d H:i:s');

		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code 
				WHERE job.jobcategory = cat.id AND job.status = 1 AND (job.jobstatus BETWEEN 1 AND 5) 
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
		$query .= $wherequery;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		$query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
					, company.id AS companyid, company.name AS companyname, company.url, salary.rangestart, salary.rangeend, country.name AS countryname
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id
				LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code 
				WHERE job.status = 1  
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
				
		$query .= $wherequery." ORDER BY  job.startpublishing DESC";
//		$query .= " ORDER BY  job.created desc";
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$jobtype = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
		$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
		$heighesteducation = $this->getHeighestEducation(JText::_('JS_SELECT_EDUCATION'));

		$job_categories = $this->getCategories(JText::_('JS_SELECT_CATEGORY'));
		$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY'));
		$countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
		if($cmbfiltercountry != '') $states = $this->getStates($cmbfiltercountry, JText::_('JS_SELECT_STATE'));
		if ($cmbfilterstate != '') $counties = $this->getCounties($cmbfilterstate, JText::_('JS_SELECT_COUNTY'));
		if ($cmbfiltercounty != '') $cities = $this->getCities($cmbfiltercounty, JText::_('JS_SELECT_CITY'));
		
		
		$filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country','class="inputbox"  style="width:'.$address_fields_width.'px;" '.'onChange="filter_dochange(\'filter_state\', this.value)"', 'value', 'text', $cmbfiltercountry);
		if ( isset($states[1]) ) if ($states[1] != '') $filterlists['state'] = JHTML::_('select.genericList', $states, 'cmbfilter_state', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. 'onChange="filter_dochange(\'county\', this.value)"', 'value', 'text', $cmbfilterstate);
		if ( isset($counties[1]) ) if ($counties[1] != '') $filterlists['county'] = JHTML::_('select.genericList', $counties, 'cmbfilter_county', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. 'onChange="filter_dochange(\'city\', this.value)"', 'value', 'text', $cmbfiltercounty);
		if ( isset($cities[1]) ) if ($cities[1] != '') $filterlists['city'] = JHTML::_('select.genericList', $cities, 'cmbfilter_city', 'class="inputbox"  style="width:'.$address_fields_width.'px;" '. '', 'value', 'text', $cmbfiltercity);

		$filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" '. '', 'value', 'text', $filterjobcategory);
		$filterlists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'filter_jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $filterjobsalaryrange);
		$filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" '. '', 'value', 'text', $filterjobtype);
		$filterlists['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'filter_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $filterheighesteducation);

		$filtervalues['state'] = $txtfilterstate;
		$filtervalues['county'] = $txtfiltercounty;
		$filtervalues['city'] = $txtfiltercity;
		
		$result[0] = $this->_applications;
		$result[1] = $total;
		$result[2] = $filterlists;
		$result[3] = $filtervalues;
		
		return $result;
	}
	
	function &getJobbyId($job_id)
	{
		$db = &$this->getDBO();
		
		if (is_numeric($job_id) == false) return false;
		$query = "SELECT job.*, cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
				, jobstatus.title AS jobstatustitle, shift.title as shifttitle
				, salary.rangestart, salary.rangeend, education.title AS heighesteducationtitle
				, country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
		FROM ".$db->nameQuote('#__js_job_jobs')." AS job
		JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
		JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id
		JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id
		JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id
		LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id
		LEFT JOIN ".$db->nameQuote('#__js_job_heighesteducation')." AS education ON job.heighestfinisheducation = education.id
		LEFT JOIN ".$db->nameQuote('#__js_job_shifts')." AS shift ON job.shift = shift.id
		LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code
		LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code
		LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code
		LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code
		WHERE  job.id = " . $job_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$this->_application = $db->loadObject();

		$query = "UPDATE ".$db->nameQuote('#__js_job_jobs')." SET hits = hits + 1 WHERE id = ".$job_id;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			//return false;
		}

		$result[0] = $this->_application;
		$result[2] = $this->getUserFieldsForView(2, $job_id); // company fields, id
		$result[3] = $this->getFieldsOrdering(2); // company fields
		return $result;
	}
	
	function &getConvocante($job_id)
	{
		$db = &$this->getDBO();
		$convocante = NULL;
		//Obtenemos el convocante si no hay nombre de empresa (para cuando ponen ofertas desde el backoffice)
		$query_extra_data2 = "SELECT data AS convocante 
		FROM ". $db->nameQuote('#__js_job_userfield_data')." AS fdata 
		INNER JOIN ". $db->nameQuote('#__js_job_jobs')." AS job ON job.id = fdata.referenceid 
		WHERE fdata.field=9 AND job.id=".$job_id;
		//echo $query_extra_data;
		
		$db->setQuery($query_extra_data2);
		if ($extra_data2 = $db->loadObjectList()) 
		{
			//print_r($extra_data2);
			$convocante = $extra_data2[0]->convocante;
		}
		
		if ($db->getErrorNum()) {
			echo $db->stderr();
		}
		
		return $convocante;	
	}

	function &getJobbyIdforJobApply($job_id)
	{
		$db = &$this->getDBO();
		
		if (is_numeric($job_id) == false) return false;
		$query = "SELECT job.*, cat.cat_title , company.name as companyname, company.url
				, jobtype.title AS jobtypetitle
				, jobstatus.title AS jobstatustitle, shift.title as shifttitle
				, salary.rangestart, salary.rangeend, education.title AS heighesteducationtitle
				, country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
		FROM ".$db->nameQuote('#__js_job_jobs')." AS job
		JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
		JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id
		JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id
		JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id
		LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id
		LEFT JOIN ".$db->nameQuote('#__js_job_heighesteducation')." AS education ON job.heighestfinisheducation = education.id
		LEFT JOIN ".$db->nameQuote('#__js_job_shifts')." AS shift ON job.shift = shift.id
		LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code
		LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code
		LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code
		LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code
		WHERE  job.id = " . $job_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$this->_application = $db->loadObject();
		$result[0] = $this->_application;
		return $result;
	}

	function &getMyResumes($u_id)
	{
		
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		
		$totalresume = 0;
		$query = "SELECT id, application_title, create_date, status 
		FROM ".$db->nameQuote('#__js_job_resume')."	WHERE status = 1 AND uid = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$resumes = array();
		foreach($rows as $row)	{
			$resumes[] =  array('value' => $row->id, 'text' => $row->application_title);
			$totalresume++;
		}
		
		$myresymes = JHTML::_('select.genericList', $resumes, 'cvid', 'class="inputbox required" '. '', 'value', 'text', '');
		
		$result[0] = $myresymes;
		$result[1] = $totalresume;
		return $result;
	}

	function &getMyAppliedJobs($u_id,$sortby,$limit,$limitstart)
	{
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		$result = array();

		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job, ".$db->nameQuote('#__js_job_jobapply')." AS apply  
		WHERE apply.jobid = job.id AND apply.uid = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 

		$query = "SELECT job.*, cat.cat_title, apply.apply_date, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
				, salary.rangestart, salary.rangeend, country.name AS countryname
				, company.name AS companyname, company.url
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code 
				, ".$db->nameQuote('#__js_job_categories')." AS cat
				, ".$db->nameQuote('#__js_job_jobapply')." AS apply  
		WHERE job.jobcategory = cat.id AND apply.jobid = job.id AND apply.uid = ".$u_id." ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$result[0] = $this->_applications;
		$result[1] = $total;
		
		return $result;
	}

	function &getJobsAppliedResume($u_id,$sortby,$limit,$limitstart)
	{
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		$result = array();

		$query = "SELECT COUNT(job.id)
		FROM ".$db->nameQuote('#__js_job_jobs')." AS job, ".$db->nameQuote('#__js_job_categories')." AS cat 
		WHERE job.jobcategory = cat.id AND job.uid= ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		$query = "SELECT DISTINCT job.*, cat.cat_title , jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
				, (SELECT COUNT(apply.id) FROM ".$db->nameQuote('#__js_job_jobapply')." AS apply WHERE apply.jobid = job.id ) as appinjob
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id
			WHERE job.uid= ".$u_id." ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$result[0] = $this->_applications;
		$result[1] = $total;
		
		return $result;
	}

	function &getJobAppliedResume($u_id,$jobid,$sortby,$limit,$limitstart)
	{
		
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		if (is_numeric($jobid) == false) return false;
		$result = array();

		$query = "SELECT COUNT(job.id)
		FROM ".$db->nameQuote('#__js_job_jobs')." AS job
		, ".$db->nameQuote('#__js_job_jobapply')." AS apply  , ".$db->nameQuote('#__js_job_resume')." AS app  
		WHERE apply.jobid = job.id AND apply.cvid = app.id AND apply.jobid = ".$jobid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		
		$query = "SELECT job.id, cat.cat_title ,apply.apply_date, jobtype.title AS jobtypetitle
				, app.id AS appid, app.first_name, app.last_name, app.email_address, app.jobtype
				, app.jobsalaryrange, salary.rangestart, salary.rangeend
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobapply')." AS apply  ON apply.jobid = job.id 
				JOIN ".$db->nameQuote('#__js_job_resume')." AS app ON apply.cvid = app.id 
				LEFT OUTER JOIN  ".$db->nameQuote('#__js_job_salaryrange')." AS salary	ON	app.jobsalaryrange=salary.id
		WHERE apply.jobid = ".$jobid." ORDER BY  ".$sortby;
		//echo '<br> SQL '.$query;
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$result[0] = $this->_applications;
		$result[1] = $total;
		
		return $result;
	}

	function &getMyResumesbyUid($u_id,$sortby,$limit,$limitstart)
	{
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		$result = array();
		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE uid  = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		
		$query = "SELECT resume.* , category.cat_title, jobtype.title AS jobtypetitle, salary.rangestart, salary.rangeend
					FROM ".$db->nameQuote('#__js_job_resume')." AS resume
					JOIN  ".$db->nameQuote('#__js_job_categories')." AS category ON	resume.job_category = category.id
					JOIN  ".$db->nameQuote('#__js_job_salaryrange')." AS salary	ON	resume.jobsalaryrange = salary.id
					JOIN  ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON resume.jobtype = jobtype.id
					WHERE resume.uid  = ".$u_id ."
					ORDER BY ". $sortby;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		$db->setQuery($query, $limitstart, $limit);
		$this->_applications = $db->loadObjectList();

		$result[0] = $this->_applications;
		$result[1] = $total;
		
		return $result;

	//	$this->backupv104();	
		
	}
	
	function &getUserType($u_id)
	{
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		$query = "SELECT userrole.*, role.rolefor 
			FROM ".$db->nameQuote('#__js_job_userroles')." AS userrole
			JOIN ".$db->nameQuote('#__js_job_roles')." AS role ON userrole.role = role.id
			WHERE  uid  = ".$u_id;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		$result[0] = $db->loadObject();
		
		$usertype = array(
			'0' => array('value' => 1,'text' => JText::_('JS_EMPLOYER')),
			'1' => array('value' => 2,'text' => JText::_('JS_JOB_SEEKER')),);
		
		if (isset($result[0]))
			$lists['usertype'] = JHTML::_('select.genericList', $usertype, 'usertype', 'class="inputbox" '. '', 'value', 'text', $result[0]->rolefor);
		else
			$lists['usertype'] = JHTML::_('select.genericList', $usertype, 'usertype', 'class="inputbox" '. '', 'value', 'text', 1);
		$result[1] = $lists;
		
		return $result;
	}

	function &getResumebyId($id, $u_id)
	{
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		if (($id != '') && ($id != 0)){
			if (is_numeric($id) == false) return false;
			$query = "SELECT * FROM ".$db->nameQuote('#__js_job_resume')." WHERE id = ". $id ." AND uid  = ".$u_id;
			//echo '<br> sql '.$query;
			$db->setQuery($query);
			$this->_application = $db->loadObject();
			$result[0] = $this->_application;
		}
		//$result[2] = $this->getUserFields(3, this->_application->id); // company fields, id  USER FIELD NOT FOR RESUME
		$result[3] = $this->getFieldsOrdering(3); // resume fields
		if ($id) // not new
			$result[4] = 1;
		else // new
			$result[4] = $this->canAddNewResume($u_id); 

		return $result;
	}

	function &getCoverLetterbyId($id, $u_id)
	{
		$db = &$this->getDBO();
		
		if (($id != '') && ($id != 0)){
			if (is_numeric($id) == false) return false;
			$query = "SELECT * FROM ".$db->nameQuote('#__js_job_coverletters')." WHERE id = ". $id;
			//echo '<br> sql '.$query;
			$db->setQuery($query);
			$this->_application = $db->loadObject();
			$result[0] = $this->_application;
		}
		if ($id) // not new
			$result[4] = 1;
		else // new
			if (isset($u_id)) {
				if (is_numeric($u_id) == false) return false;
				$result[4] = $this->canAddNewCoverLetter($u_id); 
			}	
		return $result;
	}

	function &getMyCoverLettersbyUid($u_id,$limit,$limitstart)
	{
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		$result = array();
		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_coverletters')." WHERE uid  = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		
		$query = "SELECT letter.* 
					FROM ".$db->nameQuote('#__js_job_coverletters')." AS letter
					WHERE letter.uid  = ".$u_id ;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		$db->setQuery($query, $limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		
		return $result;
	}
	
	function &getMyJobSearchesbyUid($u_id,$limit,$limitstart)
	{
		if (is_numeric($u_id) == false) return false;
		$db = &$this->getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobsearches')." WHERE uid  = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		
		$query = "SELECT search.* 
					FROM ".$db->nameQuote('#__js_job_jobsearches')." AS search
					WHERE search.uid  = ".$u_id ;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		$db->setQuery($query, $limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		
		return $result;
	}
	
	function &getMyResumeSearchesbyUid($u_id,$limit,$limitstart)
	{
		$db = &$this->getDBO();
		if (is_numeric($u_id) == false) return false;
		$result = array();
		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resumesearches')." WHERE uid  = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		//$limit = $limit ? $limit : 5;
		if ( $total <= $limit ) { 
			$limitstart = 0;
		} 
		
		$query = "SELECT search.* 
					FROM ".$db->nameQuote('#__js_job_resumesearches')." AS search
					WHERE search.uid  = ".$u_id ;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		$db->setQuery($query, $limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		
		return $result;
	}
	
	function &getJobSearchebyId($id)
	{
		$db = &$this->getDBO();
		if (is_numeric($id) == false) return false;
		$query = "SELECT search.* 
					FROM ".$db->nameQuote('#__js_job_jobsearches')." AS search
					WHERE search.id  = ".$id ;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	function &getResumeSearchebyId($id)
	{
		$db = &$this->getDBO();
		if (is_numeric($id) == false) return false;
		$query = "SELECT search.* 
					FROM ".$db->nameQuote('#__js_job_resumesearches')." AS search
					WHERE search.id  = ".$id ;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	
/* this fuction not in use	
	function &getEmpApplicationbyuid($u_id)
	{
		$db = &$this->getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_resume')." WHERE uid  = ".$u_id;
		echo '<br> sql '.$query;
		$db->setQuery($query);
		$this->_application = $db->loadObject();
		$result[0] = $this->_application;
		//$result[2] = $this->getUserFields(3, this->_application->id); // company fields, id  USER FIELD NOT FOR RESUME
		$result[3] = $this->getFieldsOrdering(3); // resume fields
		return $result;
	}
*/	
	function storeNewinJSJobs()
	{
		global $resumedata;
		$row = &$this->getTable('userrole');
		$data = JRequest :: get('post');
		
		if ($data['id']) return false; // can not edit
		
		if(! isset($this->_config)){
			$this->getConfig();
		}	
		foreach ($this->_config as $conf){
			if ($data['usertype'] == 1) { //employer
				if ($conf->configname == 'employerdefaultrole')
					$data['role'] = $conf->configvalue;
			}else { // job seeker
				if ($conf->configname == 'jobseekerdefaultrole')
					$data['role'] = $conf->configvalue;
			}	
		}

		if (!$row->bind($data)){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check())	{
			$this->setError($this->_db->getErrorMsg());
			return 2;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function &getCurUserAllow($u_id)
	{
		
		if (is_numeric($u_id) == false) return false;
		$db = &$this->getDBO();

		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_userallow') ." WHERE uid= ".$db->Quote($u_id);
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$this->_application = $db->loadObject();
		
		return $this->_application;
	}

	function &getUserRole($u_id)
	{
		if (is_numeric($u_id) == false) return false;
		$db = &$this->getDBO();
		if ($u_id != 0){

			$query = "SELECT userrole.*, role.* 
					FROM ".$db->nameQuote('#__js_job_userroles')." AS userrole
					JOIN  ".$db->nameQuote('#__js_job_roles')." AS role ON	userrole.role = role.id
					WHERE userrole.uid  = ".$u_id;
			//echo '<br> SQL '.$query;
			$db->setQuery($query);
			$role = $db->loadObject();
		}	
		return $role;
	}

	function &getUserFilter($u_id)
	{
		if (is_numeric($u_id) == false) return false;
		$db = &$this->getDBO();

		$query = "SELECT filter.*
				FROM ".$db->nameQuote('#__js_job_filters')." AS filter
				WHERE filter.uid  = ".$u_id;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$userfields = $db->loadObject();
		return $userfields;
	}

	function &getConfig()
	{
		if (isset($this->_config ) == false){
			$db = &$this->getDBO();

			$query = "SELECT * FROM ".$db->nameQuote('#__js_job_config');
			//echo '<br> SQL '.$query;
			$db->setQuery($query);
			$this->_config = $db->loadObjectList();
			foreach($this->_config as $conf)
			{
				if ($conf->configname == "defaultcountry")
					$this->_defaultcountry = $conf->configvalue;
				elseif ($conf->configname == "job_editor")
					$this->_job_editor = $conf->configvalue;
				elseif ($conf->configname == "comp_editor")
					$this->_comp_editor = $conf->configvalue;
				elseif ($conf->configname == "actk"){
					if ($conf->configvalue == '0'){
						foreach($this->_config as $confg){
							if ($confg->configname == "offline")
								$confg->configvalue = 1;
						}
					}
				}	
			}
		}
		return $this->_config;
	}



	function storeCompany() //store company
	{
		$row = &$this->getTable('company');
		$data = JRequest :: get('post');
		
		//echo '<br> Store Company';
		if ( !$this->_config )
			$this->getConfig();
		foreach ($this->_config as $conf){
			if ($conf->configname == 'companyautoapprove')
				$data['status'] = $conf->configvalue;
			if ($conf->configname == 'company_logofilezize')
				$logofilesize = $conf->configvalue;
		}
		if ($this->_comp_editor == 1){	
			$data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);
		}
		if($_FILES['logo']['size'] > 0){ // logo
			$uploadfilesize = $_FILES['logo']['size'];
			$uploadfilesize = $uploadfilesize / 1024; //kb
			if($uploadfilesize > $logofilesize){ // logo
				return 5; // file size error	
			}	
		}
		if($_FILES['logo']['size'] > 0){ // logo
			$data['logofilename'] = $_FILES['logo']['name']; // file name
			$data['logoisfile'] = 1; // logo store in file system
		}
		if($data['deletelogo'] == 1){ // delete logo
			$data['logofilename'] = ''; // file name
			$data['logoisfile'] = -1; // no logo
		}

		if($_FILES['smalllogo']['size'] > 0){ //small logo
			$data['smalllogofilename'] = $_FILES['smalllogo']['name']; // file name
			$data['smalllogoisfile'] = 1; // logo store in file system
		}
		if($data['deletesmalllogo'] == 1){ //delete small logo
			$data['smalllogofilename'] = ''; // file name
			$data['smalllogoisfile'] = -1; // no logo
		}

		if($_FILES['aboutcompany']['size'] > 0){ //about company
			$data['aboutcompanyfilename'] = $_FILES['aboutcompany']['name']; // file name
			$data['aboutcompanyisfile'] = 1; // logo store in file system
		}
		if($data['deleteaboutcompany'] == 1){ // delete about company
			$data['aboutcompanyfilename'] = ''; // file name
			$data['aboutcompanyisfile'] = -1; // no logo
		}
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check())	{
			$this->setError($this->_db->getErrorMsg());
			return 2;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$this->storeUserFieldData($data, $row->id);

		$companyid = $row->id;
		if($_FILES['logo']['size'] > 0){ // logo
			$data['logofilename'] = $_FILES['logo']['name']; // file name
			$data['logoisfile'] = 1; // logo store in file system
			$returnvalue = $this->uploadFile($companyid, 1, 0);
		}
		if($data['deletelogo'] == 1){ // delete logo
			$data['logofilename'] = ''; // file name
			$data['logoisfile'] = -1; // no logo
			$returnvalue = $this->uploadFile($companyid, 1, 1);
		}

		if($_FILES['smalllogo']['size'] > 0){ //small logo
			$data['smalllogofilename'] = $_FILES['smalllogo']['name']; // file name
			$data['smalllogoisfile'] = 1; // logo store in file system
			$returnvalue = $this->uploadFile($companyid, 2, 0);
		}
		if($data['deletesmalllogo'] == 1){ //delete small logo
			$data['smalllogofilename'] = ''; // file name
			$data['smalllogoisfile'] = -1; // no logo
			$returnvalue = $this->uploadFile($companyid, 2, 1);
		}

		if($_FILES['aboutcompany']['size'] > 0){ //about company
			$data['aboutcompanyfilename'] = $_FILES['aboutcompany']['name']; // file name
			$data['aboutcompanyisfile'] = 1; // logo store in file system
			$returnvalue = $this->uploadFile($companyid, 3, 0);
		}
		if($data['deleteaboutcompany'] == 1){ // delete about company
			$data['aboutcompanyfilename'] = ''; // file name
			$data['aboutcompanyisfile'] = -1; // no logo
			$returnvalue = $this->uploadFile($companyid, 3, 1);
		}
		
		//if ($returnvalue != 1)
			//return $returnvalue;
			return true;
			
	}

	function deleteCompany($companyid, $uid)
	{
		$row = &$this->getTable('company');
		$data = JRequest :: get('post');
		
		if (is_numeric($companyid) == false) return false;
		$returnvalue = $this->companyCanDelete($companyid,$uid);
		if ($returnvalue == 1 ){
			if (!$row->delete($companyid))	{
				$this->setError($row->getErrorMsg());
				return false;
			}
			$this->deleteUserFieldData($companyid);
		}else return $returnvalue;// company can not delete	

		return true;
	}
	
	function companyCanDelete($companyid, $uid){
		if (is_numeric($uid) == false) return false;
		$db = &$this->getDBO();
		$result = array();

		$query = "SELECT COUNT(company.id) FROM ".$db->nameQuote('#__js_job_companies')." AS company  
					WHERE company.id = ".$companyid." AND company.uid = ".$uid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$comtotal = $db->loadResult();
		
		if ($comtotal > 0){ // this company is same user

				$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job  
						WHERE job.companyid = ".$companyid;
				//echo '<br> SQL '.$query;
				$db->setQuery($query);
				$total = $db->loadResult();
				
				if ($total > 0)
					return 2;
				else
					return 1;
		}else return 3; // 	this company is not of this user		
	
	}
	
	function storeJob() //store job
	{
		$row = &$this->getTable('job');
		$data = JRequest :: get('post');
		
		if (isset($this_config) == false)
			$this->getConfig();
		if ($data['id'] == '') { // only for new job
			foreach ($this->_config as $conf){
				if ($conf->configname == 'jobautoapprove')
					$data['status'] = $conf->configvalue;
			}
		}
		if ($this->_job_editor == 1){	
			$data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$data['qualifications'] = JRequest::getVar('qualifications', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$data['prefferdskills'] = JRequest::getVar('prefferdskills', '', 'post', 'string', JREQUEST_ALLOWRAW);
		}
		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$check_return = $row->check();
		
		if ($check_return != 1)
		{
			$this->setError($this->_db->getErrorMsg());
			return $check_return;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			echo $this->_db->getErrorMsg();
			return false;
		}

		$this->storeUserFieldData($data, $row->id);
		
		return true;
	}

	function storeUserFieldData($data, $refid) //store  user field data
	{
		$row = &$this->getTable('userfielddata');
		for($i = 1; $i <= $data['userfields_total']; $i++){
			$fname = "userfields_".$i;
			$fid = "userfields_".$i."_id";
			$dataid = "userdata_".$i."_id";
			//$fielddata['id'] = "";
			
			$fielddata['id'] = $data[$dataid];
			$fielddata['referenceid'] = $refid;
			$fielddata['field'] = $data[$fid];
			$fielddata['data'] = $data[$fname];
	
			if (!$row->bind($fielddata))	{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			if (!$row->store())	{
				$this->setError($this->_db->getErrorMsg());
				echo $this->_db->getErrorMsg();
				return false;
			}
		}
		return true;
	}

	function &getTypeStatus()
	{
		$db = &$this->getDBO();

		$query = "SELECT jtype.status AS typestatus, shift.status AS shiftstatus
				FROM ".$db->nameQuote('#__js_job_jobtypes')." AS jtype
				, ".$db->nameQuote('#__js_job_shifts')." AS shift ";
//				WHERE jtype.id = 1 AND shift.id = 1";
		//echo '<br> SQL '.$query;
		$result[0] = 'ine';
		$result[1] = 1;
		$db->setQuery($query);
		$conf = $db->loadObject();
		if ($conf->typestatus == 1){
			$result[1] = 0;
		}elseif($conf->shiftstatus == 1){
			$result[1] = 0;
		}

		return $result;
	}

	function deleteUserFieldData($refid) //delete user field data
	{
		$db =& JFactory::getDBO();
		
		$query = "DELETE FROM #__js_job_userfield_data WHERE referenceid = ".$refid;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
	}

	function jobapply()
	{
		$row = &$this->getTable('jobapply');
		$data = JRequest :: get('post');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if ($data['id'] == ''){ // only for new
			$result=$this->jobApplyValidation($data['uid'],$data['jobid']);
			if ($result == true)
			{
				return 3;
			}
		}
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$emailrerurn=$this->sendMail($data['jobid'], $data['uid']);
		return true;
	}

	function storeUserAllow($u_id)
	{
		if (is_numeric($u_id) == false) return false;
		$row = & $this->getTable('userallow');

			$data['uid'] = $u_id;
			if ( !$this->_config )
				$this->getConfig();
			foreach ($this->_config as $conf){
				if ($conf->configname == 'defaultempallow')
					$data['empallow'] = $conf->configvalue;
				if ($conf->configname == 'defaultjoballow')
					$data['joballow'] = $conf->configvalue;
			}

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());
			return 2;
		}
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;

	}

	function jobApplyValidation($u_id, $jobid)
	{
		if (is_numeric($u_id) == false) return false;
		if (is_numeric($jobid) == false) return false;
		$db =& JFactory::getDBO();
		
		$query = "SELECT COUNT(id) FROM #__js_job_jobapply 
		WHERE uid = ".$u_id ." AND jobid = ".$jobid;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		$result = $db->loadResult();
		//echo '<br>r'.$result;
		if ($result == 0)
			return false;
		else
			return true;
			
	}

	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//send mail
	function &sendMail($jobid, $uid)
	{
		$db =& JFactory::getDBO();
		
		$templatefor = 'jobapply-jobapply';
		$query = "SELECT template.* FROM ".$db->nameQuote('#__js_job_emailtemplates') ."AS template	WHERE template.templatefor = ".$db->Quote($templatefor);
		$db->setQuery( $query );
		$template = $db->loadObject();
		$msgSubject = $template->subject;
		$msgBody = $template->body;
		
		$jobquery = "SELECT users.name, users.email, job.title, job.sendemail FROM ".$db->nameQuote('#__users') ."AS users, ".$db->nameQuote('#__js_job_jobs') ." AS job  
		WHERE users.id = job.uid  AND job.id = ".$db->Quote($jobid);
		//echo '<br>sql '.$jobquery;
		$db->setQuery( $jobquery );
		$jobuser = $db->loadObject();
		if ($jobuser->sendemail == 1){
			$userquery = "SELECT name, email FROM ".$db->nameQuote('#__users') ."
			WHERE id = ".$db->Quote($uid);
			//echo '<br>sql '.$userquery;
			$db->setQuery( $userquery );
			$user = $db->loadObject();

			$ApplicantName=$user->name;
			$EmployerEmail=$jobuser->email;
			$EmployerName=$jobuser->name;
			$JobTitle=$jobuser->title;

			$msgSubject = str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgSubject);
			$msgSubject = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
			$msgSubject = str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
			$msgBody = str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgBody);
			$msgBody = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
			$msgBody = str_replace('{JOB_TITLE}', $JobTitle, $msgBody);

			if ( !$this->_config )
				$this->getConfig();
			foreach ($this->_config as $conf){
				if ($conf->configname == 'mailfromname')
					$senderName = $conf->configvalue;
				if ($conf->configname == 'mailfromaddress')
					$senderEmail = $conf->configvalue;
			}

			$message =& JFactory::getMailer();
			$message->addRecipient($EmployerEmail); //to email

			//$message->setSubject("JS Jobs :  $ApplicantName apply for $JobTitle");
			$message->setSubject($msgSubject);
			$siteAddress = JURI::base();
			//$msgBody="Hello  $EmployerName , \n\n Mr/Mrs  $ApplicantName  apply for $JobTitle.\n Login and view detail at $siteAddress  \n\nPlease do not respond to this message. It is automatically generated and is for information purposes only.";
			$message->setBody($msgBody);
			$sender = array( $senderEmail, $senderName );
			$message->setSender($sender);
			$message->IsHTML(true);
			$sent = $message->send();
			return $sent;
			//if ($sent != 1) echo 'Error sending email';
		}
	
	}

	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Employement Application
		// get application for the set id
	function &getEmpApplicationbyid($id) // <<<--- this isn't used
	{
		if (is_numeric($id) == false) return false;
		$db = &$this->getDBO();
		$query = "SELECT app.* , cat.cat_title AS job_category, salary.rangestart, salary.rangeend
				, address_city.name AS address_city , address_county.name AS address_county , address_state.name AS address_state , address_country.name AS address_country 
				, institute_city.name AS institute_city , institute_county.name AS institute_county , institute_state.name AS institute_state , institute_country.name AS institute_country 
				, employer_city.name AS employer_city , employer_county.name AS employer_county , employer_state.name AS employer_state , employer_country.name AS employer_country 
				FROM ".$db->nameQuote('#__js_job_resume')." AS app 
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON app.job_category = cat.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON app.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS address_city ON app.address_city = address_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS address_county ON app.address_county = address_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS address_state ON app.address_state = address_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS address_country ON app.address_country = address_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS institute_city ON app.institute_city = institute_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS institute_county ON app.institute_county = institute_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS institute_state ON app.institute_state = institute_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS institute_country ON app.institute_country = institute_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS employer_city ON app.employer_city = employer_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS employer_county ON app.employer_county = employer_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS employer_state ON app.employer_state = employer_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS employer_country ON app.employer_country = employer_country.code 
				WHERE app.id = ".$db->Quote($id);
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		$this->_application = $db->loadObject();
		return $this->_application;
	}

	function &getResumeViewbyId($id) 
	{
		if (is_numeric($id) == false) return false;
		$db = &$this->getDBO();
		$query = "SELECT app.* , cat.cat_title AS categorytitle, salary.rangestart, salary.rangeend, jobtype.title AS jobtypetitle
				,heighesteducation.title AS heighesteducationtitle
				, nationality_country.name AS nationalitycountry
				, address_city.name AS address_city2 , address_county.name AS address_county2 , address_state.name AS address_state2 , address_country.name AS address_country
				, address1_city.name AS address1_city2 , address1_county.name AS address1_county2 , address1_state.name AS address1_state2 , address1_country.name AS address1_country
				, address2_city.name AS address2_city2 , address2_county.name AS address2_county2 , address2_state.name AS address2_state2 , address2_country.name AS address2_country 
				
				
				FROM ".$db->nameQuote('#__js_job_resume')." AS app 
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON app.job_category = cat.id 
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON app.jobtype = jobtype.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_heighesteducation')." AS heighesteducation ON app.heighestfinisheducation = heighesteducation.id
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS nationality_country ON app.nationality = nationality_country.code
				LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON app.jobsalaryrange = salary.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS address_city ON app.address_city = address_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS address_county ON app.address_county = address_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS address_state ON app.address_state = address_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS address_country ON app.address_country = address_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS address1_city ON app.address1_city = address1_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS address1_county ON app.address1_county = address1_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS address1_state ON app.address1_state = address1_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS address1_country ON app.address1_country = address1_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS address2_city ON app.address2_city = address2_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS address2_county ON app.address2_county = address2_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS address2_state ON app.address2_state = address2_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS address2_country ON app.address2_country = address2_country.code 

				WHERE app.id = ". $id ;
		//echo '<br> sql '.$query;
		$query2 = "SELECT app.id
				
				, institute_city.name AS institute_city2 , institute_county.name AS institute_county2 , institute_state.name AS institute_state2 , institute_country.name AS institute_country 
				, institute1_city.name AS institute1_city2 , institute1_county.name AS institute1_county2 , institute1_state.name AS institute1_state2 , institute1_country.name AS institute1_country 
				, institute2_city.name AS institute2_city2 , institute2_county.name AS institute2_county2 , institute2_state.name AS institute2_state2 , institute2_country.name AS institute2_country 
				, institute3_city.name AS institute3_city2 , institute3_county.name AS institute3_county2 , institute3_state.name AS institute3_state2 , institute3_country.name AS institute3_country 

				, employer_city.name AS employer_city2 , employer_county.name AS employer_county2 , employer_state.name AS employer_state2 , employer_country.name AS employer_country 
				, employer1_city.name AS employer1_city2 , employer1_county.name AS employer1_county2 , employer1_state.name AS employer1_state2 , employer1_country.name AS employer1_country 
				, employer2_city.name AS employer2_city2 , employer2_county.name AS employer2_county2 , employer2_state.name AS employer2_state2 , employer2_country.name AS employer2_country 
				, employer3_city.name AS employer3_city2 , employer3_county.name AS employer3_county2 , employer3_state.name AS employer3_state2 , employer3_country.name AS employer3_country 
				
				FROM ".$db->nameQuote('#__js_job_resume')." AS app 

				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS institute_city ON app.institute_city = institute_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS institute_county ON app.institute_county = institute_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS institute_state ON app.institute_state = institute_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS institute_country ON app.institute_country = institute_country.code 

				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS institute1_city ON app.institute1_city = institute1_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS institute1_county ON app.institute1_county = institute1_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS institute1_state ON app.institute1_state = institute1_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS institute1_country ON app.institute1_country = institute1_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS institute2_city ON app.institute2_city = institute2_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS institute2_county ON app.institute2_county = institute2_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS institute2_state ON app.institute2_state = institute2_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS institute2_country ON app.institute2_country = institute2_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS institute3_city ON app.institute3_city = institute3_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS institute3_county ON app.institute3_county = institute3_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS institute3_state ON app.institute3_state = institute3_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS institute3_country ON app.institute3_country = institute3_country.code 
				
				
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS employer_city ON app.employer_city = employer_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS employer_county ON app.employer_county = employer_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS employer_state ON app.employer_state = employer_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS employer_country ON app.employer_country = employer_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS employer1_city ON app.employer1_city = employer1_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS employer1_county ON app.employer1_county = employer1_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS employer1_state ON app.employer1_state = employer1_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS employer1_country ON app.employer1_country = employer1_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS employer2_city ON app.employer2_city = employer2_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS employer2_county ON app.employer2_county = employer2_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS employer2_state ON app.employer2_state = employer2_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS employer2_country ON app.employer2_country = employer2_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS employer3_city ON app.employer3_city = employer3_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS employer3_county ON app.employer3_county = employer3_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS employer3_state ON app.employer3_state = employer3_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS employer3_country ON app.employer3_country = employer3_country.code 

				WHERE app.id = ". $id ;

		$db->setQuery($query);
		$resume = $db->loadObject();

		$db->setQuery($query2);
		$resume2 = $db->loadObject();

		$query = "UPDATE ".$db->nameQuote('#__js_job_resume')." SET hits = hits + 1 WHERE id = ".$id;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			//return false;
		}

		$result[0] = $resume;
		$result[1] = $resume2;
		$result[2] = $this->getResumeViewbyId3($id);
		$result[3] = $this->getFieldsOrdering(3); // resume fields
		return $result;
	}

	function &getResumeViewbyId3($id) 
	{
		if (is_numeric($id) == false) return false;
		$db = &$this->getDBO();
		$query = "SELECT app.id 
				
				, reference_city.name AS reference_city2 , reference_county.name AS reference_county2 , reference_state.name AS reference_state2 , reference_country.name AS reference_country 
				, reference1_city.name AS reference1_city2 , reference1_county.name AS reference1_county2 , reference1_state.name AS reference1_state2 , reference1_country.name AS reference1_country 
				, reference2_city.name AS reference2_city2 , reference2_county.name AS reference2_county2 , reference2_state.name AS reference2_state2 , reference2_country.name AS reference2_country 
				, reference3_city.name AS reference3_city2 , reference3_county.name AS reference3_county2 , reference3_state.name AS reference3_state2 , reference3_country.name AS reference3_country 

				FROM ".$db->nameQuote('#__js_job_resume')." AS app 
				
				

				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS reference_city ON app.reference_city = reference_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS reference_county ON app.reference_county = reference_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS reference_state ON app.reference_state = reference_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS reference_country ON app.reference_country = reference_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS reference1_city ON app.reference1_city = reference1_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS reference1_county ON app.reference1_county = reference1_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS reference1_state ON app.reference1_state = reference1_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS reference1_country ON app.reference1_country = reference1_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS reference2_city ON app.reference2_city = reference2_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS reference2_county ON app.reference2_county = reference2_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS reference2_state ON app.reference2_state = reference2_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS reference2_country ON app.reference2_country = reference2_country.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS reference3_city ON app.reference3_city = reference3_city.code
				LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS reference3_county ON app.reference3_county = reference3_county.code
				LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS reference3_state ON app.reference3_state = reference3_state.code 
				LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS reference3_country ON app.reference3_country = reference3_country.code 

				WHERE app.id = ". $id ;
		//echo '<br> sql '.$query;
		$db->setQuery($query);
		$resume = $db->loadObject();
		return $resume;
	}

	function storeCoverLetter()
	{
		global $resumedata;
		$row = &$this->getTable('coverletter');
		$data = JRequest :: get('post');


		if (!$row->bind($data)){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check())	{
			$this->setError($this->_db->getErrorMsg());
			return 2;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function storeFilter()
	{
		global $resumedata;
		$user	=& JFactory::getUser();
		$row = &$this->getTable('filter');
		$data = JRequest :: get('post');

		$data['uid'] = $user->id;
		$data['status'] = 1;
		if (!$row->bind($data)){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if ($data['cmbfilter_country'] != '') { $row->country = $data['cmbfilter_country']; $row->country_istext = 0;}
		elseif ($data['txtfilter_country'] != '') { $row->country = $data['txtfilter_country']; $row->country_istext = 1;}
		if ($data['cmbfilter_state'] != '') { $row->state = $data['cmbfilter_state']; $row->state_istext = 0;}
		elseif ($data['txtfilter_state'] != '') { $row->state = $data['txtfilter_state']; $row->state_istext = 1;}
		if ($data['cmbfilter_county'] != '') { $row->county = $data['cmbfilter_county']; $row->county_istext = 0;}
		elseif ($data['txtfilter_county'] != '') { $row->county = $data['txtfilter_county']; $row->county_istext = 1;}
		if ($data['cmbfilter_city'] != '') { $row->city = $data['cmbfilter_city']; $row->city_istext = 0;}
		elseif ($data['txtfilter_city'] != '') { $row->city = $data['txtfilter_city']; $row->city_istext = 1;}

		//echo 'cat '. $data['filter_jobcategory'];
		$row->category = $data['filter_jobcategory'];
		$row->jobtype = $data['filter_jobtype'];
		$row->salaryrange = $data['filter_jobsalaryrange'];
		$row->heighesteducation = $data['filter_heighesteducation'];

		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			echo $this->_db->getErrorMsg();
			return false;
		}
		return true;
	}

	function storeJobSearch($data)
	{
		global $resumedata;
		$row = &$this->getTable('jobsearch');

		if (!$row->bind($data)){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$returnvalue = $this->canAddNewJobSearch($data['uid']);
		if ($returnvalue == 0) return 3; //not allowed save new search
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			echo $this->_db->getErrorMsg();
			return false;
		}
		return true;
	}

	function storeResumeSearch($data)
	{
		global $resumedata;
		$row = &$this->getTable('resumesearch');

		if (!$row->bind($data)){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$returnvalue = $this->canAddNewResumeSearch($data['uid']);
		if ($returnvalue == 0) return 3; //not allowed save new search
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			echo $this->_db->getErrorMsg();
			return false;
		}
		return true;
	}

	function canAddNewJobSearch($uid) 
	{
		if (is_numeric($uid) == false) return false;
		$db = &$this->getDBO();
		$query = "SELECT COUNT(search.id) AS totalsearches, role.savesearchjob
		FROM ".$db->nameQuote('#__js_job_roles')." AS role
		JOIN ".$db->nameQuote('#__js_job_userroles')." AS userrole ON userrole.role = role.id
		LEFT JOIN ".$db->nameQuote('#__js_job_jobsearches')." AS search ON userrole.uid = search.uid 
		WHERE userrole.uid = ".$uid." GROUP BY role.savesearchjob";
		//echo $query;
		$db->setQuery($query);
		$job = $db->loadObject();
		if ($job){
			if ($job->savesearchjob == -1) return 1;
			else{
					if ($job->totalsearch < $job->savesearchjob ) return 1;
					else return 0;
				}
		}
		return 0;
	}

	function canAddNewResumeSearch($uid) 
	{
		if (is_numeric($uid) == false) return false;
		$db = &$this->getDBO();
		$query = "SELECT COUNT(search.id) AS totalsearches, role.savesearchresume
		FROM ".$db->nameQuote('#__js_job_roles')." AS role
		JOIN ".$db->nameQuote('#__js_job_userroles')." AS userrole ON userrole.role = role.id
		LEFT JOIN ".$db->nameQuote('#__js_job_resumesearches')." AS search ON userrole.uid = search.uid 
		WHERE userrole.uid = ".$uid." GROUP BY role.savesearchresume ";
		//echo $query;
		$db->setQuery($query);
		$resume = $db->loadObject();
		if ($resume){
			if ($resume->savesearchresume == -1) return 1;
			else{
					if ($resume->totalsearch < $resume->savesearchresume ) return 1;
					else return 0;
				}
		}
		return 0;
	}

	function deleteUserFilter()
	{
		$row = &$this->getTable('filter');
		$data = JRequest :: get('post');
		
		if (!$row->delete($data['id']))
		{
			$this->setError($row->getErrorMsg());
			return false;
		}

		return true;
	}
	
	function deleteJobSearch($searchid, $uid)
	{
		$db = &$this->getDBO();
		$row = &$this->getTable('jobsearch');

		if (is_numeric($searchid) == false) return false;

		$query = "SELECT COUNT(search.id) FROM ".$db->nameQuote('#__js_job_jobsearches')." AS search  
					WHERE search.id = ".$searchid." AND search.uid = ".$uid;
		echo '<br> SQL '.$query;
		$db->setQuery($query);
		$searchtotal = $db->loadResult();
		
		if ($searchtotal > 0){ // this search is same user
		
			if (!$row->delete($searchid))
			{
				$this->setError($row->getErrorMsg());
				return false;
			}
		}else return 2;	

		return true;
	}
	
	function deleteResumeSearch($searchid, $uid)
	{
		
		$db = &$this->getDBO();
		$row = &$this->getTable('resumesearch');
		if (is_numeric($searchid) == false) return false;

		$query = "SELECT COUNT(search.id) FROM ".$db->nameQuote('#__js_job_resumesearches')." AS search  
					WHERE search.id = ".$searchid." AND search.uid = ".$uid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$searchtotal = $db->loadResult();
		
		if ($searchtotal > 0){ // this search is same user
		
			if (!$row->delete($searchid))
			{
				$this->setError($row->getErrorMsg());
				return false;
			}
		}else return 2;	

		return true;
	}
	
	function deleteCoverLetter($coverletterid, $uid)
	{
		$db = &$this->getDBO();
		$row = &$this->getTable('coverletter');
		if (is_numeric($coverletterid) == false) return false;

		$query = "SELECT COUNT(letter.id) FROM ".$db->nameQuote('#__js_job_coverletters')." AS letter  
					WHERE letter.id = ".$coverletterid." AND letter.uid = ".$uid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0){ // this search is same user
		
			if (!$row->delete($coverletterid))
			{
				$this->setError($row->getErrorMsg());
				return false;
			}
		}else return 2;	

		return true;
	}
	
	function deleteResume($resumeid, $uid)
	{
		$row = &$this->getTable('resume');
		$data = JRequest :: get('post');
		
		if (is_numeric($resumeid) == false) return false;
		$returnvalue = $this->resumeCanDelete($resumeid, $uid);
		if( $returnvalue == 1){
			if (!$row->delete($resumeid))	{
				$this->setError($row->getErrorMsg());
				return false;
			}
		}else return $returnvalue;	

		return true;
	}

	function deleteJob($jobid, $uid)
	{
		$row = &$this->getTable('job');
		$data = JRequest :: get('post');
		
		if (is_numeric($jobid) == false) return false;
		$returnvalue = $this->jobCanDelete($jobid, $uid);
		if( $returnvalue == 1){
			if (!$row->delete($jobid))	{
				$this->setError($row->getErrorMsg());
				return false;
			}
		}else return $returnvalue;	

		return true;
	}

	function jobCanDelete($jobid, $uid){
		if (is_numeric($uid) == false) return false;
		$db = &$this->getDBO();

		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job  
					WHERE job.id = ".$jobid." AND job.uid = ".$uid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$jobtotal = $db->loadResult();
		
		if ($jobtotal > 0){ // this job is same user

			$query = "SELECT COUNT(apply.id) FROM ".$db->nameQuote('#__js_job_jobapply')." AS apply  
						WHERE apply.jobid = ".$jobid;
			//echo '<br> SQL '.$query;
			$db->setQuery($query);
			$total = $db->loadResult();
				
				if ($total > 0)
					return 2;
				else
					return 1;
		}else return 3; // 	this job is not of this user		
		
		
	}

	function resumeCanDelete($resumeid, $uid){
		if (is_numeric($uid) == false) return false;
		$db = &$this->getDBO();

		$query = "SELECT COUNT(resume.id) FROM ".$db->nameQuote('#__js_job_resume')." AS resume  
					WHERE resume.id = ".$resumeid." AND resume.uid = ".$uid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$resumetotal = $db->loadResult();
		
		if ($resumetotal > 0){ // this resume is same user

			$query = "SELECT COUNT(apply.id) FROM ".$db->nameQuote('#__js_job_jobapply')." AS apply  
						WHERE apply.cvid = ".$resumeid;
			//echo '<br> SQL '.$query;
			$db->setQuery($query);
			$total = $db->loadResult();
				
				if ($total > 0)
					return 2;
				else
					return 1;
		}else return 3; // 	this resume is not of this user		
	}

	
	function storeResume()
	{
		global $resumedata;
		$row = &$this->getTable('resume');
		$resumedata = JRequest :: get('post');
		//if ( !$resumedata['id'] ){
			if ( !$this->_config )
				$this->getConfig();
			foreach ($this->_config as $conf){
				if ($conf->configname == 'empautoapprove')
					if ( !$resumedata['id'] ) $resumedata['status'] = $conf->configvalue;
				if ($conf->configname == 'resume_photofilesize')
					$photofilesize = $conf->configvalue;
			}
		//}
		$resumedata['resume'] = JRequest::getVar('resume', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if($_FILES['photo']['size'] > 0){
			$uploadfilesize = $_FILES['photo']['size'];
			$uploadfilesize = $uploadfilesize / 1024; //kb
			if($uploadfilesize > $photofilesize){ // logo
				return 7; // file size error	
			}	
		}		

		if($_FILES['resumefile']['size'] > 0){
			$file_name = $_FILES['resumefile']['name']; // file name
			$resumedata['filename'] = $file_name;
			$resumedata['filecontent'] = '';
		}else {
			if ($resumedata['deleteresumefile'] == 1){
				$resumedata['filename'] = '';
				$resumedata['filecontent'] = '';
			}
		}		
		if($_FILES['photo']['size'] > 0){
			$file_name = $_FILES['photo']['name']; // file name
			$resumedata['photo'] = $file_name;
		}else {
			if ($resumedata['deleteresumefile'] == 1){
				$resumedata['photo'] = '';
			}	
		}
//		if ($returnvalue != 1)
//			return $returnvalue;

		if (!$row->bind($resumedata))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());
			return 2;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$returnvalue = $this->uploadResume($row->id);
		$returnvalue = $this->uploadPhoto($row->id);

		return true;
	}

	function uploadResume($id)
	{
		if (is_numeric($id) == false) return false;
		global $resumedata ;
		$db =& JFactory::getDBO();
		$resumequery = "SELECT * FROM ".$db->nameQuote('#__js_job_resume') ."
		WHERE uid = ".$db->Quote($u_id);
		//echo '<br>sql '.$resumequery;
		$iddir = 'resume_'.$id;
		if($_FILES['resumefile']['size'] > 0){
			$file_name = $_FILES['resumefile']['name']; // file name
			$file_tmp = $_FILES['resumefile']['tmp_name']; // actual location
			$file_size = $_FILES['resumefile']['size']; // file size
			$file_type = $_FILES['resumefile']['type']; // mime type of file determined by php
			$file_error = $_FILES['resumefile']['error']; // any error!. get reason here

			if( !empty($file_tmp)){	// only MS office and text file is accepted.
				$ext = $this->getExtension($file_name);
				if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf") && ($ext != "opt") && ($ext != "rtf"))
					return 6; //file type mistmathc
/*
				if( !(($file_type=="application/msword") || ($file_type=="text/plain")) )
				{
					return 6; //file type mistmathc
				}
*/			}

			$path =JPATH_BASE.'/components/com_jsjobs';
			if (!file_exists($path)){ // creating resume directory
				mkdir($path, 0755);
			}
			$path= $path . '/data';
			if (!file_exists($userpath)){ // create user directory
				mkdir($path, 0755);
			}
			$path= $path . '/jobseeker';
			if (!file_exists($path)){ // create user directory
				mkdir($path, 0755);
			}
			$userpath= $path . '/'.$iddir;
			if (!file_exists($userpath)){ // create user directory
				mkdir($userpath, 0755);
			}
			$userpath= $path . '/'.$iddir.'/resume';
			if (!file_exists($userpath)){ // create user directory
				mkdir($userpath, 0755);
			}
			$files = glob($userpath.'/*.*');
			array_map('unlink', $files);  //delete all file in user directory
			
			move_uploaded_file($file_tmp, $userpath.'/' . $file_name);
			//unlink($file_tmp);
			
//			$resumedata['filename'] = $file_name;
//			$resumedata['filecontent'] = '';
			return 1;
		}else {
			if ($resumedata['deleteresumefile'] == 1){
				$path =JPATH_BASE.'/components/com_jsjobs/data/jobseeker';
				$userpath= $path . '/'.$iddir.'/resume';
				$files = glob($userpath.'/*.*');
				array_map('unlink', $files);
				$resumedata['filename'] = '';
				$resumedata['filecontent'] = '';
			}else{
//				$db->setQuery( $resumequery );
//				$resume = $db->loadObject();
//				if ( isset($resume) ){ // if already file upload then pick the path
//						$resumedata['filename'] = $resume->filename;
//						$resumedata['filecontent'] = '';
//				}
			}
			return 1;
		}

	}


	function uploadPhoto($id)
	{
		if (is_numeric($id) == false) return false;
		global $resumedata ;
		$db =& JFactory::getDBO();
		$resumequery = "SELECT * FROM ".$db->nameQuote('#__js_job_resume') ."
		WHERE uid = ".$db->Quote($u_id);
		//echo '<br>sql '.$resumequery;
		$iddir = 'resume_'.$id;
		if($_FILES['photo']['size'] > 0){
			$file_name = $_FILES['photo']['name']; // file name
			$file_tmp = $_FILES['photo']['tmp_name']; // actual location
			$file_size = $_FILES['photo']['size']; // file size
			$file_type = $_FILES['photo']['type']; // mime type of file determined by php
			$file_error = $_FILES['photo']['error']; // any error!. get reason here

			if( !empty($file_tmp)){	
				$ext = $this->getExtension($file_name);
				if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
					return 6; //file type mistmathc
			}

			$path =JPATH_BASE.'/components/com_jsjobs';
			if (!file_exists($path)){ // creating resume directory
				mkdir($path, 0755);
			}
			$path = $path.'/data';
			if (!file_exists($path)){ // creating resume directory
				mkdir($path, 0755);
			}
			$path = $path.'/jobseeker';
			if (!file_exists($path)){ // creating resume directory
				mkdir($path, 0755);
			}
			$userpath= $path . '/'.$iddir;
			if (!file_exists($userpath)){ // create user directory
				mkdir($userpath, 0755);
			}
			$userpath= $path . '/'.$iddir.'/photo';
			if (!file_exists($userpath)){ // create user directory
				mkdir($userpath, 0755);
			}
			$files = glob($userpath.'/*.*');
			array_map('unlink', $files);  //delete all file in user directory
			
			move_uploaded_file($file_tmp, $userpath.'/' . $file_name);
			//unlink($file_tmp);
			
//			$resumedata['photo'] = $file_name;
			return 1;
		}else {
			if ($resumedata['deleteresumefile'] == 1){
				$path =JPATH_BASE.'/components/com_jsjobs/data/jobseeker';
				$userpath= $path . '/'.$iddir.'/photo';
				$files = glob($userpath.'/*.*');
				array_map('unlink', $files);
				$resumedata['photo'] = '';
			}else{
//				$db->setQuery( $resumequery );
//				$resume = $db->loadObject();
//				if ( isset($resume) ){ // if already file upload then pick the path
//						$resumedata['photo'] = $resume->filename;
//				}
			}
			return 1;
		}

	}


	function uploadFile($id, $action, $isdeletefile)
	{

		if (is_numeric($id) == false) return false;
		$db =& JFactory::getDBO();
		$path =JPATH_BASE.'/components/com_jsjobs';
		$isupload = false;
		$path= $path . '/data';
		if (!file_exists($userpath)){ // create user directory
			mkdir($path, 0755);
		}
		$path= $path . '/employer';
		if (!file_exists($userpath)){ // create user directory
			mkdir($path, 0755);
		}
		if ($action == 1) { //Company logo
			if($_FILES['logo']['size'] > 0){
				$file_name = $_FILES['logo']['name']; // file name
				$file_tmp = $_FILES['logo']['tmp_name']; // actual location
				
				$ext = $this->getExtension($file_name);
				$ext = strtolower($ext);
				if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
					return 6; //file type mistmathc
					
				$userpath= $path . '/comp_'.$id;
				if (!file_exists($userpath)){ // create user directory
					mkdir($userpath, 0755);
				}
				$userpath= $userpath. '/logo';
				if (!file_exists($userpath)){ // create logo directory
					mkdir($userpath, 0755);
				}
				$isupload = true;
			}
		}elseif ($action == 2) { //Company small logo
			if($_FILES['smalllogo']['size'] > 0){
				$file_name = $_FILES['smalllogo']['name']; // file name
				$file_tmp = $_FILES['smalllogo']['tmp_name']; // actual location
				
				$ext = $this->getExtension($file_name);
				$ext = strtolower($ext);
				if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
					return 6; //file type mistmathc
					
				$userpath= $path . '/comp_'.$id;
				if (!file_exists($userpath)){ // create user directory
					mkdir($userpath, 0755);
				}
				$userpath= $userpath. '/smalllogo';
				if (!file_exists($userpath)){ // create logo directory
					mkdir($userpath, 0755);
				}
				$isupload = true;
			}
		}elseif ($action == 3) { //About Company
			if($_FILES['aboutcompany']['size'] > 0){
				$file_name = $_FILES['aboutcompany']['name']; // file name
				$file_tmp = $_FILES['aboutcompany']['tmp_name']; // actual location
				
				$ext = $this->getExtension($file_name);
				$ext = strtolower($ext);
				if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf") && ($ext != "opt") && ($ext != "rtf"))
					return 6; //file type mistmathc
					
				$userpath= $path . '/comp_'.$id;
				if (!file_exists($userpath)){ // create user directory
					mkdir($userpath, 0755);
				}
				$userpath= $userpath. '/aboutcompany';
				if (!file_exists($userpath)){ // create logo directory
					mkdir($userpath, 0755);
				}
				$isupload = true;
			}
	
		
		}
		
		if ($isupload){
			$files = glob($userpath.'/*.*');
			array_map('unlink', $files);  //delete all file in directory
			
			move_uploaded_file($file_tmp, $userpath.'/' . $file_name);
			//unlink($file_tmp);
			
			return 1;
		}else { // DELETE FILES
			if ($action == 1) { // company logo
				if ($isdeletefile == 1){
					$userpath= $path . '/comp_'.$id . '/logo';
					$files = glob($userpath.'/*.*');
					array_map('unlink', $files); // delete all file in the direcoty 
				}
			}elseif ($action == 2) { // company small logo
				if ($isdeletefile == 1){
					$userpath= $path . '/comp_'.$id . '/smalllogo';
					$files = glob($userpath.'/*.*');
					array_map('unlink', $files); // delete all file in the direcoty 
				}
			}elseif ($action == 3) { // about company 
				if ($isdeletefile == 1){
					$userpath= $path . '/comp_'.$id . '/aboutcompany';
					$files = glob($userpath.'/*.*');
					array_map('unlink', $files); // delete all file in the direcoty 
				}
			}
			return 1;
		}

	}

	function &listAddressData($data,$val)
	{
		$db = &$this->getDBO();
		
		if ($data=='country') {  // country
			$query  = "SELECT code, name FROM ".$db->nameQuote('#__js_job_countries')."  WHERE enabled = 'Y' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='country' size='40' maxlength='100'  />";
			}else {
				$return_value = "<select name='country' class='inputbox' onChange=\"dochange('state', this.value)\">\n";
				$return_value .= "<option value='0'>". JText::_('JS_CHOOSE_COUNTRY') ."</option>\n";

				foreach($result as $row){
			       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}

		}else if ($data=='state') {  // states
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_states')."  WHERE enabled = 'Y' AND countrycode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='state' size='40' maxlength='100'  />";
			}else {
				$return_value = "<select name='state' class='inputbox' onChange=\"dochange('county', this.value)\">\n";
				$return_value .= "<option value='0'>". JText::_('JS_CHOOSE_STATE') ."</option>\n";

				foreach($result as $row){
					   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}
		}else if ($data=='county') {  // county
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_counties')."  WHERE enabled = 'Y' AND statecode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))
			{
				$return_value = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
			}else
			{
				  $return_value = "<select name='county' class='inputbox' onChange=\"dochange('city', this.value)\">\n";
				  $return_value .= "<option value='0'>". JText::_('JS_CHOOSE_COUNTY') ."</option>\n";
				  
				  
				  foreach($result as $row){
				       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}


		} else if ($data=='city') { // city
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_cities')."  WHERE enabled = 'Y' AND countycode= '$val' ORDER BY 'name'";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			//if (mysql_num_rows($result)== 0)
			if (empty($result))
			{
				$return_value = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
			}else
			{
				  $return_value = "<select name='city' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
				  $return_value .= "<option value='0'>". JText::_('JS_CHOOSE_CITY') ."</option>\n";
				  
				  
				  foreach($result as $row){
				       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}

		}
		return $return_value;
	}

	function &listSearchAddressData($data,$val)
	{
		$db = &$this->getDBO();
		
		if ($data=='country') {  // country
			$query  = "SELECT code, name FROM ".$db->nameQuote('#__js_job_countries')." WHERE enabled = 'Y' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='country' size='40' maxlength='100'  />";
			}else {
				$return_value = "<select name='country' class='inputbox' onChange=\"dochange('state', this.value)\">\n";
				$return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";

				foreach($result as $row){
			       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}

		}else if ($data=='state') {  // states
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_states')."  WHERE enabled = 'Y' AND countrycode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='state' size='40' maxlength='100'  />";
			}else {
				$return_value = "<select name='state' class='inputbox' onChange=\"dochange('county', this.value)\">\n";
				$return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";

				foreach($result as $row){
					   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}
		}else if ($data=='county') {  // county
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_counties')."  WHERE enabled = 'Y' AND statecode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
			}else
			{
				  $return_value = "<select name='county' class='inputbox' onChange=\"dochange('city', this.value)\">\n";
				  $return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";
				  
				  
				  foreach($result as $row){
				       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}


		} else if ($data=='city') { // city
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_cities')."  WHERE enabled = 'Y' AND countycode= '$val' ORDER BY 'name'";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			//if (mysql_num_rows($result)== 0)
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
			}else
			{
				  $return_value = "<select name='city' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
				  $return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";
				  
				  
				  foreach($result as $row){
				       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}

		}
		return $return_value;
	}

	function &listModuleSearchAddressData($data,$val,$for)
	{
		$db = &$this->getDBO();
		$methodname = $for.'dochange';
		
		if ($data=='country') {  // country
			$query  = "SELECT code, name FROM ".$db->nameQuote('#__js_job_countries')." WHERE enabled = 'Y' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='country' size='27' maxlength='100'  />";
			}else {
				$return_value = "<select name='country' class='inputbox' style='width:160px;' onChange=\"$methodname('state', this.value)\">\n";
				$return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";

				foreach($result as $row){
			       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}

		}else if ($data=='state') {  // states
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_states')."  WHERE enabled = 'Y' AND countrycode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='state' size='27' maxlength='100'  />";
			}else {
				$return_value = "<select name='state' class='inputbox' style='width:160px;' onChange=\"$methodname('county', this.value)\">\n";
				$return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";

				foreach($result as $row){
					   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}
		}else if ($data=='county') {  // county
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_counties')."  WHERE enabled = 'Y' AND statecode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='county' size='27' maxlength='100'  />";
			}else
			{
				  $return_value = "<select name='county' class='inputbox' style='width:160px;' onChange=\"$methodname('city', this.value)\">\n";
				  $return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";
				  
				  
				  foreach($result as $row){
				       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}


		} else if ($data=='city') { // city
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_cities')."  WHERE enabled = 'Y' AND countycode= '$val' ORDER BY 'name'";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			//if (mysql_num_rows($result)== 0)
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='city' size='27' maxlength='100'  />";
			}else
			{
				  $return_value = "<select name='city' class='inputbox' style='width:160px;' onChange=\"$methodname('zipcode', this.value)\">\n";
				  $return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";
				  
				  
				  foreach($result as $row){
				       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}

		}
		return $return_value;
	}

	function &listFilterAddressData($data,$val)
	{
		$db = &$this->getDBO();

		if(! isset($this->_config)){
			$this->getConfig();
		}	
		foreach ($this->_config as $conf){
			if ($conf->configname == 'filter_address_fields_width')
				$address_fields_width = $conf->configvalue;
		}

		if ($data=='country') {  // country
			$query  = "SELECT code, name FROM ".$db->nameQuote('#__js_job_countries')." WHERE enabled = 'Y' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' style='width:".$address_fields_width."px;' type='text' name='txtfilter_country' size='25' maxlength='50'  />";
			}else {
				$return_value = "<select name='cmbfilter_country' style='width:".$address_fields_width."px;' onChange=\"filter_dochange('filter_state', this.value)\">\n";
				$return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";
				foreach($result as $row){
			       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}

		}else if ($data=='filter_state') {  // states
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_states')."  WHERE enabled = 'Y' AND countrycode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' style='width:".$address_fields_width."px;' name='txtfilter_state' size='25' maxlength='50'  />";
			}else {
				$return_value = "<select name='cmbfilter_state' class='inputbox' style='width:".$address_fields_width."px;' onChange=\"filter_dochange('filter_county', this.value)\">\n";
				$return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";

				foreach($result as $row){
					   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}
		}else if ($data=='filter_county') {  // county
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_counties')."  WHERE enabled = 'Y' AND statecode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' style='width:".$address_fields_width."px;' name='txtfilter_county' size='25' maxlength='50'  />";
			}else
			{
				  $return_value = "<select name='cmbfilter_county' class='inputbox' style='width:".$address_fields_width."px;' onChange=\"filter_dochange('filter_city', this.value)\">\n";
				  $return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";
				  foreach($result as $row){
				       $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}

		} else if ($data=='filter_city') { // city
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_cities')."  WHERE enabled = 'Y' AND countycode= '$val' ORDER BY 'name'";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			//if (mysql_num_rows($result)== 0)
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' style='width:".$address_fields_width."px;' name='txtfilter_city' size='25' maxlength='50'  />";
			}else
			{
				$return_value = "<select name='cmbfilter_city' class='inputbox' style='width:".$address_fields_width."px;' onChange=\"filter_dochange('zipcode', this.value)\">\n";
				$return_value .= "<option value=''>".JText::_('JS_SEARCH_ALL')."</option>\n";
				foreach($result as $row){
				   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}
		}
		return $return_value;
	}

	function &listEmpAddressData($name, $myname, $nextname, $data, $val)
	{
		$db = &$this->getDBO();

		
		if ($data=='country') {  // country
			$query  = "SELECT code, name FROM ".$db->nameQuote('#__js_job_countries')."  WHERE enabled = 'Y' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='$name' size='40' maxlength='100'  />";
			}else {

				$return_value = "<select name='$name' onChange=\"dochange(\"$myname\",'state', this.value)\">\n";
				$return_value .= "<option value='0'>". JText::_('JS_CHOOSE_COUNTRY') ."</option>\n";

				foreach($result as $row){
				   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}

		}else if ($data=='state') {  // states
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_states')."  WHERE enabled = 'Y' AND countrycode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='$name' size='40' maxlength='100'  />";
			}else
			{
				$return_value = "<select name='$name' class='inputbox' onChange=\"dochange('$myname','$nextname','','county', this.value)\">\n";
				$return_value .= "<option value='0'>". JText::_('JS_CHOOSE_STATE') ."</option>\n";
				  
				foreach($result as $row){
					   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}
		}else if ($data=='county') {  // county
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_counties')."  WHERE enabled = 'Y' AND statecode= '$val' ORDER BY name ASC";
			$db->setQuery($query);
			$result = $db->loadObjectList();

			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='$name' size='40' maxlength='100'  />";
			}else
			{
				$return_value = "<select name='$name' class='inputbox' onChange=\"dochange('$myname','','','city', this.value)\">\n";
				$return_value .= "<option value='0'>". JText::_('JS_CHOOSE_COUNTY') ."</option>\n";
				  
				foreach($result as $row){
					   $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				  }
				$return_value .= "</select>\n";
			}


		} else if ($data=='city') { // second dropdown
		    $query  = "SELECT code, name from ".$db->nameQuote('#__js_job_cities')."  WHERE enabled = 'Y' AND countycode= '$val' ORDER BY 'name'";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			if (empty($result))	{
				$return_value = "<input class='inputbox' type='text' name='$name' size='40' maxlength='100'  />";
			}else
			{
				$return_value = "<select name='$name' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
				$return_value .= "<option value='0'>". JText::_('JS_CHOOSE_CITY') ."</option>\n";
				  
				foreach($result as $row){
				    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n" ;
				}
				$return_value .= "</select>\n";
			}

		}
		return $return_value;
	}		


	function &getEmpOptions()
	{
		if ( !$this->_empoptions )
		{
			$this->_empoptions = array();

		$gender = array(
			'0' => array('value' => 1,'text' => JText::_('JS_MALE')),
			'1' => array('value' => 2,'text' => JText::_('JS_FEMALE')),);

			$job_type = $this->getJobType('');
			$heighesteducation = $this->getHeighestEducation('');
			$job_categories = $this->getCategories('');
			$job_salaryrange = $this->getJobSalaryRange('');
			$countries = $this->getCountries('');

			if(isset($this->_application))$address_states = $this->getStates($this->_application->address_country, '');
			if(isset($this->_application))$address_counties = $this->getCounties($this->_application->address_state, '');
			if(isset($this->_application))$address_cities = $this->getCities($this->_application->address_county, '');

			if(isset($this->_application))$institute_states = $this->getStates($this->_application->institute_country, '');
			if(isset($this->_application))$institute_counties = $this->getCounties($this->_application->institute_state, '');
			if(isset($this->_application))$institute_cities = $this->getCities($this->_application->institute_county, '');
			
			if(isset($this->_application))$institute1_states = $this->getStates($this->_application->institute1_country, '');
			if(isset($this->_application))$institute1_counties = $this->getCounties($this->_application->institute1_state, '');
			if(isset($this->_application))$institute1_cities = $this->getCities($this->_application->institute1_county, '');

			if(isset($this->_application))$employer_states = $this->getStates($this->_application->employer_country, '');
			if(isset($this->_application))$employer_counties = $this->getCounties($this->_application->employer_state, '');
			if(isset($this->_application))$employer_cities = $this->getCities($this->_application->employer_county, '');
			
			if ( isset($this->_application) )
			{
				$this->_empoptions['nationality'] = JHTML::_('select.genericList', $countries, 'nationality','class="inputbox" '.'', 'value', 'text', $this->_application->nationality);
				$this->_empoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender','class="inputbox" '.'', 'value', 'text', $this->_application->gender);

				$this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" '. '', 'value', 'text', $this->_application->job_category);

				$this->_empoptions['address_country'] = JHTML::_('select.genericList', $countries, 'address_country','class="inputbox" '.'onChange="dochange(\'address_state\', \'address_county\',\'address_city\', \'state\', this.value)"', 'value', 'text', $this->_application->address_country);
				if (isset($address_states[1])) if ($address_states[1] != '')$this->_empoptions['address_state'] = JHTML::_('select.genericList', $address_states, 'address_state', 'class="inputbox" '. 'onChange="dochange(\'address_county\, \'address_city\', , this.value)"', 'value', 'text', $this->_application->address_state);
				if (isset($address_counties[1])) if ($address_counties[1] != '')$this->_empoptions['address_county'] = JHTML::_('select.genericList', $address_counties, 'address_county', 'class="inputbox" '. 'onChange="dochange(\'address_city\, , , this.value)"', 'value', 'text', $this->_application->address_county);
				if (isset($address_cities[1])) if ($address_cities[1] != '')$this->_empoptions['address_city'] = JHTML::_('select.genericList', $address_cities, 'address_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->address_city);
				
				$this->_empoptions['address1_country'] = JHTML::_('select.genericList', $countries, 'address1_country','class="inputbox" '.'onChange="dochange(\'address1_state\', \'address1_county\',\'address1_city\',\'state\', this.value)"', 'value', 'text', $this->_application->address1_country);
				$this->_empoptions['address2_country'] = JHTML::_('select.genericList', $countries, 'address2_country','class="inputbox" '.'onChange="dochange(\'address2_state\', \'address2_county\',\'address2_city\',\'state\', this.value)"', 'value', 'text', $this->_application->address2_country);

				$this->_empoptions['institute_country'] = JHTML::_('select.genericList', $countries, 'institute_country','class="inputbox" '.'onChange="dochange(\'institute_state\', \'institute_county\',\'institute_city\', \'state\', this.value)"', 'value', 'text', $this->_application->institute_country);
				if (isset($institute_states[1])) if ($institute_states[1] != '')$this->_empoptions['institute_state'] = JHTML::_('select.genericList', $institute_states, 'institute_state', 'class="inputbox" '. 'onChange="dochange(\'institute_county\, \'institute_city\', , this.value)"', 'value', 'text', $this->_application->institute_state);
				if (isset($institute_counties[1])) if ($institute_counties[1] != '')$this->_empoptions['institute_county'] = JHTML::_('select.genericList', $institute_counties, 'institute_county', 'class="inputbox" '. 'onChange="dochange(\'institute_city\,  , , this.value)"', 'value', 'text', $this->_application->institute_county);
				if (isset($institute_cities[1])) if ($institute_cities[1] != '')$this->_empoptions['institute_city'] = JHTML::_('select.genericList', $institute_cities, 'institute_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->institute_city);

				$this->_empoptions['institute1_country'] = JHTML::_('select.genericList', $countries, 'institute1_country','class="inputbox" '.'onChange="dochange(\'institute1_state\', \'institute1_county\',\'institute1_city\',\'state\', this.value)"', 'value', 'text', $this->_application->institute1_country);
				if (isset($institute1_states[1])) if ($institute1_states[1] != '')$this->_empoptions['institute1_state'] = JHTML::_('select.genericList', $institute1_states, 'institute1_state', 'class="inputbox" '. 'onChange="dochange(\'institute1_county\, \'institute1_city\', , this.value)"', 'value', 'text', $this->_application->institute1_state);
				if (isset($institute1_counties[1])) if ($institute1_counties[1] != '')$this->_empoptions['institute1_county'] = JHTML::_('select.genericList', $institute1_counties, 'institute1_county', 'class="inputbox" '. 'onChange="dochange(\'institute1_city\,  , , this.value)"', 'value', 'text', $this->_application->institute1_county);
				if (isset($institute1_cities[1])) if ($institute1_cities[1] != '')$this->_empoptions['institute1_city'] = JHTML::_('select.genericList', $institute1_cities, 'institute1_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->institute1_city);

				$this->_empoptions['institute2_country'] = JHTML::_('select.genericList', $countries, 'institute2_country','class="inputbox" '.'onChange="dochange(\'institute2_state\', \'institute2_county\',\'institute2_city\',\'state\', this.value)"', 'value', 'text', $this->_application->institute2_country);
				$this->_empoptions['institute3_country'] = JHTML::_('select.genericList', $countries, 'institute3_country','class="inputbox" '.'onChange="dochange(\'institute3_state\', \'institute3_county\',\'institute3_city\',\'state\', this.value)"', 'value', 'text', $this->_application->institute3_country);

				$this->_empoptions['employer_country'] = JHTML::_('select.genericList', $countries, 'employer_country','class="inputbox" '.'onChange="dochange(\'employer_state\', \'employer_county\',\'employer_city\',\'state\', this.value)"', 'value', 'text', $this->_application->employer_country);
				if (isset($employer_states[1])) if ($employer_states[1] != '')$this->_empoptions['employer_state'] = JHTML::_('select.genericList', $employer_states, 'employer_state', 'class="inputbox" '. 'onChange="dochange(\'employer_county\, \'employer_city\', , this.value)"', 'value', 'text', $this->_application->employer_state);
				if (isset($employer_counties[1])) if ($employer_counties[1] != '')$this->_empoptions['employer_county'] = JHTML::_('select.genericList', $employer_counties, 'employer_county', 'class="inputbox" '. 'onChange="dochange(\'employer_city\,  , , this.value)"', 'value', 'text', $this->_application->employer_county);
				if (isset($employer_cities[1])) if ($employer_cities[1] != '')$this->_empoptions['employer_city'] = JHTML::_('select.genericList', $employer_cities, 'employer_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->employer_city);

				$this->_empoptions['employer1_country'] = JHTML::_('select.genericList', $countries, 'employer1_country','class="inputbox" '.'onChange="dochange(\'employer1_state\', \'employer1_county\',\'employer1_city\',\'state\', this.value)"', 'value', 'text', $this->_application->employer1_country);
				$this->_empoptions['employer2_country'] = JHTML::_('select.genericList', $countries, 'employer2_country','class="inputbox" '.'onChange="dochange(\'employer2_state\', \'employer2_county\',\'employer2_city\',\'state\', this.value)"', 'value', 'text', $this->_application->employer2_country);
				$this->_empoptions['employer3_country'] = JHTML::_('select.genericList', $countries, 'employer3_country','class="inputbox" '.'onChange="dochange(\'employer3_state\', \'employer3_county\',\'employer3_city\',\'state\', this.value)"', 'value', 'text', $this->_application->employer3_country);

				$this->_empoptions['reference_country'] = JHTML::_('select.genericList', $countries, 'reference_country','class="inputbox" '.'onChange="dochange(\'reference_state\', \'reference_county\',\'reference_city\',\'state\', this.value)"', 'value', 'text', $this->_application->reference_country);
				$this->_empoptions['reference1_country'] = JHTML::_('select.genericList', $countries, 'reference1_country','class="inputbox" '.'onChange="dochange(\'reference1_state\', \'reference1_county\',\'reference1_city\',\'state\', this.value)"', 'value', 'text', $this->_application->reference1_country);
				$this->_empoptions['reference2_country'] = JHTML::_('select.genericList', $countries, 'reference2_country','class="inputbox" '.'onChange="dochange(\'reference2_state\', \'reference2_county\',\'reference2_city\',\'state\', this.value)"', 'value', 'text', $this->_application->reference2_country);
				$this->_empoptions['reference3_country'] = JHTML::_('select.genericList', $countries, 'reference3_country','class="inputbox" '.'onChange="dochange(\'reference3_state\', \'reference3_county\',\'reference3_city\',\'state\', this.value)"', 'value', 'text', $this->_application->reference3_country);

				$this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobtype);
				$this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', $this->_application->heighestfinisheducation);
				$this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobsalaryrange);


			}
			else
			{
				$this->_empoptions['nationality'] = JHTML::_('select.genericList', $countries, 'nationality','class="inputbox" '.'', 'value', 'text', '');
				$this->_empoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender','class="inputbox" '.'', 'value', 'text', '');

				$this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" '. '', 'value', 'text', '');
				
				$this->_empoptions['address_country'] = JHTML::_('select.genericList', $countries, 'address_country','class="inputbox" '.'onChange="dochange(\'address_state\', \'address_county\',\'address_city\', \'state\', this.value)"', 'value', 'text', '');
				//echo '<br> st '.$address_states[1];
				if ( isset($address_states[1]) )if ($address_states[1] != '')$this->_empoptions['address_state'] = JHTML::_('select.genericList', $address_states, 'address_state', 'class="inputbox" '. 'onChange="dochange(\'address_county\, \'address_city\', , this.value)"', 'value', 'text', '');
				if ( isset($address_counties[1]) )if ($address_counties[1] != '')$this->_empoptions['address_county'] = JHTML::_('select.genericList', $address_counties, 'address_county', 'class="inputbox" '. 'onChange="dochange(\'address_city\, , , this.value)"', 'value', 'text', '');
				if ( isset($address_cities[1]) )if ($address_cities[1] != '')$this->_empoptions['address_city'] = JHTML::_('select.genericList', $address_cities, 'address_city', 'class="inputbox" '. '', 'value', 'text', '');
				
				$this->_empoptions['address1_country'] = JHTML::_('select.genericList', $countries, 'address1_country','class="inputbox" '.'onChange="dochange(\'address1_state\', \'address1_county\',\'address1_city\',\'state\', this.value)"', 'value', 'text', '');
				$this->_empoptions['address2_country'] = JHTML::_('select.genericList', $countries, 'address2_country','class="inputbox" '.'onChange="dochange(\'address2_state\', \'address2_county\',\'address2_city\',\'state\', this.value)"', 'value', 'text', '');

				$this->_empoptions['institute_country'] = JHTML::_('select.genericList', $countries, 'institute_country','class="inputbox" '.'onChange="dochange(\'institute_state\', \'institute_county\',\'institute_city\', \'state\', this.value)"', 'value', 'text', '');
				if ( isset($institute_states[1]) )if ($institute_states[1] != '')$this->_empoptions['institute_state'] = JHTML::_('select.genericList', $institute_states, 'institute_state', 'class="inputbox" '. 'onChange="dochange(\'institute_county\, \'institute_city\', , this.value)"', 'value', 'text', '');
				if ( isset($institute_counties[1]) )if ($institute_counties[1] != '')$this->_empoptions['institute_county'] = JHTML::_('select.genericList', $institute_counties, 'institute_county', 'class="inputbox" '. 'onChange="dochange(\'institute_city\,  , , this.value)"', 'value', 'text', '');
				if ( isset($institute_cities[1]) )if ($institute_cities[1] != '')$this->_empoptions['institute_city'] = JHTML::_('select.genericList', $institute_cities, 'institute_city', 'class="inputbox" '. '', 'value', 'text', '');

				$this->_empoptions['institute1_country'] = JHTML::_('select.genericList', $countries, 'institute1_country','class="inputbox" '.'onChange="dochange(\'institute1_state\', \'institute1_county\',\'institute1_city\',\'state\', this.value)"', 'value', 'text', '');
				if (isset($institute1_states[1])) if ($institute1_states[1] != '')$this->_empoptions['institute1_state'] = JHTML::_('select.genericList', $institute1_states, 'institute1_state', 'class="inputbox" '. 'onChange="dochange(\'institute1_county\, \'institute1_city\', , this.value)"', 'value', 'text', '');
				if (isset($institute1_counties[1])) if ($institute1_counties[1] != '')$this->_empoptions['institute1_county'] = JHTML::_('select.genericList', $institute1_counties, 'institute1_county', 'class="inputbox" '. 'onChange="dochange(\'institute1_city\,  , , this.value)"', 'value', 'text', '');
				if (isset($institute1_cities[1])) if ($institute1_cities[1] != '')$this->_empoptions['institute1_city'] = JHTML::_('select.genericList', $institute1_cities, 'institute1_city', 'class="inputbox" '. '', 'value', 'text', '');

				$this->_empoptions['institute2_country'] = JHTML::_('select.genericList', $countries, 'institute2_country','class="inputbox" '.'onChange="dochange(\'institute2_state\', \'institute2_county\',\'institute2_city\',\'state\', this.value)"', 'value', 'text', '');
				$this->_empoptions['institute3_country'] = JHTML::_('select.genericList', $countries, 'institute3_country','class="inputbox" '.'onChange="dochange(\'institute3_state\', \'institute3_county\',\'institute3_city\',\'state\', this.value)"', 'value', 'text', '');

				$this->_empoptions['employer_country'] = JHTML::_('select.genericList', $countries, 'employer_country','class="inputbox" '.'onChange="dochange(\'employer_state\', \'employer_county\',\'employer_city\',\'state\', this.value)"', 'value', 'text', '');
				if ( isset($employer_states[1]) )if ($employer_states[1] != '')$this->_empoptions['employer_state'] = JHTML::_('select.genericList', $employer_states, 'employer_state', 'class="inputbox" '. 'onChange="dochange(\'employer_county\, \'employer_city\', , this.value)"', 'value', 'text', '');
				if ( isset($employer_counties[1]) )if ($employer_counties[1] != '')$this->_empoptions['employer_county'] = JHTML::_('select.genericList', $employer_counties, 'employer_county', 'class="inputbox" '. 'onChange="dochange(\'employer_city\,  , , this.value)"', 'value', 'text', '');
				if ( isset($employer_cities[1]) )if ($employer_cities[1] != '')$this->_empoptions['employer_city'] = JHTML::_('select.genericList', $employer_cities, 'employer_city', 'class="inputbox" '. '', 'value', 'text', '');

				$this->_empoptions['employer1_country'] = JHTML::_('select.genericList', $countries, 'employer1_country','class="inputbox" '.'onChange="dochange(\'employer1_state\', \'employer1_county\',\'employer1_city\',\'state\', this.value)"', 'value', 'text', '');
				$this->_empoptions['employer2_country'] = JHTML::_('select.genericList', $countries, 'employer2_country','class="inputbox" '.'onChange="dochange(\'employer2_state\', \'employer2_county\',\'employer2_city\',\'state\', this.value)"', 'value', 'text', '');
				$this->_empoptions['employer3_country'] = JHTML::_('select.genericList', $countries, 'employer3_country','class="inputbox" '.'onChange="dochange(\'employer3_state\', \'employer3_county\',\'employer3_city\',\'state\', this.value)"', 'value', 'text', '');

				$this->_empoptions['reference_country'] = JHTML::_('select.genericList', $countries, 'reference_country','class="inputbox" '.'onChange="dochange(\'reference_state\', \'reference_county\',\'reference_city\',\'state\', this.value)"', 'value', 'text', '');
				$this->_empoptions['reference1_country'] = JHTML::_('select.genericList', $countries, 'reference1_country','class="inputbox" '.'onChange="dochange(\'reference1_state\', \'reference1_county\',\'reference1_city\',\'state\', this.value)"', 'value', 'text', '');
				$this->_empoptions['reference2_country'] = JHTML::_('select.genericList', $countries, 'reference2_country','class="inputbox" '.'onChange="dochange(\'reference2_state\', \'reference2_county\',\'reference2_city\',\'state\', this.value)"', 'value', 'text', '');
				$this->_empoptions['reference3_country'] = JHTML::_('select.genericList', $countries, 'reference3_country','class="inputbox" '.'onChange="dochange(\'reference3_state\', \'reference3_county\',\'reference3_city\',\'state\', this.value)"', 'value', 'text', '');


				$this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
			}
		}
		return $this->_empoptions;
	}	
	

	function &getSearchOptions()
	{
		//echo '<br> search options';
		if ( !$this->_searchoptions )
		{
			$this->_searchoptions = array();

			$companies = $this->getAllCompanies(JText::_('JS_SEARCH_ALL'));
			$job_type = $this->getJobType(JText::_('JS_SEARCH_ALL'));
			$jobstatus = $this->getJobStatus(JText::_('JS_SEARCH_ALL'));
			$heighesteducation = $this->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
			$job_categories = $this->getCategories(JText::_('JS_SEARCH_ALL'));
			$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SEARCH_ALL'));
			$shift = $this->getShift(JText::_('JS_SEARCH_ALL'));

			$this->_searchoptions['companies'] = JHTML::_('select.genericList', $companies, 'company', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_searchoptions['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_searchoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_searchoptions['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_searchoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_searchoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_searchoptions['shift'] = JHTML::_('select.genericList', $shift, 'shift', 'class="inputbox" '. '', 'value', 'text', '');
		}
		return $this->_searchoptions;
	}	
	
	function &getResumeSearchOptions()
	{

			$gender = array(
			'0' => array('value' => '','text' => JText::_('JS_SEARCH_ALL')),
			'1' => array('value' => 1,'text' => JText::_('JS_MALE')),
			'2' => array('value' => 2,'text' => JText::_('JS_FEMALE')),);

			$nationality = $this->getCountries(JText::_('JS_SEARCH_ALL'));
			$job_type = $this->getJobType(JText::_('JS_SEARCH_ALL'));
			$heighesteducation = $this->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
			$job_categories = $this->getCategories(JText::_('JS_SEARCH_ALL'));
			$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SEARCH_ALL'));

			$searchoptions['nationality'] = JHTML::_('select.genericList', $nationality, 'nationality', 'class="inputbox" '. '', 'value', 'text', '');
			$searchoptions['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" '. '', 'value', 'text', '');
			$searchoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
			$searchoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', '');
			$searchoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', '');
			$searchoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" '. '', 'value', 'text', '');
		return $searchoptions;
	}	
	
	function &getOptions()
	{
		if ( !$this->_options )
		{
			$this->_options = array();
							
			$jobtype = $this->getJobType('');
			$jobstatus = $this->getJobStatus('');
			$heighesteducation = $this->getHeighestEducation('');
		
			$job_categories = $this->getCategories('');
			$job_salaryrange = $this->getJobSalaryRange('');
			$countries = $this->getCountries('');
			if ( isset($this->_application) ){
				$states = $this->getStates($this->_application->country, '');
				$counties = $this->getCounties($this->_application->state, '');
				$cities = $this->getCities($this->_application->county, '');
			}

			if ( isset($this->_application) )
			{
				$this->_options['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobcategory);
				$this->_options['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobsalaryrange);
				$this->_options['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_application->country);
				if ( isset($states[1]) ) if ($states[1] != '')$this->_options['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', $this->_application->state);
				if ( isset($counties[1]) ) if ($counties[1] != '')$this->_options['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', $this->_application->county);
				if ( isset($cities[1]) ) if ($cities[1] != '')$this->_options['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', $this->_application->city);
				$this->_options['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobstatus);
				$this->_options['jobtype'] = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobtype);
				$this->_options['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', $this->_application->heighestfinisheducation);

			}
			else
			{
				$this->_options['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', '');
				if ( isset($states[1]) ) if ($states[1] != '')$this->_options['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', '');
				if ( isset($counties[1]) ) if ($counties[1] != '')$this->_options['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
				if ( isset($cities[1]) ) if ($cities[1] != '')$this->_options['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['jobtype'] = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', '');
				
			}
		}
		return $this->_options;
	}	
	
	function &getFilterLists()
	{
		//if ( !$this->_filterlists ){
			$this->_filterlists = array();

			$jobtype = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
		//	$jobtype = $this->getJobType('abc');
			$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
			$heighesteducation = $this->getHeighestEducation(JText::_('JS_SELECT_EDUCATION'));

			$job_categories = $this->getCategories(JText::_('JS_SELECT_CATEGORY'));
			$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY'));
			$countries = $this->getCountries('');
			
			//$this->_filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country','class="inputbox" '.'onChange="dochange(\'filter_state\', \'filter_county\',\'filter_city\',\'state\', this.value)"', 'value', 'text', '');
			$this->_filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country','class="inputbox" '.'onChange="dochange(\'filter_state\', this.value)"', 'value', 'text', '');

			$this->_filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_filterlists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'filter_jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" '. '', 'value', 'text', '');
			$this->_filterlists['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'filter_heighesteducation', 'class="inputbox" '. '', 'value', 'text', '');
				
		//}
		return $this->_filterlists;
	}	
	

	function getCategories( $title )
	{
		$db =& JFactory::getDBO();
		
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_categories')." WHERE isactive = 1 ORDER BY cat_title ";
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$jobcategories = array();
		if($title)
			$jobcategories[] =  array('value' => JText::_(''),'text' => $title);
			
		foreach($rows as $row)
		{
			$jobcategories[] =  array('value' => JText::_($row->id),
								'text' => JText::_($row->cat_title));
		}
		return $jobcategories;	
			
	}

	function getJobSalaryRange( $title )
	{
		$db =& JFactory::getDBO();
		
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_salaryrange')." ORDER BY 'id' ";
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		if ( !$this->_config )
			$this->getConfig();
		foreach ($this->_config as $conf){
			if ($conf->configname == 'currency')
				$currency = $conf->configvalue;
		}

		$jobsalaryrange = array();
		if($title)
			$jobsalaryrange[] =  array('value' => JText::_(''),'text' => $title);

		foreach($rows as $row)
		{
			$salrange = $currency . $row->rangestart.' - '.$currency . $row->rangeend;
			$jobsalaryrange[] =  array('value' => JText::_($row->id),
								'text' => JText::_($salrange));
		}
		return $jobsalaryrange;	
			
	}

	function jobTypes($id, $val, $fild ){
		if ( !$this->_config )
			$this->getConfig();
			foreach ($this->_config as $conf){
				if ($conf->configname == $fild)
					$value = $conf->configvalue;
			}
		if ($value != $id ) return 3;
		$db =& JFactory::getDBO();
		$query = "UPDATE ".$db->nameQuote('#__js_job_jobtypes')." SET status = ".$val;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;	
	}
	function jobShifts($id, $val, $fild ){
		if ( !$this->_config )
			$this->getConfig();
			foreach ($this->_config as $conf){
				if ($conf->configname == $fild)
					$value = $conf->configvalue;
			}
		if ($value != $id ) return 3;
		$db =& JFactory::getDBO();
		$query = "UPDATE ".$db->nameQuote('#__js_job_shifts')." SET status = ".$val;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;	
	}


	function getCountries( $title )
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_countries')." WHERE enabled = 'Y' ORDER BY name ASC ";
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$countries = array();
		if($title)
			$countries[] =  array('value' => JText::_(''),'text' => $title);
		else	
			$countries[] =  array('value' => JText::_(''),'text' => JText::_('JS_CHOOSE_COUNTRY'));
		
		foreach($rows as $row)	{
			$countries[] =  array('value' => $row->code,'text' => JText::_($row->name));
		}
		return $countries;	
	}

	function getStates( $countrycode, $title)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_states')." WHERE enabled = 'Y' AND countrycode = '". $countrycode ."' ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$states = array();
		if($title)
			$states[] =  array('value' => JText::_(''),'text' => $title);
		else	
			$states[] =  array('value' => JText::_(''),	'text' => JText::_('JS_CHOOSE_STATE'));
			
		foreach($rows as $row)
		{
			$states[] =  array('value' => JText::_($row->code),
							'text' => JText::_($row->name));
		}
		return $states;	
	}
	function getCounties( $statecode, $title )
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_counties')." WHERE enabled = 'Y' AND statecode = '". $statecode ."' ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$counties = array();
		if($title)
			$counties[] =  array('value' => JText::_(''),'text' => $title);
		else	
			$counties[] =  array('value' => JText::_(''),	'text' => JText::_('JS_CHOOSE_COUNTY'));
			
		foreach($rows as $row)
		{
			$counties[] =  array('value' => JText::_($row->code),
							'text' => JText::_($row->name));
		}
		return $counties;	
	}
	function getCities( $countycode, $title )
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_cities')." WHERE enabled = 'Y' AND countycode = '". $countycode ."' ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$cities = array();
		if($title)
			$cities[] =  array('value' => JText::_(''),'text' => $title);
		else	
			$cities[] =  array('value' => JText::_(''),	'text' => JText::_('JS_CHOOSE_CITY'));
			
		foreach($rows as $row)
		{
			$cities[] =  array('value' => JText::_($row->code),
							'text' => JText::_($row->name));
		}
		return $cities;	
	}

	function getCompanies( $uid )
	{
			if (is_numeric($uid) == false) return false;
			$db =& JFactory::getDBO();
			$query = "SELECT id, name FROM ".$db->nameQuote('#__js_job_companies')." WHERE uid = ". $uid ." AND status = 1 ORDER BY name ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$companies = array();
			foreach($rows as $row)
			{
				$companies[] =  array('value' => $row->id,'text' => $row->name);
			}
		return $companies;	
	}
	
	function getAllCompanies( $title )
	{
			$db =& JFactory::getDBO();
			$query = "SELECT id, name FROM ".$db->nameQuote('#__js_job_companies')." ORDER BY name ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$companies = array();
			if($title)
				$companies[] =  array('value' => JText::_(''),'text' => $title);
			foreach($rows as $row){
				$companies[] =  array('value' => $row->id,'text' => $row->name);
			}
		return $companies;	
	}
	
	function getJobType( $title ){
//		if ( !$this->_jobtype){
			$db =& JFactory::getDBO();
			$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobtypes')." WHERE isactive = 1 ORDER BY id ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$this->_jobtype = array();
			if($title)
				$this->_jobtype[] =  array('value' => JText::_(''),'text' => $title);

			foreach($rows as $row)
			{
				$this->_jobtype[] =  array('value' => JText::_($row->id),
								'text' => JText::_($row->title));
			}
		
//		}
		return $this->_jobtype;	
	}

	function getJobStatus( $title ){
//		if ( !$this->_jobstatus){
			$db =& JFactory::getDBO();
			$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobstatus')." WHERE isactive = 1 ORDER BY id ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$this->_jobstatus = array();
			if($title)
				$this->_jobstatus[] =  array('value' => JText::_(''),'text' => $title);

			foreach($rows as $row)	{
				$this->_jobstatus[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
			}
//		}
		return $this->_jobstatus;	
	}

	function getHeighestEducation( $title ){
//		if ( !$this->_heighesteducation ){
			$db =& JFactory::getDBO();
			$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_heighesteducation')." WHERE isactive = 1 ORDER BY id ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$this->_heighesteducation = array();
			if($title)
				$this->_heighesteducation[] =  array('value' => JText::_(''),'text' => $title);

			foreach($rows as $row)	{
				$this->_heighesteducation[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
			}
//		}						
		return $this->_heighesteducation;	
	}

	function getShift( $title ){
		if ( !$this->_shifts ){
			$db =& JFactory::getDBO();
			$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_shifts')." WHERE isactive = 1 ORDER BY id ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$this->_shifts = array();
			if($title)
				$this->_shifts[] =  array('value' => JText::_(''),'text' => $title);
			foreach($rows as $row)	{
				$this->_shifts[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
			}
		}						
		return $this->_shifts;	
	}

	function getExtension($str) {
		 $i = strrpos($str,".");
		 if (!$i) { return ""; }
		 $l = strlen($str) - $i;
		 $ext = substr($str,$i+1,$l);
		 return $ext;
	}

function backupv104()
{

	$db = &JFactory::getDBO();
	$companies = array();
	$query = 'SELECT * FROM '.$db->nameQuote('#__js_job_jobs_bak');
	//echo '<br> SQL '.$query;
	$db->setQuery($query);
	$jobs = $db->loadObjectList();
	$curdate = date('Y-m-d H:i:s');
	//echo '<br> date '.$curdate;
	$startdate = date("Y-m-d");
	$stopdate = strftime("%Y-%m-%d",strtotime($curdate.' +3 month'));
	//echo '<br> stop '.$stopdate;
	$companyid = 2;
	if( isset($jobs)){
		$query = 'INSERT INTO '.$db->nameQuote('#__js_job_companies_2').' 
		(uid, category, name, contactname, contactemail, country, created, status)  
		VALUES (62, 1, "Default Company","Default","default@sitename.com", "PK", '.$db->Quote($curdate).', 1)';
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$db->query();
		foreach ($jobs as $job) {
			if ($job->company){ // company is not exist in job
				$key = array_search($job->company, $companies);
				if ($key != NULL || $key !== FALSE) {  // company not exist
						//echo '<br> insert <b>job KEY '.$key.' - '.$job->company.'</b>';
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs_2').' 
						(uid, companyid, title, jobcategory ,jobtype, jobstatus, jobsalaryrange, hidesalaryrange
						, description, qualifications, country, state, county, city	, zipcode, address1, address2, companyurl
						, contactname, contactphone	, contactemail, showcontact, noofjobs, duration, heighestfinisheducation
						, created, startpublishing	,stoppublishing, shift, sendemail, status)  
						VALUES (
						'.$job->uid.', '.$key.', '.$db->Quote($job->title).', '.$job->jobcategory .','.$job->jobtype.', '.$job->jobstatus.', '.$job->jobsalaryrange.', 0
						, '.$db->Quote($job->description).', '.$db->Quote($job->qualifications).', '.$db->Quote($job->country).', '.$db->Quote($job->state).', '.$db->Quote($job->county).', '.$db->Quote($job->city).', '.$db->Quote($job->zipcode).', '.$db->Quote($job->address1).', '.$db->Quote($job->address2).', '.$db->Quote($job->companyurl).'
						, '.$db->Quote($job->contactname).', '.$db->Quote($job->contactphone).', '.$db->Quote($job->contactemail).', '.$db->Quote($job->showcontact).', '.$job->noofjobs.', '.$db->Quote($job->duration).', '.$job->heighestfinisheducation.'
						, '.$db->Quote($job->created).', '.$db->Quote($startdate).', '.$db->Quote($stopdate).', 1, '.$job->applyinfo.', '.$job->status .')';
						//echo '<br> SQL '.$query;
						$db->setQuery($query);
						$db->query();
				}else{ //company exist
						//echo '<br> insert company';
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_companies_2').' 
						(uid, category, name, url ,contactname, contactphone, contactemail
						, country, state, county, city	, zipcode, address1, address2, created, status)  
						VALUES (
						'.$job->uid.', '.$job->jobcategory.', '.$db->Quote($job->company).', '.$db->Quote($job->companyurl).','.$db->Quote($job->contactname).', '.$db->Quote($job->contactphone).', '.$db->Quote($job->contactemail).' 
						, '.$db->Quote($job->country).', '.$db->Quote($job->state).', '.$db->Quote($job->county).', '.$db->Quote($job->city).', '.$db->Quote($job->zipcode).', '.$db->Quote($job->address1).', '.$db->Quote($job->address2).', '.$db->Quote($curdate).', 1)';
						//echo '<br> SQL '.$query;
						$db->setQuery($query);
						$db->query();
						$companies[$companyid] = $job->company;	

						//echo '<br> insert job';
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs_2').' 
						(uid, companyid, title, jobcategory ,jobtype, jobstatus, jobsalaryrange, hidesalaryrange
						, description, qualifications, country, state, county, city	, zipcode, address1, address2, companyurl
						, contactname, contactphone	, contactemail, showcontact, noofjobs, duration, heighestfinisheducation
						, created, startpublishing	,stoppublishing, shift, sendemail, status)  
						VALUES (
						'.$job->uid.', '.$companyid.', '.$db->Quote($job->title).', '.$job->jobcategory .','.$job->jobtype.', '.$job->jobstatus.', '.$job->jobsalaryrange.', 0
						, '.$db->Quote($job->description).', '.$db->Quote($job->qualifications).', '.$db->Quote($job->country).', '.$db->Quote($job->state).', '.$db->Quote($job->county).', '.$db->Quote($job->city).', '.$db->Quote($job->zipcode).', '.$db->Quote($job->address1).', '.$db->Quote($job->address2).', '.$db->Quote($job->companyurl).'
						, '.$db->Quote($job->contactname).', '.$db->Quote($job->contactphone).', '.$db->Quote($job->contactemail).', '.$db->Quote($job->showcontact).', '.$job->noofjobs.', '.$db->Quote($job->duration).', '.$job->heighestfinisheducation.'
						, '.$db->Quote($job->created).', '.$db->Quote($startdate).', '.$db->Quote($stopdate).', 1, '.$job->applyinfo.', '.$job->status .')';
						//echo '<br> SQL '.$query;
						$db->setQuery($query);
						$db->query();
					$companyid++ ;	
				}	
			
			}else { // company not exist in job default company
						//echo '<br> default company - insert job';
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs_2').' 
						(uid, companyid, title, jobcategory ,jobtype, jobstatus, jobsalaryrange, hidesalaryrange
						, description, qualifications, country, state, county, city	, zipcode, address1, address2, companyurl
						, contactname, contactphone	, contactemail, showcontact, noofjobs, duration, heighestfinisheducation
						, created, startpublishing	,stoppublishing, shift, sendemail, status)  
						VALUES (
						'.$job->uid.', 1, '.$db->Quote($job->title).', '.$job->jobcategory .','.$job->jobtype.', '.$job->jobstatus.', '.$job->jobsalaryrange.', 0
						, '.$db->Quote($job->description).', '.$db->Quote($job->qualifications).', '.$db->Quote($job->country).', '.$db->Quote($job->state).', '.$db->Quote($job->county).', '.$db->Quote($job->city).', '.$db->Quote($job->zipcode).', '.$db->Quote($job->address1).', '.$db->Quote($job->address2).', '.$db->Quote($job->companyurl).'
						, '.$db->Quote($job->contactname).', '.$db->Quote($job->contactphone).', '.$db->Quote($job->contactemail).', '.$db->Quote($job->showcontact).', '.$job->noofjobs.', '.$db->Quote($job->duration).', '.$job->heighestfinisheducation.'
						, '.$db->Quote($job->created).', '.$db->Quote($startdate).', '.$db->Quote($stopdate).', 1, '.$job->applyinfo.', '.$job->status .')';
						//echo '<br> SQL '.$query;
						$db->setQuery($query);
						$db->query();
			}
		
		}
	}else echo '<br> not found';	
	
	
		$query = 'INSERT INTO '.$db->nameQuote('#__js_job_resume_2').' 
		(uid, create_date, application_title ,first_name, last_name, middle_name, email_address, home_phone, work_phone, cell
		, job_category, jobsalaryrange, jobtype, heighestfinisheducation
		, address_country, address_state, address_county, address_city, address_zipcode, address
		, institute, institute_country, institute_state, institute_county, institute_city, institute_certificate_name, institute_study_area
		,employer, employer_position, employer_resp, employer_from_date, employer_to_date, employer_leave_reason
		, employer_country, employer_state, employer_city, employer_zip, employer_phone
		, filename, filetype, filesize, filecontent, field1, field2, field3, status)  
		SELECT uid, create_date, application_title
		,first_name, last_name, middle_name, email_address, home_phone, work_phone, cell
		, job_category, jobsalaryrange, jobtype, heighestfinisheducation
		, address_country, address_state, address_county, address_city, address_zipcode, address
		, institute, institute_country, institute_state, institute_county, institute_city, certificate_name, study_area
		,employer, employer_position, employer_resp, employer_from_date, employer_to_date, employer_leave_reason
		, employer_country, employer_state, employer_city, employer_zip, employer_phone
		, filename, filetype, filesize, filecontent, field1, field2, field3, status 
		FROM  '.$db->nameQuote('#__js_job_resume_bak');
		echo '<br> SQL '.$query;
		$db->setQuery($query);
		if ( $result = $db->queryBatch())
			echo JText::_('Resume Retrieved backed up data successfully!<br>');
		else
			echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Resume data.</font><br>');

		$query = 'SELECT * FROM '.$db->nameQuote('#__js_job_resume_2');
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$resumes = $db->loadObjectList();
		if( isset($resumes)){
			foreach ($resumes as $resume) {
				if ($resume->filename){
					$iddir = 'resume_'.$resume->id;
					$path =JPATH_BASE.'/components/com_jsjobs/data2';
					if (!file_exists($path)){ // creating resume directory
						mkdir($path, 0755);
					}
					$path =JPATH_BASE.'/components/com_jsjobs/data2/jobseeker';
					if (!file_exists($path)){ // creating resume directory
						mkdir($path, 0755);
					}
					$userpath= $path . '/'.$iddir;
					if (!file_exists($userpath)){ // create user directory
						mkdir($userpath, 0755);
					}
					$userpath= $path . '/'.$iddir.'/resume';
					if (!file_exists($userpath)){ // create user directory
						mkdir($userpath, 0755);
					}
					$from = 'components/jsjobs_resume_bak/'.$resume->uid.'/'.$resume->filename;
					$to = $userpath.'/'.$resume->filename;
					copy($from,$to);
				
				}
			
			}
		}	

}

}
?>
