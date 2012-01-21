<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	uninstall.jsjobs.php
 ^ 
 * Description: Uninstaller
 ^ 
 * History:		NONE
 ^ 
 */

defined ('_JEXEC') or die ('Restricted access');

function com_uninstall()
{
	global $mainframe;
	$db = &JFactory::getDBO();
	
	$query = "SELECT * FROM ".$db->nameQuote('#__js_job_config')." WHERE configname = 'backuponuninstall'";
	$db->setQuery($query);
	//echo '<br>SQL '.$query;
	$backup = $db->loadObject();
	
	if ($backup->configvalue == '1'){
		$returnvalue = take_back_up();
		if ($returnvalue == 1) echo '<p>Uninstall for Joom Shark\'s JS Jobs Joomla! 1.5 Component successful.</p>';
	}else{
		echo '<p>Uninstall for Joom Shark\'s JS Jobs Joomla! 1.5 Component successful.</p>';
		$returnvalue = 1;
	}	

	return $returnvalue;
	
}

function take_back_up()
{
	$str=JPATH_BASE;
	$base = substr($str, 0,strlen($str)-14); //remove administrator
	$path =$base.'/components/com_jsjobs/data/';
	$newpath =$base.'/components/jsjobs_data_bak';

	if (file_exists($path)){
		full_copy($path, $newpath);
		echo '<br>Backup Resume directory : '.$newpath;
	}
	
	$db = &JFactory::getDBO();


	$configquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_config_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_config_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_config').''; 
	$catquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_categories_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_categories_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_categories').''; 
	$salaryquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_salaryrange_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_salaryrange_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_salaryrange').''; 
	$jobquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobs_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_jobs_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_jobs').''; 
	$jobapplyquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobapply_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_jobapply_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_jobapply').''; 
	//$joballowquery = getSQLJOBALLOWBackup();
	$eaquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_resume_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_resume_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_resume').''; 

	$countriesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_countries_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_countries_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_countries').''; 
	$statesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_states_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_states_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_states').''; 
	$countiesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_counties_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_counties_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_counties').'';
	$citiesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_cities_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_cities_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_cities');
	$zipsquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_zips_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_zips_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_zips');




	$companiesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_companies_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_companies_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_companies');
	$jobsearchesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobsearches_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_jobsearches_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_jobsearches');
	$jobstatusquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobstatus_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_jobstatus_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_jobstatus');
	$jobtypesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobtypes_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_jobtypes_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_jobtypes');
	$shiftsquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_shifts_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_shifts_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_shifts');
	

	$coverlettersquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_coverletters_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_coverletters_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_coverletters');
	$emailtemplatesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_emailtemplates_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_emailtemplates_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_emailtemplates');
	$fieldorderingquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_fieldsordering_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_fieldsordering_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_fieldsordering');
	$filtersquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_filters_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_filters_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_filters');
	$educationquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_heighesteducation_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_heighesteducation_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_heighesteducation');


	$resumesearchquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_resumesearches_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_resumesearches_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_resumesearches');

	$rolesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_roles_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_roles_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_roles');
	$userfieldsquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userfields_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_userfields_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_userfields');
	$userfieldvaluequery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userfieldvalues_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_userfieldvalues_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_userfieldvalues');
	$userfielddataquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userfield_data_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_userfield_data_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_userfield_data');
	$userrolesquery = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userroles_bak').'; CREATE TABLE '.$db->nameQuote('#__js_job_userroles_bak').' SELECT * FROM '.$db->nameQuote('#__js_job_userroles');


	$db->setQuery($configquery);
	if ( $result = $db->queryBatch()) echo JText::_('<br>Configurations backup successful!<br>');
	else echo "<font color='red'>".JText::_('<br>Error occurred while attempting to backup up Configurations.</font><br>');

	$db->setQuery($catquery);
	if ( $result = $db->queryBatch())	echo JText::_('Categories Data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Categories data.</font><br>');

	$db->setQuery($salaryquery);
	if ( $result = $db->queryBatch())	echo JText::_('Salary Range Data backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Salary Range data.</font><br>');

	$db->setQuery($jobquery);
	if ( $result = $db->queryBatch())	echo JText::_('Job Data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Job data.</font><br>');

	$db->setQuery($jobapplyquery);
	if ( $result = $db->queryBatch())	echo JText::_('Job Apply Data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Job Apply data.</font><br>');
/*
	$db->setQuery($joballowquery);
	if ( $result = $db->queryBatch())	echo JText::_('User Allow Data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up User Allow data.</font><br>');
*/
	$db->setQuery($eaquery);
	if ( $result = $db->queryBatch())	echo JText::_('Resume data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Resume data.</font><br>');

	$db->setQuery($companiesquery);
	if ( $result = $db->queryBatch()) echo JText::_('Companies backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Companies.</font><br>');

	$db->setQuery($jobsearchesquery);
	if ( $result = $db->queryBatch()) echo JText::_('Job Searches backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Job Searches.</font><br>');

	$db->setQuery($jobstatusquery);
	if ( $result = $db->queryBatch()) echo JText::_('Job Status backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Job Status.</font><br>');

	$db->setQuery($jobtypesquery);
	if ( $result = $db->queryBatch()) echo JText::_('Job Types backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Job Types.</font><br>');

	$db->setQuery($shiftsquery);
	if ( $result = $db->queryBatch()) echo JText::_('Job Shifts backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Job Shifts.</font><br>');

	$db->setQuery($coverlettersquery);
	if ( $result = $db->queryBatch()) echo JText::_('Cover Letters backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Cover Letters.</font><br>');

	$db->setQuery($emailtemplatesquery);
	if ( $result = $db->queryBatch()) echo JText::_('Email Templates backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Email Templates.</font><br>');

	$db->setQuery($fieldorderingquery);
	if ( $result = $db->queryBatch()) echo JText::_('Field Ordering backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Field Ordering.</font><br>');

	$db->setQuery($filtersquery);
	if ( $result = $db->queryBatch()) echo JText::_('Filters backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Filters.</font><br>');

	$db->setQuery($educationquery);
	if ( $result = $db->queryBatch()) echo JText::_('Heighest Education backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Heighest Education.</font><br>');
	
	$db->setQuery($resumesearchquery);
	if ( $result = $db->queryBatch()) echo JText::_('Resume Searches backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Resume Searches.</font><br>');

	$db->setQuery($rolesquery);
	if ( $result = $db->queryBatch()) echo JText::_('Roles backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Roles.</font><br>');

	$db->setQuery($userfieldsquery);
	if ( $result = $db->queryBatch()) echo JText::_('User Fields backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up User Fileds.</font><br>');

	$db->setQuery($userfieldvaluequery);
	if ( $result = $db->queryBatch()) echo JText::_('User Field Values backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up User Field Values.</font><br>');

	$db->setQuery($userfielddataquery);
	if ( $result = $db->queryBatch()) echo JText::_('User Field Data backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up User Field Data.</font><br>');

	$db->setQuery($userrolesquery);
	if ( $result = $db->queryBatch()) echo JText::_('User Roles backup successful!<br>');
	else echo "<font color='red'>".JText::_('Error occurred while attempting to backup up User Roles.</font><br>');

	
	$db->setQuery($countriesquery);
	if ( $result = $db->queryBatch())	echo JText::_('Countries data backup successful!<br>');
	else	echo JText::_('Error occurred while attempting to backup up Countries data.<br>');
	
	$db->setQuery($statesquery);
	if ( $result = $db->queryBatch())	echo JText::_('States data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up States data.</font><br>');

	$db->setQuery($countiesquery);
	if ( $result = $db->queryBatch())	echo JText::_('Counties data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Counties data.</font><br>');

	$db->setQuery($citiesquery);
	if ( $result = $db->queryBatch())	echo JText::_('Cities data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Cities data.</font><br>');

	$db->setQuery($zipsquery);
	if ( $result = $db->queryBatch())	echo JText::_('Zips data backup successful!<br>');
	else	echo "<font color='red'>".JText::_('Error occurred while attempting to backup up Zips data.</font><br>');

	
	return true;
}

function full_copy( $source, $target ) {
	if ( is_dir( $source ) ) {
		@mkdir( $target );
		$d = dir( $source );
		while ( FALSE !== ( $entry = $d->read() ) ) {
			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}
			$Entry = $source . '/' . $entry; 
			if ( is_dir( $Entry ) ) {
				full_copy( $Entry, $target . '/' . $entry );
				continue;
			}
			copy( $Entry, $target . '/' . $entry );
		}
 
		$d->close();
	}else {
		copy( $source, $target );
	}
}
 
?>
