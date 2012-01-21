<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/models/jsjobs.php
 ^ 
 * Description: Model for application on admin site
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.html.html');


class JSJobsModelJsjobs extends JModel
{
	var $_id;
	var $_application;
	var $_options;
	var $_empoptions;
	var $_uid;
	var $_job = null;
	var $_config = null;
	var $_defaultcountry = null;
	var $_job_editor = null;
	var $_comp_editor = null;
	var $_data_directory = null;
	var $_shifts = null;
	
	function __construct()
	{
		parent :: __construct();


		$cid = JRequest :: getVar('cid', false, 'DEFAULT', 'array');
		if ($cid)
		{
			$id = $cid[0];
		}
		else
		{
			$id = JRequest :: getInt('id', 0);
		}
		$this->setId($id);
	}


	function & getAllCategories($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_categories";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_categories ORDER BY id ASC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

		$result[0] = $this->_application;
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllJobTypes($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_jobtypes";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_jobtypes ORDER BY id ASC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllJobStatus($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_jobstatus";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_jobstatus ORDER BY id ASC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllShifts($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_shifts";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_shifts ORDER BY id ASC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllHighestEducations($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_heighesteducation";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_heighesteducation ORDER BY id ASC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_companies AS company WHERE company.status <> 0";
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND company.category = ".$searchjobcategory;
		if ($searchcountry) $query .= " AND company.country = ".$db->Quote($searchcountry);
		
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT company.*, cat.cat_title , country.name AS countryname
				FROM #__js_job_companies AS company  
				JOIN #__js_job_categories AS cat ON company.category = cat.id
				JOIN #__js_job_countries AS country ON company.country = country.code
				WHERE company.status <> 0";
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND company.category = ".$searchjobcategory;
		if ($searchcountry) $query .= " AND company.country = ".$db->Quote($searchcountry);
		
		$query .= " ORDER BY company.created DESC";

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
								
			$job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'),'');
			$countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
			if ($searchcompany) $lists['searchcompany'] = $searchcompany;
			if($searchjobcategory) 
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"', 'value', 'text', $searchjobcategory );
			else
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"', 'value', 'text', '' );
			if ($searchcountry)
				$lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchcountry);
			else	
				$lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
		
		
		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		//return $this->_application;
		return $result;
	}

	function & getAllUnapprovedCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_companies AS company WHERE company.status = 0";
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND company.category = ".$searchjobcategory;
		if ($searchcountry) $query .= " AND company.country = ".$db->Quote($searchcountry);
		
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT company.*, cat.cat_title , country.name AS countryname
				FROM #__js_job_companies AS company  
				JOIN #__js_job_categories AS cat ON company.category = cat.id
				JOIN #__js_job_countries AS country ON company.country = country.code
				WHERE company.status = 0";
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND company.category = ".$searchjobcategory;
		if ($searchcountry) $query .= " AND company.country = ".$db->Quote($searchcountry);
		
		$query .= " ORDER BY company.created DESC";

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
								
			$job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'),'');
			$countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
			if ($searchcompany) $lists['searchcompany'] = $searchcompany;
			if($searchjobcategory) 
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"', 'value', 'text', $searchjobcategory );
			else
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"', 'value', 'text', '' );
			if ($searchcountry)
				$lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchcountry);
			else	
				$lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
		
		
		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		//return $this->_application;
		return $result;
	}

	function & getAllJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job
					LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
					WHERE job.status <> 0";
		if ($searchtitle) $query .= " AND LOWER(job.title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND job.jobcategory = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND job.jobtype = ".$searchjobtype;
		if ($searchjobstatus) $query .= " AND job.jobstatus = ".$searchjobstatus;
		
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle, company.name AS companyname  
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job 
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
				WHERE job.status <> 0";
		if ($searchtitle) $query .= " AND LOWER(job.title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND job.jobcategory = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND job.jobtype = ".$searchjobtype;
		if ($searchjobstatus) $query .= " AND job.jobstatus = ".$searchjobstatus;
		
		$query .= " ORDER BY job.created DESC";

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
			$job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
			$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
								
			$job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'),'');
			if ($searchtitle) $lists['searchtitle'] = $searchtitle;
			if ($searchcompany) $lists['searchcompany'] = $searchcompany;
			if($searchjobcategory) 
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', $searchjobcategory );
			else
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', '' );
			if ($searchjobtype)
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
			else	
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
			if ($searchjobstatus)
				$lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus','class="inputbox" '.'onChange="document.adminForm.submit();"'.'style="width:115px"', 'value', 'text', $searchjobstatus);
			else
				$lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus','class="inputbox" '.'onChange="document.adminForm.submit();"'.'style="width:115px"', 'value', 'text', '');
		
		
		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		//return $this->_application;
		return $result;
	}

	function & getAppliedResume($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE job.status <> 0";
		if ($searchtitle) $query .= " AND LOWER(job.title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchcompany) $query .= " AND LOWER(job.company) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND job.jobcategory = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND job.jobtype = ".$searchjobtype;
		if ($searchjobstatus) $query .= " AND job.jobstatus = ".$searchjobstatus;
		
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle, company.name AS companyname
				, ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobapply')." WHERE jobid = job.id) AS totalresume
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job 
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id 
				JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id
				WHERE job.status <> 0";
		if ($searchtitle) $query .= " AND LOWER(job.title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchcompany) $query .= " AND LOWER(job.company) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND job.jobcategory = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND job.jobtype = ".$searchjobtype;
		if ($searchjobstatus) $query .= " AND job.jobstatus = ".$searchjobstatus;
		
		$query .= " ORDER BY job.created DESC";

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
			$job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
			$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
								
			$job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'),'');
			if ($searchtitle) $lists['searchtitle'] = $searchtitle;
			if ($searchcompany) $lists['searchcompany'] = $searchcompany;
			if($searchjobcategory) 
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', $searchjobcategory );
			else
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', '' );
			if ($searchjobtype)
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
			else	
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
			if ($searchjobstatus)
				$lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus','class="inputbox" '.'onChange="document.adminForm.submit();"'.'style="width:115px"', 'value', 'text', $searchjobstatus);
			else
				$lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus','class="inputbox" '.'onChange="document.adminForm.submit();"'.'style="width:115px"', 'value', 'text', '');
		
		
		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		//return $this->_application;
		return $result;
	}

	function & getJobAppliedResume($jobid, $searchname, $searchjobtype, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();

		$query = "SELECT COUNT(apply.id) FROM ".$db->nameQuote('#__js_job_jobapply')." AS apply 	WHERE  apply.jobid = ".$jobid;

		if ($searchname) {
			$query .= " AND (";
				$query .= " LOWER(app.first_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.last_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.middle_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
			$query .= " )";
		}	
		if ($searchjobtype) $query .= " AND app.jobtype = ".$searchjobtype;
		
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT cat.cat_title ,apply.apply_date, jobtype.title AS jobtypetitle
				, app.id AS appid, app.first_name, app.last_name, app.email_address, app.jobtype
				, app.jobsalaryrange, salary.rangestart, salary.rangeend
				FROM ".$db->nameQuote('#__js_job_resume')." AS app 
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON app.jobtype = jobtype.id
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON app.job_category = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobapply')." AS apply  ON apply.cvid = app.id 
				LEFT OUTER JOIN  ".$db->nameQuote('#__js_job_salaryrange')." AS salary	ON	app.jobsalaryrange=salary.id
		WHERE apply.jobid = ".$jobid;

		if ($searchname) {
			$query .= " AND (";
				$query .= " LOWER(app.first_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.last_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.middle_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
			$query .= " )";
		}	
		if ($searchjobtype) $query .= " AND app.jobtype = ".$searchjobtype;
		
		$query .= " ORDER BY apply.apply_date DESC";

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
			$job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
								
			if ($searchname) $lists['searchname'] = $searchname;
			if ($searchjobtype)
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
			else	
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
		
		
		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		//return $this->_application;
		return $result;
	}

	function &getResumeViewbyId($id) 
	{
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



		$result[0] = $resume;
		$result[1] = $resume2;
		$result[2] = $this->getResumeViewbyId3($id);
		$result[3] = $this->getFieldsOrderingforForm(3); // resume fields
		return $result;
	}

	function &getResumeViewbyId3($id) 
	{
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

	function & getAllEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_resume AS app WHERE app.status <> 0";
		if ($searchtitle) $query .= " AND LOWER(app.application_title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchname) {
			$query .= " AND (";
				$query .= " LOWER(app.first_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.last_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.middle_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
			$query .= " )";
		}	
		if ($searchjobcategory) $query .= " AND app.job_category = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND app.jobtype = ".$searchjobtype;
		if ($searchjobsalaryrange) $query .= " AND app.jobsalaryrange = ".$searchjobsalaryrange;
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT app.id, app.application_title,app.first_name, app.last_name, app.jobtype, 
				app.jobsalaryrange, app.create_date, app.status, cat.cat_title, salary.rangestart, salary.rangeend
				, jobtype.title AS jobtypetitle
				FROM #__js_job_resume AS app , #__js_job_categories AS cat, #__js_job_salaryrange AS salary , #__js_job_jobtypes AS jobtype
				WHERE app.job_category = cat.id AND app.jobsalaryrange = salary.id AND app.jobtype = jobtype.id AND app.status <> 0";
		if ($searchtitle) $query .= " AND LOWER(app.application_title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchname) {
			$query .= " AND (";
				$query .= " LOWER(app.first_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.last_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.middle_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
			$query .= " )";
		}	
		if ($searchjobcategory) $query .= " AND app.job_category = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND app.jobtype = ".$searchjobtype;
		if ($searchjobsalaryrange) $query .= " AND app.jobsalaryrange = ".$searchjobsalaryrange;
		
		$query .= " ORDER BY app.create_date DESC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
			$job_type = array(
				'0' => array('value' => '',	'text' => JText::_('JS_SELECT_JOB_TYPE')),
				'1' => array('value' => JText::_(1),'text' => JText::_('JS_JOBTYPE_FULLTIME')),
				'2' => array('value' => JText::_(2),'text' => JText::_('JS_JOBTYPE_PARTTIME')),
				'3' => array('value' => JText::_(3),'text' => JText::_('JS_JOBTYPE_INTERNSHIP')),);

								
			$job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'),'');
			$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY_RANGE'),'');
			
			if ($searchtitle) $lists['searchtitle'] = $searchtitle;
			if ($searchname) $lists['searchname'] = $searchname;
			if($searchjobcategory) 
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', $searchjobcategory );
			else
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', '' );
			if ($searchjobtype)
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
			else	
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
			if ($searchjobsalaryrange)
				$lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange','class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobsalaryrange);
			else
				$lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange','class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
		
		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		return $result;
	}

	function & getAllUnapprovedJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job
					LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
					WHERE job.status = 0";
		if ($searchtitle) $query .= " AND LOWER(job.title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND job.jobcategory = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND job.jobtype = ".$searchjobtype;
		if ($searchjobstatus) $query .= " AND job.jobstatus = ".$searchjobstatus;

		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle, company.name AS companyname  
				FROM ".$db->nameQuote('#__js_job_jobs')." AS job 
				JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id
				JOIN ".$db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
				JOIN ".$db->nameQuote('#__js_job_jobstatus')." AS jobstatus ON job.jobstatus = jobstatus.id 
				LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
				WHERE  job.status = 0 ";
		if ($searchtitle) $query .= " AND LOWER(job.title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchcompany) $query .= " AND LOWER(company.name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchcompany, true ).'%', false );
		if ($searchjobcategory) $query .= " AND job.jobcategory = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND job.jobtype = ".$searchjobtype;
		if ($searchjobstatus) $query .= " AND job.jobstatus = ".$searchjobstatus;
		
		$query .= " ORDER BY job.created DESC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
			$job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
			$jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
								
			$job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'),'');
			if ($searchtitle) $lists['searchtitle'] = $searchtitle;
			if ($searchcompany) $lists['searchcompany'] = $searchcompany;
			if($searchjobcategory) 
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', $searchjobcategory );
			else
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', '' );
			if ($searchjobtype)
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
			else	
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
			if ($searchjobstatus)
				$lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus','class="inputbox" '.'onChange="document.adminForm.submit();"'.'style="width:115px"', 'value', 'text', $searchjobstatus);
			else
				$lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus','class="inputbox" '.'onChange="document.adminForm.submit();"'.'style="width:115px"', 'value', 'text', '');

		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		return $result;
	}

	function & getAllUnapprovedEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_resume AS app WHERE status = 0";
		if ($searchtitle) $query .= " AND LOWER(app.application_title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchname) {
			$query .= " AND (";
				$query .= " LOWER(app.first_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.last_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.middle_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
			$query .= " )";
		}	
		if ($searchjobcategory) $query .= " AND app.job_category = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND app.jobtype = ".$searchjobtype;
		if ($searchjobsalaryrange) $query .= " AND app.jobsalaryrange = ".$searchjobsalaryrange;
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT app.id, app.application_title,app.first_name, app.last_name, app.jobtype, 
				app.jobsalaryrange, app.create_date, cat.cat_title , salary.rangestart, salary.rangeend
				, jobtype.title AS jobtypetitle
				FROM #__js_job_resume AS app , #__js_job_categories AS cat, #__js_job_salaryrange AS salary, #__js_job_jobtypes AS jobtype 
				WHERE app.job_category = cat.id AND app.jobsalaryrange = salary.id AND app.jobtype= jobtype.id AND app.status = 0 ";
		if ($searchtitle) $query .= " AND LOWER(app.application_title) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchtitle, true ).'%', false );
		if ($searchname) {
			$query .= " AND (";
				$query .= " LOWER(app.first_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.last_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
				$query .= " OR LOWER(app.middle_name) LIKE ".$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
			$query .= " )";
		}	
		if ($searchjobcategory) $query .= " AND app.job_category = ".$searchjobcategory;
		if ($searchjobtype) $query .= " AND app.jobtype = ".$searchjobtype;
		if ($searchjobsalaryrange) $query .= " AND app.jobsalaryrange = ".$searchjobsalaryrange;
		
		$query .= " ORDER BY app.create_date DESC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

			$lists = array();
			
			$job_type = array(
				'0' => array('value' => '',	'text' => JText::_('JS_SELECT_JOB_TYPE')),
				'1' => array('value' => JText::_(1),'text' => JText::_('JS_JOBTYPE_FULLTIME')),
				'2' => array('value' => JText::_(2),'text' => JText::_('JS_JOBTYPE_PARTTIME')),
				'3' => array('value' => JText::_(3),'text' => JText::_('JS_JOBTYPE_INTERNSHIP')),);

								
			$job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'),'');
			$job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY_RANGE'),'');
			
			if ($searchtitle) $lists['searchtitle'] = $searchtitle;
			if ($searchname) $lists['searchname'] = $searchname;
			if($searchjobcategory) 
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', $searchjobcategory );
			else
				$lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" '.'onChange="this.form.submit();"'.'style="width:115px"', 'value', 'text', '' );
			if ($searchjobtype)
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
			else	
				$lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');
			if ($searchjobsalaryrange)
				$lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange','class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobsalaryrange);
			else
				$lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange','class="inputbox" '.'onChange="document.adminForm.submit();"', 'value', 'text', '');

		$result[0] = $this->_application;
		$result[1] = $total;
		$result[2] = $lists;
		//return $this->_application;
		return $result;
	}

	function & getAllSalaryRange($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_salaryrange";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_salaryrange";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

		$result[0] = $this->_application;
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllRoles($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_roles";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_roles ORDER BY id ASC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		//$this->_application = $db->loadObjectList();

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllUsers($searchname, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		
		$query = 'SELECT COUNT(a.id)'
		. ' FROM #__users AS a';
		if ($searchname) 
			$query .= 'WHERE LOWER(a.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );
		
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = 'SELECT a.*, g.name AS groupname, role.title AS roletitle'
			. ' FROM #__users AS a'
			. ' INNER JOIN #__core_acl_aro AS aro ON aro.value = a.id'
			. ' INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id'
			. ' INNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id'
			. ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
			. ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role'	;
		if ($searchname) 
			$query .= 'WHERE LOWER(a.name) LIKE '.$db->Quote( '%'.$db->getEscaped( $searchname, true ).'%', false );

		$query .= ' GROUP BY a.id';

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$result[0]= $db->loadObjectList();
		
		$lists = array();
		if ($searchname) $lists['searchname'] = $searchname;
		
		$lists['roles'] = $this->getRoles('');
		$result[1] = $total;
		$result[2] = $lists;
		return $result;
	}

	function & getAllCountries($limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM #__js_job_countries";
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM #__js_job_countries ORDER BY name ASC";
		//echo $query;
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		//return $this->_application;
		return $result;
	}

	function & getAllCountryStates($countrycode, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_states')." WHERE countrycode = ". $db->Quote($countrycode);
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_states')." WHERE countrycode = ". $db->Quote($countrycode) ." ORDER BY name ASC";
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		return $result;
	}

	function & getAllStateCounties($statecode, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_counties')." WHERE statecode = ". $db->Quote($statecode);
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_counties')." WHERE statecode = ". $db->Quote($statecode) ." ORDER BY name ASC";
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		return $result;
	}

	function & getAllCountyCities($countycode, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_cities')." WHERE countycode = ". $db->Quote($countycode);
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_cities')." WHERE countycode = ". $db->Quote($countycode) ." ORDER BY name ASC";
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		return $result;
	}

	function & getUserFields($fieldfor, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		
		$query = 'SELECT COUNT(id) FROM #__js_job_userfields WHERE fieldfor = '. $fieldfor;
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = 'SELECT field.* FROM #__js_job_userfields AS field WHERE fieldfor = '. $fieldfor;
		$query .= ' ORDER BY field.id';

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);
		$this->_application = $db->loadObjectList();

		$result[0] = $this->_application;
		$result[1] = $total;
		return $result;
	}

	function & getFieldsOrdering($fieldfor, $limitstart, $limit)
	{
		$db = & JFactory :: getDBO();
		$result = array();
		
		$query = 'SELECT COUNT(id) FROM #__js_job_fieldsordering WHERE fieldfor = '. $fieldfor;
		$db->setQuery($query);
		$total = $db->loadResult();

		$query = 'SELECT field.* ,userfield.title as userfieldtitle
					FROM #__js_job_fieldsordering AS field 
					LEFT JOIN #__js_job_userfields AS userfield ON field.field = userfield.id
					WHERE field.fieldfor = '. $fieldfor;
		$query .= ' ORDER BY field.ordering';

		//echo $query;
		$db->setQuery($query,$limitstart, $limit);

		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		return $result;
	}

	function &getUserFieldsforForm($fieldfor, $refid)
	{
		$db = &$this->getDBO();
		$field = array();
		$result = array();
		$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfields')." 
					WHERE published = 1 AND fieldfor = ". $fieldfor;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$i = 0;
		foreach ($rows as $row){
			//$result[$i] = $row;
			$field[0] = $row;
			if ($refid != ""){
				$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfield_data')." WHERE referenceid = ".$refid." AND field = ". $row->id;
				//echo '<br> SQL '.$query;
				$db->setQuery($query);
				$data = $db->loadObject();
				$field[1] = $data;
				//echo '<br> data '.$field[1];
				//$result[$i][] = $row;
			}
			if ($row->type == "select"){
				$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_userfieldvalues')." WHERE field = ". $row->id;
				//echo '<br> SQL '.$query;
				$db->setQuery($query);
				$values = $db->loadObjectList();
				//$result[$i] = array($values);
				$field[2] = $values;
			}
			$result[] = $field;
			$i++;
		}
		return $result;
	}
	
	function &getFieldsOrderingforForm($fieldfor)
	{
		$db = &$this->getDBO();
		$query =  "SELECT  * FROM ".$db->nameQuote('#__js_job_fieldsordering')." 
					WHERE published = 1 AND fieldfor =  ". $fieldfor
					." ORDER BY ordering";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$fieldordering = $db->loadObjectList();
		return $fieldordering;
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
				elseif ($conf->configname == "data_directory")
					$this->_data_directory = $conf->configvalue;
			}
		}
		
		return $this->_config;
	}

	function &getConfigurationsForForm()
	{
		if (isset($this->_config ) == false){
			$db = &$this->getDBO();

			$query = "SELECT * FROM ".$db->nameQuote('#__js_job_config');
			//echo '<br> SQL '.$query;
			$db->setQuery($query);
			$this->_config = $db->loadObjectList();
		}
			foreach($this->_config as $conf)
			{
				if ($conf->configname == "defaultcountry")
					$this->_defaultcountry = $conf->configvalue;
				elseif ($conf->configname == "employerdefaultrole")
					$employerdefaultrole = $conf->configvalue;
				elseif ($conf->configname == "jobseekerdefaultrole")
					$jobseekerdefaultrole = $conf->configvalue;
				elseif ($conf->configname == "data_directory")
					$this->_data_directory = $conf->configvalue;
			}
			$countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
			$employerroles = $this->getRoles(1); // 1 for employer
			$jobseekerroles = $this->getRoles(2); // 2 for jobseeker
			$lists['defaultcountry'] = JHTML::_('select.genericList', $countries, 'defaultcountry', 'class="inputbox" '.'', 'value', 'text', $this->_defaultcountry);
			$lists['employerdefaultrole'] = JHTML::_('select.genericList', $employerroles, 'employerdefaultrole', 'class="inputbox" '.'', 'value', 'text', $employerdefaultrole);
			$lists['jobseekerdefaultrole'] = JHTML::_('select.genericList', $jobseekerroles, 'jobseekerdefaultrole', 'class="inputbox" '.'', 'value', 'text', $jobseekerdefaultrole);

		$result[0] = $this->_config;
		$result[1] = $lists;
		return $result;
	}

	function & getTemplate($tempfor)
	{
		$db = & JFactory :: getDBO();
		switch($tempfor){
			case 'cm-ap' : $tempatefor = 'company-approval'; break;
			case 'cm-rj' : $tempatefor = 'company-rejecting'; break;
			case 'ob-ap' : $tempatefor = 'job-approval'; break;
			case 'ob-rj' : $tempatefor = 'job-rejecting'; break;
			case 'rm-ap' : $tempatefor = 'resume-approval'; break;
			case 'rm-rj' : $tempatefor = 'resume-rejecting'; break;
			case 'ba-ja' : $tempatefor = 'jobapply-jobapply'; break;
		}
		$query = "SELECT * FROM #__js_job_emailtemplates WHERE templatefor = ".$db->Quote($tempatefor);
		//echo $query;
		$db->setQuery($query);
		$template = $db->loadObject();
		return $template;
	}

	function & getCategorybyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_categories WHERE id = ".$db->Quote($c_id);
		//echo $query;
		$db->setQuery($query);
		$category = $db->loadObject();
		return $category;
	}

	function & getJobTypebyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_jobtypes WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$jobtype = $db->loadObject();
		return $jobtype;
	}

	function & getJobStatusbyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_jobstatus WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$jobstatus = $db->loadObject();
		return $jobstatus;
	}

	function & getShiftbyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_shifts WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$shift = $db->loadObject();
		return $shift;
	}

	function & getHighestEducationbyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_heighesteducation WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$education = $db->loadObject();
		return $education;
	}

	function & getCompanybyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_companies WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$company = $db->loadObject();

		$status = array(
			'0' => array('value' => 0,'text' => JText::_('JS_PENDDING')),
			'1' => array('value' => 1,'text' => JText::_('JS_APPROVE')),
			'2' => array('value' => -1,'text' => JText::_('JS_REJECT')),);

		$countries = $this->getCountries('');

		if ( isset($company) ){
			if(isset($company))$states = $this->getStates($company->country);
			if(isset($company))$counties = $this->getCounties($company->state);
			if(isset($company))$cities = $this->getCities($company->county);
			$lists['category'] = JHTML::_('select.genericList', $this->getCategories('',''), 'category', 'class="inputbox required" '. '', 'value', 'text', $company->category);
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $company->country);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', $company->state);
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', $company->county);
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', $company->city);
			$lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" '. '', 'value', 'text', $company->status);
		}else{
			if(! isset($this->_config)){
				$this->getConfig();
			}	
			if(isset($this->_defaultcountry))$states = $this->getStates($this->_defaultcountry);
			$lists['category'] = JHTML::_('select.genericList', $this->getCategories('',''), 'category', 'class="inputbox required" '. '', 'value', 'text', $company->jobcategory);
//			$lists['companies'] = JHTML::_('select.genericList', $this->getCompanies($uid), 'company', 'class="inputbox required" '. '', 'value', 'text', '');
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_defaultcountry);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', '');
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" '. '', 'value', 'text', '');
		}
		//$fieldordering = $this->getFieldsOrdering(1);// company fields
		//$userfields = $this->getUserFields(1, 0, 99999); // company fields, id
		$result[0] = $company;
		$result[1] = $lists;
		$result[2] = $this->getUserFieldsforForm(1, $c_id); // company fields, id
		$result[3] = $this->getFieldsOrderingforForm(1);// company fields

		return $result;

	}
	
	function & getJobbyId($c_id, $uid)
	{
		$db = & JFactory :: getDBO();
		
		$query = "SELECT job.*, cat.cat_title, salary.rangestart, salary.rangeend
			FROM ".$db->nameQuote('#__js_job_jobs')." AS job 
			JOIN ".$db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id 
			LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salary ON job.jobsalaryrange = salary.id 
			WHERE job.id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$this->_job = $db->loadObject();

		$status = array(
			'0' => array('value' => 0,'text' => JText::_('JS_PENDDING')),
			'1' => array('value' => 1,'text' => JText::_('JS_APPROVE')),
			'2' => array('value' => -1,'text' => JText::_('JS_REJECT')),);
		//echo '<br>status '.$this->	
		$countries = $this->getCountries('');
		if ( isset($this->_job) ){
			$states = $this->getStates($this->_job->country);
			$counties = $this->getCounties($this->_job->state);
			$cities = $this->getCities($this->_job->county);
			$lists['companies'] = JHTML::_('select.genericList', $this->getCompaniesbyJobId($c_id), 'companyid', 'class="inputbox required" '. '', 'value', 'text', $this->_job->company);
			$lists['jobcategory'] = JHTML::_('select.genericList', $this->getCategories('',''), 'jobcategory', 'class="inputbox" '. '', 'value', 'text', $this->_job->jobcategory);
			$lists['jobtype'] = JHTML::_('select.genericList', $this->getJobType(''), 'jobtype', 'class="inputbox" '. '', 'value', 'text', $this->_job->jobtype);
			$lists['jobstatus'] = JHTML::_('select.genericList', $this->getJobStatus(''), 'jobstatus', 'class="inputbox required" '. '', 'value', 'text', $this->_job->jobstatus);
			$lists['heighesteducation'] = JHTML::_('select.genericList', $this->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', $this->_job->heighestfinisheducation);
			$lists['shift'] = JHTML::_('select.genericList', $this->getShift(), 'shift', 'class="inputbox" '. '', 'value', 'text', $this->_job->shift);
			$lists['jobsalaryrange'] = JHTML::_('select.genericList', $this->getJobSalaryRange('',''), 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->_job->jobsalaryrange);
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_job->country);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', $this->_job->state);
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', $this->_job->county);
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', $this->_job->city);
			$lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" '. '', 'value', 'text', $this->_job->status);
		}else{
			if(! isset($this->_config)){ $this->getConfig();}	
			if(isset($this->_defaultcountry))$states = $this->getStates($this->_defaultcountry);
			$lists['companies'] = JHTML::_('select.genericList', $this->getCompanies($uid), 'companyid', 'class="inputbox required" '. '', 'value', 'text', '');
			$lists['jobcategory'] = JHTML::_('select.genericList', $this->getCategories('',''), 'jobcategory', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['jobtype'] = JHTML::_('select.genericList', $this->getJobType(''), 'jobtype', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['jobstatus'] = JHTML::_('select.genericList', $this->getJobStatus(''), 'jobstatus', 'class="inputbox required" '. '', 'value', 'text', '');
			$lists['heighesteducation'] = JHTML::_('select.genericList', $this->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['shift'] = JHTML::_('select.genericList', $this->getShift(), 'shift', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['jobsalaryrange'] = JHTML::_('select.genericList', $this->getJobSalaryRange('',''), 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_defaultcountry);
			if ( isset($states[1]) ) if ($states[1] != '')$lists['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', '');
			if ( isset($counties[1]) ) if ($counties[1] != '')$lists['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
			if ( isset($cities[1]) ) if ($cities[1] != '')$lists['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', '');
			$lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" '. '', 'value', 'text', '');
		}
		
		$result[0] = $this->_job;
		$result[1] = $lists;
		$result[2] = $this->getUserFieldsforForm(2, $c_id); // job fields, refid
		$result[3] = $this->getFieldsOrderingforForm(2); // job fields

		return $result;
	}

	function & getEmpAppbyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_resume WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$this->_application = $db->loadObject();
		$result[0] = $this->_application;
		//$result[2] = $this->getUserFields(3, this->_application->id); // company fields, id  USER FIELD NOT FOR RESUME
		$result[3] = $this->getFieldsOrderingforForm(3); // resume fields
		return $result;
	}

	function & getSalaryRangebyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_salaryrange WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$this->_application = $db->loadObject();
		return $this->_application;
	}

	function & getRolebyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_roles WHERE id = ".$c_id;
		//echo $query;
		$db->setQuery($query);
		$role = $db->loadObject();
		$for = array(
			'0' => array('value' => 1,'text' => JText::_('JS_EMPLOYER')),
			'1' => array('value' => 2,'text' => JText::_('JS_JOB_SEEKER')),);

		if ( isset($role) ){
			$lists['rolefor'] = JHTML::_('select.genericList', $for, 'rolefor', 'class="inputbox required" '. '', 'value', 'text', $role->rolefor);
		}else{
			$lists['rolefor'] = JHTML::_('select.genericList', $for, 'rolefor', 'class="inputbox required" '. '', 'value', 'text', '');
		}
		$result[0] = $role;
		$result[1] = $lists;
		return $result;
	}

	function & getChangeRolebyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = 'SELECT a.*, g.name AS groupname, usr.id AS userroleid, usr.role, role.title AS roletitle'
			. ' FROM #__users AS a'
			. ' INNER JOIN #__core_acl_aro AS aro ON aro.value = a.id'
			. ' INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id'
			. ' INNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id'
			. ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
			. ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role'	
			. ' WHERE a.id = '	.$c_id;

		//echo $query;
		$db->setQuery($query);
		$user = $db->loadObject();
		$roles = $this->getRoles('');
		if ( isset($user) ){
			$lists['roles'] = JHTML::_('select.genericList', $roles, 'role', 'class="inputbox required" '. '', 'value', 'text', $user->role);
		}else{
			$lists['roles'] = JHTML::_('select.genericList', $roles, 'role', 'class="inputbox required" '. '', 'value', 'text', '');
		}
		$result[0] = $user;
		$result[1] = $lists;
		return $result;
	}

	function & getUserFieldbyId($c_id)
	{
		$result = array();
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_userfields WHERE id = ".$db->Quote($c_id);
		//echo $query;
		$db->setQuery($query);
		$result[0] = $db->loadObject();

		$query = "SELECT * FROM #__js_job_userfieldvalues WHERE field = ".$db->Quote($c_id);
		//echo $query;
		$db->setQuery($query);
		$result[1] = $db->loadObjectList();

		return $result;
	}

	function & getCountrybyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_countries WHERE id = ".$c_id;
		$db->setQuery($query);
		$country = $db->loadObject();
		return $country;
	}

	function getConfigur()
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_config WHERE configname = 'refercode'";
		$db->setQuery($query);
		$conf = $db->loadObject();
		if ($conf->configvalue == '0'){
			$row = & $this->getTable('config');
			$reser_med = date('misyHd');
			$reser_med = md5($reser_med);
			$reser_med = md5($reser_med);
			$reser_med	=	substr($reser_med,0,10);
			$string = md5(time());
			$reser_start	=	substr($string,0,5);
			$reser_end =		substr($string,4,3);
			$value =  $reser_start.$reser_med.$reser_end;
			
			$config['configname'] = 'refercode';
			$config['configvalue'] = $value;
			if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
			if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
		}else $value = 	$conf->configvalue;
		
		return $value;

	}
	
	function storeActivate()
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_config WHERE configname = 'refercode'";
		$db->setQuery($query);
		$conf = $db->loadObject();
		if ($conf->configvalue != '0'){
			$row = & $this->getTable('config');
			$data = JRequest :: get('post');
			$str2 = $data['activationkey'];
			$reser_start = substr($conf->configvalue,2,3);
			$reser_med = substr($conf->configvalue,7,3);
			$reser_end = substr($conf->configvalue,12,3);
			$fstr = $reser_start.$reser_med.$reser_end;
			$reser_start = substr($str2,2,3);
			$reser_med = substr($str2,7,3);
			$reser_end = substr($str2,12,3);
			$sstr = $reser_start.$reser_med.$reser_end;
			if (strcmp($fstr,$sstr) == 0){
				$config['configname'] = 'actk';
				$config['configvalue'] = $data['activationkey'];
				if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
				if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
			}else return 3;	
		}else return 4;
		
		return true;

	}
	function & getStatebyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_states WHERE id = ".$c_id;
		$db->setQuery($query);
		$state = $db->loadObject();
		return $state;
	}

	function & getCountybyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_counties WHERE id = ".$c_id;
		$db->setQuery($query);
		$county = $db->loadObject();
		return $county;
	}

	function & getCitybyId($c_id)
	{
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_cities WHERE id = ".$c_id;
		$db->setQuery($query);
		$city = $db->loadObject();
		return $city;
	}

	// set the id
	function setId($id)
	{
		// Set id and wipe data
		$this->_id = $id;
		$this->_application = null;
	}
	

	// delete the data requested
	function deleteCompany()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('company');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->companyCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteJob()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('job');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->jobCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteResume()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('resume');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->resumeCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteEmpApp()
	{
		$cids = JRequest :: getVar('cid', array (
			0
		), 'post', 'array');
		$row = & $this->getTable('empapp');

		foreach ($cids as $cid)
		{
			if (!$row->delete($cid))
			{
				$this->setError($row->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	function deleteCategory()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('category');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->categoryCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	
	function deleteJobType()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('jobtype');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->jobTypeCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteJobStatus()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('jobstatus');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->jobStatusCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteShift()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('shift');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->shiftCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteHighestEducation()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('highesteducation');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->highestEducationCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteSalaryRange()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('salaryrange');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->salaryRangeCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteRole()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('role');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->roleCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	function deleteUserField()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('userfield');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->userFieldCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}

	function deleteCountry()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('country');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->countryCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	function deleteState()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('state');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->stateCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	function deleteCounty()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('county');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->countyCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	function deleteCity()
	{
		$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
		$row = & $this->getTable('city');
		$deleteall = 1;
		foreach ($cids as $cid)	{
			if($this->cityCanDelete($cid) == true){
				if (!$row->delete($cid)){
					$this->setError($row->getErrorMsg());
					return false;
				}
			}else $deleteall++ ;
		}
		return $deleteall;
	}
	
	//##################### CAN DELETE ######################
	function companyCanDelete($companyid){
		$db = &$this->getDBO();

		$query = "SELECT COUNT(job.id) FROM ".$db->nameQuote('#__js_job_jobs')." AS job  
					WHERE job.companyid = ".$companyid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}

	function jobCanDelete($jobid){
		$db = &$this->getDBO();

		$query = "SELECT COUNT(apply.id) FROM ".$db->nameQuote('#__js_job_jobapply')." AS apply  
					WHERE apply.jobid = ".$jobid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}

	function resumeCanDelete($resumeid){
		$db = &$this->getDBO();

		$query = "SELECT COUNT(apply.id) FROM ".$db->nameQuote('#__js_job_jobapply')." AS apply  
					WHERE apply.cvid = ".$resumeid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}

	function categoryCanDelete($categoryid){
		$db = &$this->getDBO();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_companies')." WHERE category = ". $categoryid .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE jobcategory = ". $categoryid .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE job_category = ". $categoryid .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}

	function jobTypeCanDelete($typeid){
		$db = &$this->getDBO();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE jobtype = ". $typeid .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE jobtype = ". $typeid .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)	return false;
		else return true;
	}

	function jobStatusCanDelete($statusid){
		$db = &$this->getDBO();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE jobstatus = ". $statusid .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)	return false;
		else return true;
	}

	function shiftCanDelete($shiftid){
		$db = &$this->getDBO();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE shift = ". $shiftid .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)	return false;
		else return true;
	}

	function highestEducationCanDelete($educationid){
		$db = &$this->getDBO();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE heighestfinisheducation = ". $educationid .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE heighestfinisheducation = ". $educationid .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)	return false;
		else return true;
	}

	function salaryRangeCanDelete($salaryid){
		$db = &$this->getDBO();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE jobsalaryrange = ". $salaryid .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE jobsalaryrange = ". $salaryid .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}

	function roleCanDelete($roleid){
		$db = &$this->getDBO();

		$query = "SELECT COUNT(userrole.id) FROM ".$db->nameQuote('#__js_job_userroles')." AS userrole  
					WHERE userrole.role = ".$roleid;
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}

	function userFieldCanDelete($field){
		$db = &$this->getDBO();

		$query = "SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_userfieldvalues')." WHERE field = ". $country->field."	AS total ";
		echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}
	
	function countryCanDelete($countryid){
		$db = &$this->getDBO();

		$query = "SELECT code FROM ".$db->nameQuote('#__js_job_countries')."	WHERE id = ".$countryid;
		$db->setQuery($query);
		$country = $db->loadObject();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_companies')." WHERE country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE nationality = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address1_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address2_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute1_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute2_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute3_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer1_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer2_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer3_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference1_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference2_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference3_country = ". $db->Quote($country->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_states')." WHERE countrycode = ". $db->Quote($country->code) .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}
	function stateCanDelete($stateid){
		$db = &$this->getDBO();

		$query = "SELECT code FROM ".$db->nameQuote('#__js_job_states')."	WHERE id = ".$stateid;
		$db->setQuery($query);
		$state = $db->loadObject();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_companies')." WHERE state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address1_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address2_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute1_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute2_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute3_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer1_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer2_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer3_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference1_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference2_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference3_state = ". $db->Quote($state->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_counties')." WHERE statecode = ". $db->Quote($state->code) .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}
	function countyCanDelete($countyid){
		$db = &$this->getDBO();

		$query = "SELECT code FROM ".$db->nameQuote('#__js_job_counties')."	WHERE id = ".$countyid;
		$db->setQuery($query);
		$county = $db->loadObject();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_companies')." WHERE county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address1_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address2_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute1_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute2_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute3_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer1_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer2_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer3_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference1_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference2_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference3_county = ". $db->Quote($county->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_cities')." WHERE countycode = ". $db->Quote($county->code) .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}
	function cityCanDelete($cityid){
		$db = &$this->getDBO();

		$query = "SELECT code FROM ".$db->nameQuote('#__js_job_cities')."	WHERE id = ".$cityid;
		$db->setQuery($query);
		$city = $db->loadObject();

		$query = "SELECT 
					( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_jobs')." WHERE city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_companies')." WHERE city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address1_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE address2_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute1_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute2_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE institute3_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer1_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer2_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE employer3_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference1_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference2_city = ". $db->Quote($city->code) .") 
					+ ( SELECT COUNT(id) FROM ".$db->nameQuote('#__js_job_resume')." WHERE reference3_city = ". $db->Quote($city->code) .") 
					AS total ";
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		$total = $db->loadResult();
		
		if ($total > 0)
			return false;
		else
			return true;
	}

	function storeCompany() //store company
	{
		$row = &$this->getTable('company');
		$data = JRequest :: get('post');
		
		if ( !$this->_comp_editor )
			$this->getConfig();
		if ($this->_comp_editor == 1){	
			$data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		}
		$returnvalue = 1;

		// For database
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
//		echo '<br> 4';
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$this->storeUserFieldData($data, $row->id);
		

		// For file upload
		$companyid = $row->id;
		if($_FILES['logo']['size'] > 0){ // logo
			$returnvalue = $this->uploadFile($companyid, 1, 0);
		}
		if($data['deletelogo'] == 1){ // delete logo
			$returnvalue = $this->uploadFile($companyid, 1, 1);
		}

		if($_FILES['smalllogo']['size'] > 0){ //small logo
			$returnvalue = $this->uploadFile($companyid, 2, 0);
		}
		if($data['deletesmalllogo'] == 1){ //delete small logo
			$returnvalue = $this->uploadFile($companyid, 2, 1);
		}

		if($_FILES['aboutcompany']['size'] > 0){ //about company
			$returnvalue = $this->uploadFile($companyid, 3, 0);
		}
		if($data['deleteaboutcompany'] == 1){ // delete about company
			$returnvalue = $this->uploadFile($companyid, 3, 1);
		}
		
		//if ($returnvalue != 1)
			return $returnvalue;
	}

	function storeJob()
	{
		$row = & $this->getTable('job');

		$data = JRequest :: get('post');

////////////		
		/* if ($this->_job_editor == 1){	*/
			$data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);
			$data['qualifications'] = JRequest::getVar('qualifications', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$data['prefferdskills'] = JRequest::getVar('prefferdskills', '', 'post', 'string', JREQUEST_ALLOWRAW);
		/* } */
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

		$this->storeUserFieldData($data, $row->id);

		return true;

	}

	function storeUserFieldData($data, $refid) //store  user field data
	{
		$row = &$this->getTable('userfielddata');
		//$data = JRequest :: get('post');
		//echo '<br>total field '.$data['userfields_total'];
		for($i = 1; $i <= $data['userfields_total']; $i++){
			$fname = "userfields_".$i;
			$fid = "userfields_".$i."_id";
			$dataid = "userdata_".$i."_id";
			//$fielddata['id'] = "";
			
			$fielddata['id'] = $data[$dataid];
			$fielddata['referenceid'] = $refid;
			$fielddata['field'] = $data[$fid];
			$fielddata['data'] = $data[$fname];
			//echo '<br> iiid '.$fielddata['id'];
			//echo '<br>field '.$fielddata['field'];
			//echo '<br>data '.$fielddata['data'];
	
//echo '<br> 3';
			if (!$row->bind($fielddata))	{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
//echo '<br> 4';
			if (!$row->store())	{
				$this->setError($this->_db->getErrorMsg());
				echo $this->_db->getErrorMsg();
				return false;
			}
			//echo '<br> id '.$row->id;
		}
		return true;
	}

	function storeResume()
	{
		$row = & $this->getTable('resume');

		$data = JRequest :: get('post');
		$data['resume'] = JRequest::getVar('resume', '', 'post', 'string', JREQUEST_ALLOWRAW);
//		$returnvalue = $this->uploadResume($resumedata['uid']);

		if($_FILES['resumefile']['size'] > 0){
			$file_name = $_FILES['resumefile']['name']; // file name
			$data['filename'] = $file_name;
			$data['filecontent'] = '';
		}else {
			if ($data['deleteresumefile'] == 1){
				$data['filename'] = '';
				$data['filecontent'] = '';
			}
		}		

		if($_FILES['photo']['size'] > 0){
			$file_name = $_FILES['photo']['name']; // file name
			$data['photo'] = $file_name;
		}else {
			if ($data['deleteresumefile'] == 1){
				$data['photo'] = '';
			}	
		}

/*
		if($_FILES['resumefile']['size'] > 0)
		{
			$fileName = $_FILES['resumefile']['name'];
			$tmpName  = $_FILES['resumefile']['tmp_name'];
			$fileSize = $_FILES['resumefile']['size'];
			$fileType = $_FILES['resumefile']['type'];

			
			$fp      = fopen($tmpName, 'r');
			$content = fread($fp, filesize($tmpName));
			$content = addslashes($content);
			fclose($fp);

			if(!get_magic_quotes_gpc())
			{
			    $fileName = addslashes($fileName);
			}
		}
		$data['filename'] = $fileName;
		$data['filetype'] = $fileType;
		$data['filesize'] = $fileSize;
		$data['filecontent'] = $content;
*/
		
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

		$returnvalue = $this->uploadResume($row->id);
		$returnvalue = $this->uploadPhoto($row->id);
		return true;

	}

	function uploadResume($id)
	{
		global $resumedata ;
		$db =& JFactory::getDBO();
		$str=JPATH_BASE;
		$base = substr($str, 0,strlen($str)-14); //remove administrator
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
				if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf"))
					return 6; //file type mistmathc
/*
				if( !(($file_type=="application/msword") || ($file_type=="text/plain")) )
				{
					return 6; //file type mistmathc
				}
*/			}

			$path =$base.'/components/com_jsjobs/data/jobseeker';
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
			$files = glob($userpath.'/*.*');
			array_map('unlink', $files);  //delete all file in user directory
			
			move_uploaded_file($file_tmp, $userpath.'/' . $file_name);
			//unlink($file_tmp);
			
//			$resumedata['filename'] = $file_name;
//			$resumedata['filecontent'] = '';
			return 1;
		}else {
			if ($resumedata['deleteresumefile'] == 1){
				$path =$base.'/components/com_jsjobs/data/jobseeker';
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
		global $resumedata ;
		$db =& JFactory::getDBO();
		$str=JPATH_BASE;
		$base = substr($str, 0,strlen($str)-14); //remove administrator
		
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

			$path =$base.'/components/com_jsjobs/data/jobseeker';
//echo '<br> path '.$path;
			if (!file_exists($path)){ // creating resume directory
				mkdir($path, 0755);
			}
			$userpath= $path . '/'.$iddir;
			if (!file_exists($userpath)){ // create user directory
				mkdir($userpath, 0755);
			}
			$userpath= $path . '/'.$iddir.'/photo';
//echo '<br> userpath '.$userpath;
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
				$path =$base.'/components/com_jsjobs/data/jobseeker';
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

	function storeCategory()
	{
		$row = & $this->getTable('category');

		$data = JRequest :: get('post');
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
		if ($data['id'] == ''){ // only for new
			$result=$this->isCategoryExist($data['cat_title']);
			if ($result == true)
			{
				//echo '<br> cat exist';
				return 3;
			}
		}
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;

	}

	function storeJobType()
	{
		$row = & $this->getTable('jobtype');

		$data = JRequest :: get('post');
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function storeJobStatus()
	{
		$row = & $this->getTable('jobstatus');

		$data = JRequest :: get('post');
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function storeShift()
	{
		$row = & $this->getTable('shift');

		$data = JRequest :: get('post');
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function storeHighestEducation()
	{
		$row = & $this->getTable('highesteducation');

		$data = JRequest :: get('post');
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function storeSalaryRange()
	{
		$row = & $this->getTable('salaryrange');

		$data = JRequest :: get('post');
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
		if ($data['id'] == ''){ // only for new
//			$result=$this->SalaryRangeValidations($data['cat_title']);
			if ($result == true)
			{
				//echo '<br> cat exist';
				return 3;
			}
		}
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;

	}

	function storeRole()
	{
		$row = & $this->getTable('role');

		$data = JRequest :: get('post');
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

		return true;

	}

	function storeUserRole()
	{
		$row = & $this->getTable('userrole');

		$data = JRequest :: get('post');
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

		return true;

	}

	function storeUserField()
	{
		echo '<br> store user field';

		$db =& JFactory::getDBO();
		$row = & $this->getTable('userfield');

		$data = JRequest :: get('post');
		
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return 2;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// add in field ordering 
		if ($data['id'] == '') { // only for new
			$query ="INSERT INTO #__js_job_fieldsordering 
					(field, fieldtitle, ordering, section, fieldfor, published, sys, cannotunpublish)
					VALUES(". $row->id .",'". $data['title'] ."', ( SELECT max(ordering)+1 FROM #__js_job_fieldsordering AS field WHERE fieldfor = " . $data['fieldfor'] . "), ''
					, " . $data['fieldfor'] . "," . $data['published'] . " ,0,0)
			";
			echo '<br>sql '.$query;
			$db->setQuery( $query );
			if (!$db->query()) {
				return false;
			}
		}

		// store values
		$names = $data['jsNames'];
		$values = $data['jsValues'];
		$fieldvaluerow = & $this->getTable('userfieldvalue');
		
		for ($i=0; $i <= $data['valueCount'];$i++) {
		
			$fieldvaluedata = array();
			$fieldvaluedata['id'] = '';
			$fieldvaluedata['field'] = $row ->id;
			$fieldvaluedata['fieldtitle'] = $names[$i];
			$fieldvaluedata['fieldvalue'] = $values[$i];
			$fieldvaluedata['ordering'] = $i + 1;		
			$fieldvaluedata['sys'] = 0;
			
			if (!$fieldvaluerow->bind($fieldvaluedata))	{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			if (!$fieldvaluerow->store())	{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			echo '<br>name '.$names[$i];
			echo '<br>value '.$values[$i];
		
		}

		return true;

	}

	function storeConfig()
	{
		$row = & $this->getTable('config');

		$data = JRequest :: get('post');
		$config = array();
		
			foreach ($data as $key=>$value){
				//echo "<br>".$key;
				//echo $value;
				$config['configname'] = $key;
				$config['configvalue'] = $value;
				if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
				if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
		   }
		
		/*
		// SITE SETTINGS
		$config['configname'] = 'title';
		$config['configvalue'] = $data['title'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
	
		$config['configname'] = 'jobautoapprove';
		$config['configvalue'] = $data['jobautoapprove'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
	
		$config['configname'] = 'empautoapprove';
		$config['configvalue'] = $data['empautoapprove'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'defaultempallow';
		$config['configvalue'] = $data['defaultempallow'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'defaultjoballow';
		$config['configvalue'] = $data['defaultjoballow'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'currency';
		$config['configvalue'] = $data['currency'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'mailfromname';
		$config['configvalue'] = $data['mailfromname'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'mailfromaddress';
		$config['configvalue'] = $data['mailfromaddress'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'newdays';
		$config['configvalue'] = $data['newdays'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}


		$config['configname'] = 'jpsalaryrange';
		$config['configvalue'] = $data['jpsalaryrange'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'jpqualification';
		$config['configvalue'] = $data['jpqualification'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'resumeaddress';
		$config['configvalue'] = $data['resumeaddress'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'resumeeducation';
		$config['configvalue'] = $data['resumeeducation'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'resumeemployer';
		$config['configvalue'] = $data['resumeemployer'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}

		$config['configname'] = 'theme';
		$config['configvalue'] = $data['theme'];
		if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
		if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
*/
		return true;

	}

	function storeEmailTemplate()
	{
		$row = & $this->getTable('emailtemplate');

		$data = JRequest :: get('post');
		$data['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;

	}

	function storeCountry()
	{
		$row = & $this->getTable('country');

		$data = JRequest :: get('post');
		if (!$data['id']){ // only for new
			$existvalue = $this->isCountryExist($data['name']);
			if ($existvalue == true) return 3; 
			$returnvalue = $this->makeCountryCode($data['name']);
			if ($returnvalue == false) return false;
			else $code = $returnvalue;
		}
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$data['id'])	$row->code = $code;
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;

	}
	
	function storeState()
	{
		$row = & $this->getTable('state');

		$data = JRequest :: get('post');
		if (!$data['id']){ // only for new
			$existvalue = $this->isStateExist($data['name'], $data['countrycode']);
			if ($existvalue == true) return 3; 
			$returnvalue = $this->makeStateCode($data['name'], $data['countrycode']);
			if ($returnvalue == false) return false;
			else $code = $returnvalue;
		}
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$data['id'])	$row->code = $code;
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	function storeCounty()
	{
		$row = & $this->getTable('county');

		$data = JRequest :: get('post');
		if (!$data['id']){ // only for new
			$existvalue = $this->isCountyExist($data['name'], $data['statecode']);
			if ($existvalue == true) return 3; 
			$returnvalue = $this->makeCountyCode($data['name'], $data['statecode']);
			if ($returnvalue == false) return false;
			else $code = $returnvalue;
		}
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$data['id'])	$row->code = $code;
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	function storeCity()
	{
		$row = & $this->getTable('city');

		$data = JRequest :: get('post');
		if (!$data['id']){ // only for new
			$existvalue = $this->isCityExist($data['name'], $data['countycode']);
			if ($existvalue == true) return 3; 
			$returnvalue = $this->makeCityCode($data['name'], $data['countycode']);
			if ($returnvalue == false) return false;
			else $code = $returnvalue;
		}
		if (!$row->bind($data))	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$data['id'])	$row->code = $code;
		if (!$row->store())	{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function loadAddressData()
	{
		$db =& JFactory::getDBO();
		$data = JRequest :: get('post');
		$str=JPATH_BASE;
		$base = substr($str, 0,strlen($str)-14); //remove administrator
		$returncode = 1;
		if ($data['actiontype'] == 1){ // first time
			if($_FILES['loadaddressdata']['size'] > 0){
				$file_name = $_FILES['loadaddressdata']['name']; // file name
				$file_tmp = $_FILES['loadaddressdata']['tmp_name']; // actual location
				$file_size = $_FILES['loadaddressdata']['size']; // file size
				$file_type = $_FILES['loadaddressdata']['type']; // mime type of file determined by php
				$file_error = $_FILES['loadaddressdata']['error']; // any error!. get reason here

				if( !empty($file_tmp)){	// only MS office and text file is accepted.
					$ext = $this->getExtension($file_name);
					if (($ext != "txt") && ($ext != "sql") )
						return 3; //file type mistmathc
				}

				$path =$base.'/components/com_jsjobs/data';
//				echo 'path '.$path;
				if (!file_exists($path)){ // creating data directory
					mkdir($path, 0755);
				}

				$path =$base.'/components/com_jsjobs/data/temp';
//				echo 'path '.$path;
				if (!file_exists($path)){ // creating temp directory
					mkdir($path, 0755);
				}
				$comp_filename = $path.'/' . $file_name;
				move_uploaded_file($file_tmp, $path.'/' . $file_name);
				
				$myFile = $comp_filename;
				
				$fh = fopen($myFile, 'r');
				$theData = fread($fh, filesize($myFile));
				fclose($fh);
//				echo '<br>'.$theData.'<br>';
				
				$start = strpos($theData,'###CTYST',0);
				$end  = strpos($theData,'###CTYED',0); 				
				$start = $start + 9;
//				echo '<br> start '.$start;
//				echo '<br> end '.$end;
				$len  = $end - $start;
				$country = substr($theData,$start,$len);
//				echo '<br> country '.$country;	
				
				$prtstart = strpos($theData,'###PRTST',0);
				$prtend  = strpos($theData,'###PRTED',0); 				
				$prtstart = $prtstart + 9;
//				echo '<br> start '.$prtstart;
//				echo '<br> end '.$prtend;
				$prtlen  = $prtend - $prtstart;
				$prt = substr($theData,$prtstart,$prtlen);
//				echo '<br> prt '.$prt;	

				//if ($country == '' ) return 3;
				if ($prt == 1){
					$query = "SELECT count(id) FROM ".$db->nameQuote('#__js_job_country') ." WHERE countrycode = ".$db->Quote($country);
					$db->setQuery( $query );
					$countryresult = $db->loadResult();

					$query = "SELECT count(id) FROM ".$db->nameQuote('#__js_job_states') ." WHERE countrycode = ".$db->Quote($country);
					$db->setQuery( $query );
					$stateresult = $db->loadResult();
					if ($stateresult != 0) $returncode = 5;
					
					$query = "SELECT count(id) FROM ".$db->nameQuote('#__js_job_counties') ." WHERE countrycode = ".$db->Quote($country);
					$db->setQuery( $query );
					$countyresult = $db->loadResult();
					if ($countyresult != 0){
						if ($returncode != 0) $returncode = 11;
						else $returncode = 7;
					}
					$query = "SELECT count(id) FROM ".$db->nameQuote('#__js_job_cities') ." WHERE countrycode = ".$db->Quote($country);
					$db->setQuery( $query );
					$cityresult = $db->loadResult();
					if ($cityresult != 0){
						if ($returncode != 0) $returncode = $returncode + 1;
						else $returncode = 7;
						
					}
				}	
				if($returncode == 1){		
					$db->setQuery($theData);
					if ( $result = $db->queryBatch())
						return 1;
					else{
						return 2;
					}
				}else{
					$_SESSION['js_address_data_filename'] = $myFile;
				}
				/*			
							$handle = fopen($myFile, 'r');
				while (!feof($handle))
				{
				$Data = fgets($handle, 256);
				print $Data;
				print "<p>";


				}
				fclose($handle); 
					$lines = file($myFile);
					foreach ($lines as $line_num => $line)
					{
					print "<font color=red>Line #{$line_num}</font> : " . $line . "<br />\n";
					}
				*/
				return $returncode;
			}
			
		}elseif($data['actiontype'] == 3){ // delete and insert	
			$myFile = $_SESSION['js_address_data_filename'];
			$fh = fopen($myFile, 'r');
			$theData = fread($fh, filesize($myFile));
			fclose($fh);
//			echo '<br>'.$theData.'<br>';

			$start = strpos($theData,'###CTYST',0);
			$end  = strpos($theData,'###CTYED',0); 				
			$start = $start + 9;
			$len  = $end - $start;
			$country = substr($theData,$start,$len);
			
			$countrydata = strpos($theData,'### COUNTRY ###',0);
			$statesdata = strpos($theData,'### STATES ###',0);
			$countiesdata = strpos($theData,'### COUNTIES ###',0);
			$citiesdata = strpos($theData,'### CITIES ###',0);
			
			if ($countrydata != 0){ // country data exist
				$query = "DELETE FROM ".$db->nameQuote('#__js_job_country') ." WHERE countrycode = ".$db->Quote($country);
				$db->setQuery( $query );
				$db->query();
			}
			if ($statesdata != 0) { //stats exist
				$query = "DELETE FROM ".$db->nameQuote('#__js_job_states') ." WHERE countrycode = ".$db->Quote($country);
				$db->setQuery( $query );
				$db->query();
			}
			if ($countiesdata != 0) { //counties exist
				$query = "DELETE FROM ".$db->nameQuote('#__js_job_counties') ." WHERE countrycode = ".$db->Quote($country);
				$db->setQuery( $query );
				$db->query();
			}
			if ($citiesdata != 0) { //citiesexist
				$query = "DELETE FROM ".$db->nameQuote('#__js_job_cities') ." WHERE countrycode = ".$db->Quote($country);
				$db->setQuery( $query );
				$db->query();
			}
			$db->setQuery($theData);
			if ( $result = $db->queryBatch())
				return 1;
			else{
				return 2;
			}
	
		}elseif($data['actiontype'] == 4){ // insert	
			$myFile = $_SESSION['js_address_data_filename'];
			$fh = fopen($myFile, 'r');
			$theData = fread($fh, filesize($myFile));
			fclose($fh);
//			echo '<br>'.$theData.'<br>';
			$db->setQuery($theData);
			if ( $result = $db->queryBatch())
				return 1;
			else{
				return 2;
			}
		
		}
		

	}
	
	function isCountryExist($country)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(id) FROM #__js_job_countries WHERE name = ".$db->Quote($country);
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($result == 0)
			return false;
		else
			return true;
	}
	function isStateExist($state, $countrycode)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(id) FROM #__js_job_states WHERE name = ".$db->Quote($state)." AND countrycode = ".$db->Quote($countrycode);
		echo '<br> SQL '.$query;
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($result == 0)
			return false;
		else
			return true;
	}
	function isCountyExist($county, $statecode)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(id) FROM #__js_job_counties WHERE name = ".$db->Quote($county)." AND statecode = ".$db->Quote($statecode);
		echo '<br> SQL '.$query;
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($result == 0)
			return false;
		else
			return true;
	}
	function isCityExist($city, $countycode)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT COUNT(id) FROM #__js_job_cities WHERE name = ".$db->Quote($city)." AND countycode = ".$db->Quote($countycode);
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($result == 0)
			return false;
		else
			return true;
	}

	function makeCountryCode($country)
	{
		$db =& JFactory::getDBO();
		$code = substr($country, 0, 2); 
		$found = false;
		$start = 1;
		while (!$found == true){
			$query = "SELECT COUNT(id) FROM #__js_job_countries WHERE code = ".$db->Quote($code);
			$db->setQuery( $query );
			$result = $db->loadResult();
			if ($result == 0){
				$found = true;
				return $code;
			}else {
				$code = substr($country, $start, 2); 
				$start++;
				if($start == strlen($country))	return false;	
			}	
		}
	}

	function makeStateCode($stat, $countrycode)
	{
		$db =& JFactory::getDBO();
		$state = str_replace (" ", "", $stat); // remove spaces
		$totallen = strlen($state);
		$len = 4;
		if ($len > $totallen) $len = $totallen;
		$code = substr($state, 0, $len); 
		$found = false;
		$start = 0;
		while (!$found == true){
			$query = "SELECT COUNT(id) FROM #__js_job_states WHERE code = ".$db->Quote($code);
			$db->setQuery( $query );
			$result = $db->loadResult();
			if ($result == 0){
				$found = true;
				return $code;
			}else {
				$code = substr($state, 0, $len); 
				if($len == $totallen) $code .= $countrycode;
				if($len > $totallen)	return false;	
				$len++;
			}	
		}
	}

	function makeCountyCode($count, $statecode)
	{
		$db =& JFactory::getDBO();
		$county = str_replace (" ", "", $count); // remove spaces
		$totallen = strlen($county);
		$len = 4;
		if ($len > $totallen) $len = $totallen;
		$code = substr($county, 0, $len); 
		$found = false;
		$start = 0;
		while (!$found == true){
			$query = "SELECT COUNT(id) FROM #__js_job_counties WHERE code = ".$db->Quote($code);
			$db->setQuery( $query );
			$result = $db->loadResult();
			if ($result == 0){
				$found = true;
				return $code;
			}else {
				$code = substr($county, 0, $len); 
				if($len == $totallen){
					$county .= $statecode;
					$totallen = strlen($county);
					$code = substr($county, 0, $len); 
				}	
				if($len > $totallen)	return false;	
				$len++;
			}	
		}
	}

	function makeCityCode($cit, $countycode)
	{
		$db =& JFactory::getDBO();
		$city = str_replace (" ", "", $cit); // remove spaces
		$totallen = strlen($city);
		$len = 4;
		if ($len > $totallen) $len = $totallen;
		$code = substr($city, 0, $len); 
		$found = false;
		$start = 0;
		while (!$found == true){
			$query = "SELECT COUNT(id) FROM #__js_job_cities WHERE code = ".$db->Quote($code);
			$db->setQuery( $query );
			$result = $db->loadResult();
			if ($result == 0){
				$found = true;
				return $code;
			}else {
				$code = substr($city, 0, $len); 
				if($len == $totallen){
					$city .= $countycode;
					$totallen = strlen($city);
					$code = substr($city, 0, $len); 
				}	
				if($len > $totallen)	return false;	
				$len++;
			}	
		}
	}

	function uploadFile($id, $action, $isdeletefile)
	{
		$db =& JFactory::getDBO();

		$str=JPATH_BASE;
		$base = substr($str, 0,strlen($str)-14); //remove administrator
		$path =$base.'/components/com_jsjobs/data/employer';
		echo '<br> path '.$path;
		
		$isupload = false;
		if ($action == 1) { //Company logo
			if($_FILES['logo']['size'] > 0){
				$file_name = $_FILES['logo']['name']; // file name
				$file_tmp = $_FILES['logo']['tmp_name']; // actual location
				
		echo '<br> file_name '.$file_name;
				$ext = $this->getExtension($file_name);
		echo '<br> ext '.$ext;
				$ext = strtolower($ext);
				if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
					return 6; //file type mistmathc
					
		echo '<br> action '.$action;
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
				if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf"))
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

	function companyApprove($company_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE #__js_job_companies SET status = 1 WHERE id = ".$company_id;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return $this->sendMail(1, 1, $company_id);
	}

	function companyReject($company_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE #__js_job_companies SET status = -1 WHERE id = ".$company_id;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return $this->sendMail(1, -1, $company_id);
	}

	function jobApprove($job_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE #__js_job_jobs SET status = 1 WHERE id = ".$db->Quote($job_id);
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return $this->sendMail(2, 1, $job_id);
		//return true;
	}

	function jobReject($job_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE #__js_job_jobs SET status = -1 WHERE id = ".$db->Quote($job_id);
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return $this->sendMail(2, -1, $job_id);
		//return true;
	}

	function empappApprove($app_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE #__js_job_resume SET status = 1 WHERE id = ".$db->Quote($app_id);
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return $this->sendMail(3, 1, $app_id);
		//return true;
	}

	function empappReject($app_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE #__js_job_resume SET status = -1 WHERE id = ".$db->Quote($app_id);
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return $this->sendMail(3, -1, $app_id);
		//return true;
	}

	function fieldPublished($field_id, $value)
	{
		$db =& JFactory::getDBO();
		
		$query = " UPDATE #__js_job_fieldsordering
					SET published = ". $value ."
					WHERE id = ". $field_id ;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
	}

	function fieldOrderingUp($field_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE #__js_job_fieldsordering AS f1, #__js_job_fieldsordering AS f2
					SET f1.ordering = f1.ordering - 1
					WHERE f1.ordering = f2.ordering + 1
					AND f1.fieldfor = f2.fieldfor
					AND f2.id = ". $field_id ." ; " ;
		$db->setQuery( $query );
		if (!$db->query()) {return false;	}
		
		$query = " UPDATE #__js_job_fieldsordering
					SET ordering = ordering + 1
					WHERE id = ". $field_id .";"
					;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
	}

	function fieldOrderingDown($field_id)
	{
		$db =& JFactory::getDBO();
		
		$query = "UPDATE jos_js_job_fieldsordering AS f1, jos_js_job_fieldsordering AS f2
					SET f1.ordering = f1.ordering + 1
					WHERE f1.ordering = f2.ordering - 1
					AND f1.fieldfor = f2.fieldfor
					AND f2.id = ". $field_id ." ; ";

		$db->setQuery( $query );
		if (!$db->query()) {return false;	}
		
		$query = " UPDATE #__js_job_fieldsordering
					SET ordering = ordering - 1
					WHERE id = ". $field_id .";"	;
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
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
				$return_value = "<select name='country' onChange=\"dochange('state', this.value)\">\n";
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

	function & getApplication()
	{
		if (!$this->_application && $this->_id != null)
		{
			$db = & $this->getDBO();
			$query = "SELECT * FROM " . $db->nameQuote('#__js_job_jobs') . " WHERE " .
			$db->nameQuote('id') . " = " . $this->_id;
			$db->setQuery($query);
			$this->_application = $db->loadObject();
			$this->getOptions();
		}
		return $this->_application;
	}

	// save the current data
	function save($data)
	{
	$table = & $this->getTable('application');
		if (!$table->save($data))
		{
			$this->setError($table->getError());
			return false;
		}
		return true;
	}

	// increment the hit counter <<---- not used
	function hit()
	{
		$db = & JFactory :: getDBO();
		$db->setQuery("UPDATE " . $db->nameQuote('#_at_jp_jobs') . " SET " .
		$db->nameQuote('hits') . " = " . $db->nameQuote('hits') . " + 1 " .
		"WHERE id = " . $this->_id);
		$db->query();
	}
	
	function & getUid()
	{
		$this->_uid = $this->randomString(30); 
		return $this->_uid;
	}
	

	function isCategoryExist($cat_title)
	{
		$db =& JFactory::getDBO();
		
		$query = "SELECT COUNT(id) FROM #__js_job_categories WHERE cat_title = ".$db->Quote($cat_title);
		//echo '<br>sql '.$query;
		$db->setQuery( $query );
		$result = $db->loadResult();
		//echo '<br>r'.$result;
		if ($result == 0)
			return false;
		else
			return true;
			
	}

	function SalaryRangeValidation($rangestart, $rangeend)
	{
		$db =& JFactory::getDBO();
		
		$query = "SELECT COUNT(id) FROM #__js_job_categories WHERE cat_title = ".$db->Quote($cat_title);
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
	function &sendMail($for, $action, $id)
	{
		//action			1 = job approved, 2 = job reject 6, resume approved, 7 resume reject
		$db =& JFactory::getDBO();
		$str = JURI::base();
		if (strtolower(substr($str, strlen($str)-14, 13)) == "administrator")		
			$siteAddress = substr($str, 0,strlen($str)-14); //remove administrator
		else
			$siteAddress = $str;
			

		if ($for == 1 ){ //company
			if ($action == 1){ // company approved
				$templatefor = 'company-approval';
			}elseif ($action == -1){ //company reject
				$templatefor = 'company-rejecting';
			}
		}elseif ($for == 2 ){ //job
			if ($action == 1){ // job approved
				$templatefor = 'job-approval';
			}elseif ($action == -1){ // job reject
				$templatefor = 'job-rejecting';
			}
		}elseif ($for == 3 ){ // resume
			if ($action == 1){ //resume approved
				$templatefor = 'resume-approval';
			}elseif ($action == -1){ // resume reject
				$templatefor = 'resume-rejecting';
			}
		}
		
		$query = "SELECT template.* FROM ".$db->nameQuote('#__js_job_emailtemplates') ."AS template	WHERE template.templatefor = ".$db->Quote($templatefor);
		$db->setQuery( $query );
		$template = $db->loadObject();
		$msgSubject = $template->subject;
		$msgBody = $template->body;

		if ($for == 1 ){ //company
			$query = "SELECT company.name, company.contactname, company.contactemail FROM ".$db->nameQuote('#__js_job_companies') ."AS company
				WHERE company.id = ".$id;
			//echo '<br>sql '.$query;	

			$db->setQuery( $query );
			$company = $db->loadObject();

			$Name=$company->contactname;
			$Email=$company->contactemail;
			$companyName=$company->name;
			
			$msgSubject = str_replace('{COMPANY_NAME}', $companyName, $msgSubject);
			$msgSubject = str_replace('{EMPLOYER_NAME}', $Name, $msgSubject);
			$msgBody = str_replace('{COMPANY_NAME}', $companyName, $msgBody);
			$msgBody = str_replace('{EMPLOYER_NAME}', $Name, $msgBody);
/*
			if ($action == 1){ // company approved
				$msgSubject = " Your Company $companyTitle has been approved";
				$msgBody="Dear  $Name , \n\n Your company <b>$companyTitle</b> has been approved.\n Login and view detail at $siteAddress  \n\nPlease do not respond to this message. It is automatically generated and is for information purposes only.";
			}elseif ($action == -1){ //company reject
				$msgSubject = "Your company $companyTitle has been rejected";
				$msgBody="Dear  $Name , \n\n Your company <b>$companyTitle</b> has been rejected.\n Login and view detail at $siteAddress  \n\nPlease do not respond to this message. It is automatically generated and is for information purposes only.";
			}
*/
		}elseif ($for == 2 ){ //job
			$query = "SELECT job.title, company.contactname, company.contactemail 
						FROM ".$db->nameQuote('#__js_job_jobs') ."AS job
						JOIN ".$db->nameQuote('#__js_job_companies') ."AS company ON job.companyid = company.id
				WHERE job.id = ".$id;
			//echo '<br>sql '.$query;	
			$db->setQuery( $query );
			$job = $db->loadObject();

			$Name=$job->contactname;
			$Email=$job->contactemail;
			$jobTitle=$job->title;
			$msgSubject = str_replace('{JOB_TITLE}', $jobTitle, $msgSubject);
			$msgSubject = str_replace('{EMPLOYER_NAME}', $Name, $msgSubject);
			$msgBody = str_replace('{JOB_TITLE}', $jobTitle, $msgBody);
			$msgBody = str_replace('{EMPLOYER_NAME}', $Name, $msgBody);			
/*			
			if ($action == 1){ // job approved
				$msgSubject = " Your job $jobTitle has been approved";
				$msgBody="Dear  $Name , \n\n Your job <b>$jobTitle</b> has been approved.\n Login and view detail at $siteAddress  \n\nPlease do not respond to this message. It is automatically generated and is for information purposes only.";
			}elseif ($action == -1){ // job reject
				$msgSubject = "Your job $jobTitle has been rejected";
				$msgBody="Dear  $Name , \n\n Your job <b>$jobTitle</b> has been rejected.\n Login and view detail at $siteAddress  \n\nPlease do not respond to this message. It is automatically generated and is for information purposes only.";
			}
*/
		}elseif ($for == 3 ){ // resume
			$query = "SELECT app.application_title, app.first_name, app.middle_name, app.last_name, app.email_address FROM ".$db->nameQuote('#__js_job_resume') ."AS app
				WHERE app.id = ".$id;
				
			//echo '<br>sql '.$query;		
			$db->setQuery( $query );
			$app = $db->loadObject();
			
			$Name=$app->first_name;
			if ($app->middle_name) $Name .= " ".$app->middle_name;
			if ($app->last_name) $Name .= " ".$app->last_name;
			$Email=$app->email_address;
			$resumeTitle=$app->application_title;
			$msgSubject = str_replace('{RESUME_TITLE}', $resumeTitle, $msgSubject);
			$msgSubject = str_replace('{JOBSEEKER_NAME}', $Name, $msgSubject);
			$msgBody = str_replace('{RESUME_TITLE}', $resumeTitle, $msgBody);
			$msgBody = str_replace('{JOBSEEKER_NAME}', $Name, $msgBody);			
/*
			if ($action == 1){ //resume approved
				$msgSubject = "Your resume $jobTitle has been approved";
				$msgBody="Dear  $Name , \n\n Your resume <b>$jobTitle</b> has been approved.\n Login and view detail at $siteAddress  \n\nPlease do not respond to this message. It is automatically generated and is for information purposes only.";
			}elseif ($action == -1){ // resume reject
				$msgSubject = "Your resume $jobTitle has been rejected";
				$msgBody="Dear  $Name , \n\n Your resume <b>$jobTitle</b> has been rejected.\n Login and view detail at $siteAddress  \n\nPlease do not respond to this message. It is automatically generated and is for information purposes only.";
			}
*/		
		}

			if ( !$this->_config )
				$this->getConfig();
			foreach ($this->_config as $conf){
				if ($conf->configname == 'mailfromname')
					$senderName = $conf->configvalue;
				if ($conf->configname == 'mailfromaddress')
					$senderEmail = $conf->configvalue;
			}

			$message =& JFactory::getMailer();
			$message->addRecipient($Email); //to email

			$message->setSubject($msgSubject);
			$message->setBody($msgBody);
			$sender = array( $senderEmail, $senderName );
			$message->setSender($sender);
			$message->IsHTML(true);
			$sent = $message->send();

			return true;
			//			return $sent;
			//if ($sent != 1) echo 'Error sending email';
 	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		function &getEmpOptions()
	{
		if ( !$this->_empoptions )
		{
			$this->_empoptions = array();

		$gender = array(
			'0' => array('value' => 1,'text' => JText::_('JS_MALE')),
			'1' => array('value' => 2,'text' => JText::_('JS_FEMALE')),);

		$status = array(
			'0' => array('value' => 0,'text' => JText::_('JS_PENDDING')),
			'1' => array('value' => 1,'text' => JText::_('JS_APPROVE')),
			'2' => array('value' => -1,'text' => JText::_('JS_REJECT')),);
		
			$job_type = $this->getJobType('');
			$heighesteducation = $this->getHeighestEducation('');
			$job_categories = $this->getCategories('','');
			$job_salaryrange = $this->getJobSalaryRange('','');
			$countries = $this->getCountries('');

			if(isset($this->_application))$address_states = $this->getStates($this->_application->address_country);
			if(isset($this->_application))$address_counties = $this->getCounties($this->_application->address_state);
			if(isset($this->_application))$address_cities = $this->getCities($this->_application->address_county);

			if(isset($this->_application))$institute_states = $this->getStates($this->_application->institute_country);
			if(isset($this->_application))$institute_counties = $this->getCounties($this->_application->institute_state);
			if(isset($this->_application))$institute_cities = $this->getCities($this->_application->institute_county);
			
			if(isset($this->_application))$institute1_states = $this->getStates($this->_application->institute1_country);
			if(isset($this->_application))$institute1_counties = $this->getCounties($this->_application->institute1_state);
			if(isset($this->_application))$institute1_cities = $this->getCities($this->_application->institute1_county);

			if(isset($this->_application))$employer_states = $this->getStates($this->_application->employer_country);
			if(isset($this->_application))$employer_counties = $this->getCounties($this->_application->employer_state);
			if(isset($this->_application))$employer_cities = $this->getCities($this->_application->employer_county);
			
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
				$this->_empoptions['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" '. '', 'value', 'text', $this->_application->status);


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
				
				$this->_empoptions['address1_country'] = JHTML::_('select.genericList', $countries, 'address1_country','class="inputbox" '.'onChange="dochange(\'address1_state\', \'address1_county\',\'address1_city\',\'state\', this.value)"', 'value', 'text', $this->_application->address1_country);
				$this->_empoptions['address2_country'] = JHTML::_('select.genericList', $countries, 'address2_country','class="inputbox" '.'onChange="dochange(\'address2_state\', \'address2_county\',\'address2_city\',\'state\', this.value)"', 'value', 'text', $this->_application->address2_country);

				$this->_empoptions['institute_country'] = JHTML::_('select.genericList', $countries, 'institute_country','class="inputbox" '.'onChange="dochange(\'institute_state\', \'institute_county\',\'institute_city\', \'state\', this.value)"', 'value', 'text', '');
				if ( isset($institute_states[1]) )if ($institute_states[1] != '')$this->_empoptions['institute_state'] = JHTML::_('select.genericList', $institute_states, 'institute_state', 'class="inputbox" '. 'onChange="dochange(\'institute_county\, \'institute_city\', , this.value)"', 'value', 'text', '');
				if ( isset($institute_counties[1]) )if ($institute_counties[1] != '')$this->_empoptions['institute_county'] = JHTML::_('select.genericList', $institute_counties, 'institute_county', 'class="inputbox" '. 'onChange="dochange(\'institute_city\,  , , this.value)"', 'value', 'text', '');
				if ( isset($institute_cities[1]) )if ($institute_cities[1] != '')$this->_empoptions['institute_city'] = JHTML::_('select.genericList', $institute_cities, 'institute_city', 'class="inputbox" '. '', 'value', 'text', '');

				$this->_empoptions['institute1_country'] = JHTML::_('select.genericList', $countries, 'institute1_country','class="inputbox" '.'onChange="dochange(\'institute1_state\', \'institute1_county\',\'institute1_city\',\'state\', this.value)"', 'value', 'text', '');
				if ($institute1_states[1] != '')$this->_empoptions['institute1_state'] = JHTML::_('select.genericList', $institute1_states, 'institute1_state', 'class="inputbox" '. 'onChange="dochange(\'institute1_county\, \'institute1_city\', , this.value)"', 'value', 'text', '');
				if ($institute1_counties[1] != '')$this->_empoptions['institute1_county'] = JHTML::_('select.genericList', $institute1_counties, 'institute1_county', 'class="inputbox" '. 'onChange="dochange(\'institute1_city\,  , , this.value)"', 'value', 'text', '');
				if ($institute1_cities[1] != '')$this->_empoptions['institute1_city'] = JHTML::_('select.genericList', $institute1_cities, 'institute1_city', 'class="inputbox" '. '', 'value', 'text', '');

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
				$this->_empoptions['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" '. '', 'value', 'text', '');
			}
		}
		return $this->_empoptions;
	}	
	

	
/*
	function &getEmpOptions()
	{
		if ( !$this->_empoptions )
		{
			$this->_empoptions = array();
			$job_type = array(
				'0' => array('value' => JText::_(1),
								'text' => JText::_('JS_JOBTYPE_FULLTIME')),
				'1' => array('value' => JText::_(2),
								'text' => JText::_('JS_JOBTYPE_PARTTIME')),
				'3' => array('value' => JText::_(3),
								'text' => JText::_('JS_JOBTYPE_INTERNSHIP')),);
		

			$heighesteducation = array(
				'0' => array('value' => JText::_(1),
								'text' => JText::_('JS_JOBEDUCATION_UNIVERSITY')),
				'1' => array('value' => JText::_(2),
								'text' => JText::_('JS_JOBEDUCATION_COLLEGE')),
				'2' => array('value' => JText::_(2),
								'text' => JText::_('JS_JOBEDUCATION_HIGH_SCHOOL')),
				'3' => array('value' => JText::_(3),
								'text' => JText::_('JS_JOBEDUCATION_NO_SCHOOL')),);
			
			$job_categories = $this->getCategories('','');
			$job_salaryrange = $this->getJobSalaryRange('','');
			$countries = $this->getCountries('');

			$address_states = $this->getStates($this->_application->address_country);
			$address_counties = $this->getCounties($this->_application->address_state);
			$address_cities = $this->getCities($this->_application->address_county);

			$institute_states = $this->getStates($this->_application->institute_country);
			$institute_counties = $this->getCounties($this->_application->institute_state);
			$institute_cities = $this->getCities($this->_application->institute_county);

			$employer_states = $this->getStates($this->_application->employer_country);
			$employer_counties = $this->getCounties($this->_application->employer_state);
			$employer_cities = $this->getCities($this->_application->employer_county);
			//++++++++++++++++++++++++++++++++++++++++++++++++++++++
			
			if ( isset($this->application) )
			{
				$this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" '. '', 'value', 'text', $this->_application->job_category);
				$this->_empoptions['address_country'] = JHTML::_('select.genericList', $countries, 'address_country','class="inputbox" '.'onChange="dochange(\'address_state\', \'address_county\',\'address_city\', \'state\', this.value)"', 'value', 'text', $this->_application->address_country);
				if(isset($address_states[1])) if ($address_states[1] != '')$this->_empoptions['address_state'] = JHTML::_('select.genericList', $address_states, 'address_state', 'class="inputbox" '. 'onChange="dochange(\'address_county\, \'address_city\', , this.value)"', 'value', 'text', $this->_application->address_state);
				if(isset($address_counties[1])) if ($address_counties[1] != '')$this->_empoptions['address_county'] = JHTML::_('select.genericList', $address_counties, 'address_county', 'class="inputbox" '. 'onChange="dochange(\'address_city\, , , this.value)"', 'value', 'text', $this->_application->address_county);
				if(isset($address_cities[1])) if ($address_cities[1] != '')$this->_empoptions['address_city'] = JHTML::_('select.genericList', $cities, 'address_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->address_city);
				
				$this->_empoptions['institute_country'] = JHTML::_('select.genericList', $countries, 'institute_country','class="inputbox" '.'onChange="dochange(\'institute_state\', \'institute_county\',\'institute_city\', \'state\', this.value)"', 'value', 'text', $this->_application->institute_country);
				if(isset($institute_states[1])) if ($institute_states[1] != '')$this->_empoptions['institute_state'] = JHTML::_('select.genericList', $institute_states, 'institute_state', 'class="inputbox" '. 'onChange="dochange(\'institute_county\, \'institute_city\', , this.value)"', 'value', 'text', $this->_application->institute_state);
				if(isset($institute_counties[1])) if ($institute_counties[1] != '')$this->_empoptions['institute_county'] = JHTML::_('select.genericList', $institute_counties, 'institute_county', 'class="inputbox" '. 'onChange="dochange(\'institute_city\,  , , this.value)"', 'value', 'text', $this->_application->institute_county);
				if(isset($institute_cities[1])) if ($institute_cities[1] != '')$this->_empoptions['institute_city'] = JHTML::_('select.genericList', $institute_cities, 'institute_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->institute_city);

				$this->_empoptions['employer_country'] = JHTML::_('select.genericList', $countries, 'employer_country','class="inputbox" '.'onChange="dochange(\'employer_state\', \'employer_county\',\'employer_city\',\'state\', this.value)"', 'value', 'text', $this->_application->employer_country);
				if(isset($employer_states[1])) if ($employer_states[1] != '')$this->_empoptions['employer_state'] = JHTML::_('select.genericList', $employer_states, 'employer_state', 'class="inputbox" '. 'onChange="dochange(\'employer_county\, \'employer_city\', , this.value)"', 'value', 'text', $this->_application->employer_state);
				if(isset($employer_counties[1])) if ($employer_counties[1] != '')$this->_empoptions['employer_county'] = JHTML::_('select.genericList', $employer_counties, 'employer_county', 'class="inputbox" '. 'onChange="dochange(\'employer_city\,  , , this.value)"', 'value', 'text', $this->_application->employer_county);
				if(isset($employer_cities[1])) if ($employer_cities[1] != '')$this->_empoptions['employer_city'] = JHTML::_('select.genericList', $employer_cities, 'employer_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->employer_city);

				$this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobtype);
				$this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', $this->_application->heighestfinisheducation);
				$this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobsalaryrange);
			}
			else
			{
				$this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" '. '', 'value', 'text', '3');
				$this->_empoptions['address_country'] = JHTML::_('select.genericList', $countries, 'address_country','class="inputbox" '.'onChange="dochange(\'address_state\', \'address_county\',\'address_city\', \'state\', this.value)"', 'value', 'text', $this->_application->address_country);
				//echo '<br> st '.$address_states[1];
				if(isset($address_states[1])) if ($address_states[1] != '')$this->_empoptions['address_state'] = JHTML::_('select.genericList', $address_states, 'address_state', 'class="inputbox" '. 'onChange="dochange(\'address_county\, \'address_city\', , this.value)"', 'value', 'text', $this->_application->address_state);
				if(isset($address_counties[1])) if ($address_counties[1] != '')$this->_empoptions['address_county'] = JHTML::_('select.genericList', $address_counties, 'address_county', 'class="inputbox" '. 'onChange="dochange(\'address_city\, , , this.value)"', 'value', 'text', $this->_application->address_county);
				if(isset($address_cities[1])) if ($address_cities[1] != '')$this->_empoptions['address_city'] = JHTML::_('select.genericList', $address_cities, 'address_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->address_city);
				
				$this->_empoptions['institute_country'] = JHTML::_('select.genericList', $countries, 'institute_country','class="inputbox" '.'onChange="dochange(\'institute_state\', \'institute_county\',\'institute_city\', \'state\', this.value)"', 'value', 'text', $this->_application->institute_country);
				if(isset($institute_states[1])) if ($institute_states[1] != '')$this->_empoptions['institute_state'] = JHTML::_('select.genericList', $institute_states, 'institute_state', 'class="inputbox" '. 'onChange="dochange(\'institute_county\, \'institute_city\', , this.value)"', 'value', 'text', $this->_application->institute_state);
				if(isset($institute_counties[1])) if ($institute_counties[1] != '')$this->_empoptions['institute_county'] = JHTML::_('select.genericList', $institute_counties, 'institute_county', 'class="inputbox" '. 'onChange="dochange(\'institute_city\,  , , this.value)"', 'value', 'text', $this->_application->institute_county);
				if(isset($institute_cities[1])) if ($institute_cities[1] != '')$this->_empoptions['institute_city'] = JHTML::_('select.genericList', $institute_cities, 'institute_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->institute_city);

				$this->_empoptions['employer_country'] = JHTML::_('select.genericList', $countries, 'employer_country','class="inputbox" '.'onChange="dochange(\'employer_state\', \'employer_county\',\'employer_city\',\'state\', this.value)"', 'value', 'text', $this->_application->employer_country);
				if(isset($employer_states[1])) if ($employer_states[1] != '')$this->_empoptions['employer_state'] = JHTML::_('select.genericList', $employer_states, 'employer_state', 'class="inputbox" '. 'onChange="dochange(\'employer_county\, \'employer_city\', , this.value)"', 'value', 'text', $this->_application->employer_state);
				if(isset($employer_counties[1])) if ($employer_counties[1] != '')$this->_empoptions['employer_county'] = JHTML::_('select.genericList', $employer_counties, 'employer_county', 'class="inputbox" '. 'onChange="dochange(\'employer_city\,  , , this.value)"', 'value', 'text', $this->_application->employer_county);
				if(isset($employer_cities[1])) if ($employer_cities[1] != '')$this->_empoptions['employer_city'] = JHTML::_('select.genericList', $employer_cities, 'employer_city', 'class="inputbox" '. '', 'value', 'text', $this->_application->employer_city);


				$this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobtype);
				$this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', $this->_application->heighestfinisheducation);
				$this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobsalaryrange);
			}
		}
		return $this->_empoptions;
	}	
*/	
	// load the options for our templates
	function &getOptions()
	{
		if ( !$this->_options )
		{
			$this->_options = array();
			$job_type = array(
				'0' => array('value' => JText::_(1),
								'text' => JText::_('JS_JOBTYPE_FULLTIME')),
				'1' => array('value' => JText::_(2),
								'text' => JText::_('JS_JOBTYPE_PARTTIME')),
				'3' => array('value' => JText::_(3),
								'text' => JText::_('JS_JOBTYPE_INTERNSHIP')),);
		

			$heighesteducation = array(
				'0' => array('value' => JText::_(1),
								'text' => JText::_('JS_JOBEDUCATION_UNIVERSITY')),
				'1' => array('value' => JText::_(2),
								'text' => JText::_('JS_JOBEDUCATION_COLLEGE')),
				'2' => array('value' => JText::_(2),
								'text' => JText::_('JS_JOBEDUCATION_HIGH_SCHOOL')),
				'3' => array('value' => JText::_(3),
								'text' => JText::_('JS_JOBEDUCATION_NO_SCHOOL')),);

			$jobstatus = array(
				'0' => array('value' => JText::_(1),
								'text' => JText::_('JS_JOBSTATUS_SOURCING')),
				'1' => array('value' => JText::_(2),
								'text' => JText::_('JS_JOBSTATUS_INTERVIEWING')),
				'2' => array('value' => JText::_(3),
								'text' => JText::_('JS_JOBSTATUS_CLOSED')),
				'3' => array('value' => JText::_(4),
								'text' => JText::_('JS_JOBSTATUS_FINALISTS')),
				'4' => array('value' => JText::_(5),
								'text' => JText::_('JS_JOBSTATUS_PENDING')),
				'5' => array('value' => JText::_(6),
								'text' => JText::_('JS_JOBSTATUS_HOLD')),);
								
			
			$job_categories = $this->getCategories('','');
			$job_salaryrange = $this->getJobSalaryRange('','');
			$countries = $this->getCountries('');
			if (isset($this->_application))$states = $this->getStates($this->_application->country);
			if (isset($this->_application))$counties = $this->getCounties($this->_application->state);
			if (isset($this->_application))$cities = $this->getCities($this->_application->county);
			if (isset($this->_application)){
				$this->_options['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobcategory);
				$this->_options['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobsalaryrange);
				$this->_options['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_application->country);
				if ( isset($states[1]) ) if ($states[1] != '')$this->_options['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', $this->_application->state);
				if ( isset($counties[1]) ) if ($counties[1] != '')$this->_options['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', $this->_application->county);
				if ( isset($cities[1]) ) if ($cities[1] != '')$this->_options['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', $this->_application->city);
				$this->_options['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobstatus);				
				$this->_options['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', $this->_application->jobtype);
				$this->_options['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', $this->_application->heighestfinisheducation);
			}else{
				$this->_options['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', '');
				if ( isset($states[1]) ) if ($states[1] != '')$this->_options['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'county\', this.value)"', 'value', 'text', '');
				if ( isset($counties[1]) ) if ($counties[1] != '')$this->_options['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
				if ( isset($cities[1]) ) if ($cities[1] != '')$this->_options['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" '. '', 'value', 'text', '');				
				$this->_options['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" '. '', 'value', 'text', '');
				$this->_options['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" '. '', 'value', 'text', '');
			}
		}
		return $this->_options;
	}
	
	function getCategories($title, $value  )
	{
		$db =& JFactory::getDBO();
		
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_categories')." WHERE isactive = 1 ORDER BY 'id' ";
//		echo '<br>sql '.$query;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$jobcategories = array();
		if ($title) 
			$jobcategories[] =  array('value' => JText::_($value),	'text' => JText::_($title));
		foreach($rows as $row)
		{
		$jobcategories[] =  array('value' => JText::_($row->id),
							'text' => JText::_($row->cat_title));
		}
		return $jobcategories;	
			
	}
	function getJobSalaryRange($title, $value)
	{
		$db =& JFactory::getDBO();
		
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_salaryrange')." ORDER BY 'id' ";
//		echo '<br>sql '.$query;
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
		if ($title) 
			$jobsalaryrange[] =  array('value' => JText::_($value),	'text' => JText::_($title));
		foreach($rows as $row)
		{
			$salrange = $currency . $row->rangestart.' - '.$currency . $row->rangeend;
			$jobsalaryrange[] =  array('value' => JText::_($row->id),
								'text' => JText::_($salrange));
		}
		return $jobsalaryrange;	
			
	}

	function getCountries( $title )
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_countries')." WHERE enabled = 'Y' ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$countries = array();
		if ($title) $countries[] =  array('value' => JText::_('0'),'text' => $title);
		else $countries[] =  array('value' => JText::_('0'),'text' => JText::_('==== choose country ===='));
		foreach($rows as $row)
		{
			$countries[] =  array('value' => JText::_($row->code),
							'text' => JText::_($row->name));
			//echo 'c'.$row->name;
		}
		return $countries;	
	}

	function getStates( $country )
	{
		$states = array();
			$db =& JFactory::getDBO();
			$query = "SELECT * FROM ".$db->nameQuote('#__js_job_states')." WHERE enabled = 'Y' AND countrycode = '". $country ."' ORDER BY name ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			
			foreach($rows as $row)	{
				$states[] =  array('value' => JText::_($row->code),	'text' => JText::_($row->name));
			}
		return $states;	
	}
	function getCounties( $state )
	{
		$counties = array();
			$db =& JFactory::getDBO();
			$query = "SELECT * FROM ".$db->nameQuote('#__js_job_counties')." WHERE enabled = 'Y' AND statecode = '". $state ."' ORDER BY name ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			
			foreach($rows as $row)	{
				$counties[] =  array('value' => JText::_($row->code),'text' => JText::_($row->name));
			}
		
		return $counties;	
	}
	function getCities( $county )
	{
		$cities = array();
			$db =& JFactory::getDBO();
			$query = "SELECT * FROM ".$db->nameQuote('#__js_job_cities')." WHERE enabled = 'Y' AND countycode = '". $county ."' ORDER BY name ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			
			foreach($rows as $row){
				$cities[] =  array('value' => JText::_($row->code),	'text' => JText::_($row->name));
			}
		
		return $cities;	
	}

	function getCompanies( $uid )
	{
			$db =& JFactory::getDBO();
			$query = "SELECT id, name FROM ".$db->nameQuote('#__js_job_companies')." WHERE uid = ". $uid ." ORDER BY name ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$companies = array();
			foreach($rows as $row)
			{
				$companies[] =  array('value' => JText::_($row->id),
								'text' => JText::_($row->name));
			}
		return $companies;	
	}
	
	function getCompaniesbyJobId( $jobid )
	{
			$db =& JFactory::getDBO();
			$query = "SELECT company.id, company.name 
			FROM ".$db->nameQuote('#__js_job_companies')." AS company
			JOIN ".$db->nameQuote('#__js_job_jobs')." AS job ON company.uid = job.uid 
			WHERE job.id = ". $jobid ." ORDER BY name ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			}
			$companies = array();
			foreach($rows as $row)
			{
				$companies[] =  array('value' => JText::_($row->id),
								'text' => JText::_($row->name));
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

	function getShift(  ){
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
			foreach($rows as $row)	{
				$this->_shifts[] =  array('value' => $row->id,	'text' => $row->title);
			}
		}						
		return $this->_shifts;	
	}

	function getRoles( $rolefor ){
		$db =& JFactory::getDBO();
		
		if ($rolefor != "")
			$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_roles')." WHERE rolefor = ". $rolefor ." AND published = 1 ORDER BY id ASC ";
		else
			$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_roles')." WHERE published = 1 ORDER BY id ASC ";
		
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}
		$roles = array();
		foreach($rows as $row)	{
			$roles[] =  array('value' => $row->id,	'text' => $row->title);
		}
		return $roles;	
	}

	function getExtension($str) {
		 $i = strrpos($str,".");
		 if (!$i) { return ""; }
		 $l = strlen($str) - $i;
		 $ext = substr($str,$i+1,$l);
		 return $ext;
	}

}
?>
