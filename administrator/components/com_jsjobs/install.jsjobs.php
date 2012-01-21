<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	install.jsjobs.php
 ^ 
 * Description: Installer
 ^ 
 * History:		NONE
 ^ 
 */

defined ('_JEXEC') or die ('Restricted access');
 
function com_install()
{
	echo '<pre>';

	checkBackup(); 
	
	echo '</pre>';
	echo '<p>Thank you for installing Joom Shark\'s JS Jobs Joomla! 1.5 Component.</p>';
	
	return true;
}

function checkBackup()
{
	$db = &JFactory::getDBO();

	$str=JPATH_BASE;
	$base = substr($str, 0,strlen($str)-14); //remove administrator
	$newpath =$base.'/components/com_jsjobs/data/';
	$path =$base.'/components/jsjobs_data_bak';
	if (file_exists($path)){
		full_copy($path, $newpath);
		echo '<br>Resume Directory Retrieved<br> ';
		rmdir_r($path,1);
	}
	$com_query = "UPDATE #__components SET admin_menu_img = '../administrator/components/com_jsjobs/include/images/js.png' WHERE admin_menu_link='option=com_jsjobs'";
	$db->setQuery($com_query);
	$db->query();
	
	$return_value;	
	$query = 'SELECT COUNT(*) FROM '.$db->nameQuote('#__js_job_config_bak');
	$db->setQuery($query);
	//echo '<br>SQL '.$query;
	$backup = $db->loadResult();
		if ( $backup == 0 )	{ // no back up found
							// insert default values
				$query = setupSQLTransferCleanup();
				$db->setQuery($query);
				$db->queryBatch();
				echo JText::_('No backed up found... <br>');	

				$configquery = getDefaultConfigSQL();
				$catquery = getDefaultCatSQL();
				$jobtypequery = getDefaultJobTypeSQL();
				$salquery = getDefaultSalaryRangeSQL();
				$educationquery = getDefaultHeighestEducationSQL();
				$jobstatusquery = getDefaultJobStatusSQL();
				$shiftquery = getDefaultShiftSQL();
				$rolequery = getDefaultRoleSQL();
				$emailquery = getDefaultEmailTemplatesSQL();
				$contriesquery = getDefaultContriesSQL();
				$fieldorderingquery = getDefaultFieldOrderingSQL();
				
				$Pakstatesquery = getDefaultPakStatesSQL();
				$Pakcontiesquery = getDefaultPakCountiesSQL();
				$Pakcitiesquery = getDefaultPakCitiesSQL();

				$db->setQuery($configquery);
				if ( $result = $db->queryBatch())echo JText::_('Deault Configurations insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Configurations.</font><br>');
				
				$db->setQuery($catquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Categories data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Categories data.</font><br>');
				
				$db->setQuery($jobtypequery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Job Types data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Job Type data.</font><br>');

				$db->setQuery($salquery);
				if ( $result = $db->queryBatch()) echo JText::_('Deault Salary Range data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Salary Ranges data.</font><br>');

				$db->setQuery($educationquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Heighest Education data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Heighest Education data.</font><br>');

				$db->setQuery($jobstatusquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Job Status data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Job Status data.</font><br>');

				$db->setQuery($shiftquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Shift data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Shift data.</font><br>');

				$db->setQuery($rolequery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Roles data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Roles data.</font><br>');

				$db->setQuery($emailquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Email Templates data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Email Templates data.</font><br>');

				$db->setQuery($contriesquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Contries data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Contries data.</font><br>');

				$db->setQuery($Pakstatesquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Pakistan States data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Pakistan States data.</font><br>');
				$db->setQuery($Pakcontiesquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Pakistan Districts data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Pakistan Districts data.</font><br>');
				$db->setQuery($Pakcitiesquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Pakistan Cities data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Pakistan Cities data.</font><br>');

				$db->setQuery($fieldorderingquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Field Ordering data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Field Ordering data.</font><br>');
				
				return true;
		}else { // backup found
			$query = 'SELECT COUNT(*) FROM '.$db->nameQuote('#__js_job_userallow_bak');
			$db->setQuery($query);
			//echo '<br>SQL '.$query;
			$oldversionbackup = $db->loadResult();
			if ( $oldversionbackup == 0 )	{ // no old verson back up found
			
				echo JText::_('Database backed up data found... Beginning transfer ...<br>');
				$configquery = 'INSERT INTO '.$db->nameQuote('#__js_job_config').' SELECT * FROM '.$db->nameQuote('#__js_job_config_bak').';';
				$catquery = 'INSERT INTO '.$db->nameQuote('#__js_job_categories').' SELECT * FROM '.$db->nameQuote('#__js_job_categories_bak').';';
				$salaryquery = 'INSERT INTO '.$db->nameQuote('#__js_job_salaryrange').' SELECT * FROM '.$db->nameQuote('#__js_job_salaryrange_bak').';';
				$jobquery = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs').' SELECT * FROM '.$db->nameQuote('#__js_job_jobs_bak').';';
				$jobapplyquery = 'INSERT INTO '.$db->nameQuote('#__js_job_jobapply').' SELECT * FROM '.$db->nameQuote('#__js_job_jobapply_bak').';';
				$jaquery = 'INSERT INTO '.$db->nameQuote('#__js_job_resume').' SELECT * FROM '.$db->nameQuote('#__js_job_resume_bak').';';

				$countriesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_countries').' SELECT * FROM '.$db->nameQuote('#__js_job_countries_bak').';';
				$statesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_states').' SELECT * FROM '.$db->nameQuote('#__js_job_states_bak').';';
				$countiesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_counties').' SELECT * FROM '.$db->nameQuote('#__js_job_counties_bak').';';
				$citiesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_cities').' SELECT * FROM '.$db->nameQuote('#__js_job_cities_bak').';';
				$zipsquery = 'INSERT INTO '.$db->nameQuote('#__js_job_zips').' SELECT * FROM '.$db->nameQuote('#__js_job_zips_bak').';';


				$companiesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_companies').' SELECT * FROM '.$db->nameQuote('#__js_job_companies_bak').';';
				$jobsearchesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_jobsearches').' SELECT * FROM '.$db->nameQuote('#__js_job_jobsearches_bak').';';
				$jobstatusquery = 'INSERT INTO '.$db->nameQuote('#__js_job_jobstatus').' SELECT * FROM '.$db->nameQuote('#__js_job_jobstatus_bak').';';
				$jobtypequery = 'INSERT INTO '.$db->nameQuote('#__js_job_jobtypes').' SELECT * FROM '.$db->nameQuote('#__js_job_jobtypes_bak').';';
				$jobtypequery2 = 'INSERT INTO '.$db->nameQuote('#__js_job_jobtypes').' (id, title, isactive, status) SELECT id, title, isactive, 0 FROM '.$db->nameQuote('#__js_job_jobtypes_bak').';';
				$shiftsquery = 'INSERT INTO '.$db->nameQuote('#__js_job_shifts').' SELECT * FROM '.$db->nameQuote('#__js_job_shifts_bak').';';
				$shiftsquery2 = 'INSERT INTO  '.$db->nameQuote('#__js_job_shifts').' (id, title, isactive, status) SELECT id, title, isactive, 0 FROM '.$db->nameQuote('#__js_job_shifts_bak').';';
				$coverlettersquery = 'INSERT INTO '.$db->nameQuote('#__js_job_coverletters').' SELECT * FROM '.$db->nameQuote('#__js_job_coverletters_bak').';';
				$emailquery = 'INSERT INTO '.$db->nameQuote('#__js_job_emailtemplates').' SELECT * FROM '.$db->nameQuote('#__js_job_emailtemplates_bak').';';
				$fieldorderingquery = 'INSERT INTO '.$db->nameQuote('#__js_job_fieldsordering').' SELECT * FROM '.$db->nameQuote('#__js_job_fieldsordering_bak').';';

				
				$filtersquery = 'INSERT INTO '.$db->nameQuote('#__js_job_filters').' SELECT * FROM '.$db->nameQuote('#__js_job_filters_bak').';';
				$educationquery = 'INSERT INTO '.$db->nameQuote('#__js_job_heighesteducation').' SELECT * FROM '.$db->nameQuote('#__js_job_heighesteducation_bak').';';
				$resumesearchquery = 'INSERT INTO '.$db->nameQuote('#__js_job_resumesearches').' SELECT * FROM '.$db->nameQuote('#__js_job_resumesearches_bak').';';
				$rolesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_roles').' SELECT * FROM '.$db->nameQuote('#__js_job_roles_bak').';';
				$userfieldsquery = 'INSERT INTO '.$db->nameQuote('#__js_job_userfields').' SELECT * FROM '.$db->nameQuote('#__js_job_userfields_bak').';';
				
				$userfieldvaluequery = 'INSERT INTO '.$db->nameQuote('#__js_job_userfieldvalues').' SELECT * FROM '.$db->nameQuote('#__js_job_userfieldvalues_bak').';';
				$userfielddataquery = 'INSERT INTO '.$db->nameQuote('#__js_job_userfield_data').' SELECT * FROM '.$db->nameQuote('#__js_job_userfield_data_bak').';';
				$userrolesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_userroles').' SELECT * FROM '.$db->nameQuote('#__js_job_userroles_bak').';';


				$db->setQuery($configquery);
				if ( $result = $db->queryBatch()) echo JText::_('Configurations Retrieved backed up data successfully!<br>');
				else{
					$configquery = getDefaultConfigSQL();
					$db->setQuery($configquery);
					if ( $result = $db->queryBatch())
						echo JText::_('Deault Configurations insert successfully!<br>');
					else{
						echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Configurations.</font><br>');
					}
				}

				$configupdate = getUpdateConfigSQL();
				$db->setQuery($configupdate);
				if ( $result = $db->queryBatch())
					echo JText::_('Configurations update successfully!<br>');
				else{
					echo "<font color='red'>".JText::_('Error occurred while attempting to update configurations.</font><br>');
					//return false;
				}

				$db->setQuery($catquery);
				if ( $result = $db->queryBatch()) echo JText::_('Categories Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Categories data.</font><br>');
				
				$db->setQuery($salaryquery);
				if ( $result = $db->queryBatch()) echo JText::_('Salary Range Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Salary Range data.</font><br>');
				
				$db->setQuery($jobquery);
				if ( $result = $db->queryBatch())	echo JText::_('Job Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Job data.</font><br>');
				
				$db->setQuery($jobapplyquery);
				if ( $result = $db->queryBatch()) echo JText::_('Job Apply Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Job Apply data.</font><br>');
				
/*			$db->setQuery($joballowquery);
				if ( $result = $db->queryBatch())
					echo JText::_('User Allow Retrieved backed up data successfully!<br>');
				else{
					echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up User Allow data.</font><br>');
					//return false;
				}
*/
				
				$db->setQuery($jaquery);
				if ( $result = $db->queryBatch())
					echo JText::_('Resume Retrieved backed up data successfully!<br>');
				else
						echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Resume data.</font><br>');

				$db->setQuery($companiesquery);
				if ( $result = $db->queryBatch()) echo JText::_('Companies Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Companies data.</font><br>');

				$db->setQuery($jobsearchesquery);
				if ( $result = $db->queryBatch()) echo JText::_('Job Searches Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Job Searches data.</font><br>');

				$db->setQuery($jobstatusquery);
				if ( $result = $db->queryBatch()) echo JText::_('Job Status Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Job Status data.</font><br>');

				$db->setQuery($jobtypequery);
				if ( $result = $db->queryBatch()) {
					echo JText::_('Job Types Retrieved backed up data successfully!<br>');
				}else {
					$db->setQuery($jobtypequery2);
					if ( $result = $db->queryBatch()) echo JText::_('Job Types Retrieved backed up data successfully!<br>');
					else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Job Types data.</font><br>');
				}
				$db->setQuery($shiftsquery);
				if ( $result = $db->queryBatch()) {
					echo JText::_('Job Shifts Retrieved backed up data successfully!<br>');
				}else {
					$db->setQuery($shiftsquery2);
					if ( $result = $db->queryBatch()) echo JText::_('Job Shifts Retrieved backed up data successfully!<br>');
					else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Job Shifts data.</font><br>');
				}	

				$db->setQuery($coverlettersquery);
				if ( $result = $db->queryBatch()) echo JText::_('Cover Letters Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Cover Letters data.</font><br>');

				$db->setQuery($emailquery);
				if ( $result = $db->queryBatch()) echo JText::_('Email Templates Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Email Templates data.</font><br>');

				$db->setQuery($fieldorderingquery);
				if ( $result = $db->queryBatch()) echo JText::_('Field Ordering Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Field Ordering data.</font><br>');
				
				$db->setQuery($filtersquery);
				if ( $result = $db->queryBatch()) echo JText::_('Filters Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Filters data.</font><br>');

				$db->setQuery($educationquery);
				if ( $result = $db->queryBatch()) echo JText::_('Heighest Education Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Heighest Education data.</font><br>');

				$db->setQuery($resumesearchquery);
				if ( $result = $db->queryBatch()) echo JText::_('Resume Searches Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Resume Searches data.</font><br>');

				$db->setQuery($rolesquery);
				if ( $result = $db->queryBatch()) echo JText::_('Roles Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Roles data.</font><br>');

				$db->setQuery($userfieldsquery);
				if ( $result = $db->queryBatch()) echo JText::_('User Fields Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up User Fields data.</font><br>');

				$db->setQuery($userfieldvaluequery);
				if ( $result = $db->queryBatch()) echo JText::_('User Field Values Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up User Field Values data.</font><br>');

				$db->setQuery($userfielddataquery);
				if ( $result = $db->queryBatch()) echo JText::_('User Field Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up User Field data.</font><br>');
				
				$db->setQuery($userrolesquery);
				if ( $result = $db->queryBatch()) echo JText::_('User Roles Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up User Roles data.</font><br>');

				
				$db->setQuery($countriesquery);
				if ( $result = $db->queryBatch()) echo JText::_('Countries Retrieved backed up data successfully!<br>');
				else echo JText::_('Error occurred while attempting to transfer backed up Countries data.<br>');
			
				$db->setQuery($statesquery);
				if ( $result = $db->queryBatch())	echo JText::_('States Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up States data.</font><br>');

				$db->setQuery($countiesquery);
				if ( $result = $db->queryBatch())	echo JText::_('Counties Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Counties data.</font><br>');

				$db->setQuery($citiesquery);
				if ( $result = $db->queryBatch()) echo JText::_('Cities Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Cities data.</font><br>');

				$db->setQuery($zipsquery);
				if ( $result = $db->queryBatch())	echo JText::_('Zips Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Zips data.</font><br>');


				$clquery = setupSQLTransferCleanup();
				//echo '<br> clean '.$clquery;
				$db->setQuery($clquery);
				$db->queryBatch();
			}else{ // bcak up v 1.0.4 or old
				backupv104();
				$clquery = setupSQLTransferCleanup();
				//echo '<br> clean '.$clquery;
				$db->setQuery($clquery);
				$db->queryBatch();
			}
		}	
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

function rmdir_r ( $dir, $DeleteMe = TRUE )
{
	if ( ! $dh = @opendir ( $dir ) ) return;
	while ( false !== ( $obj = readdir ( $dh ) ) )
	{
		if ( $obj == '.' || $obj == '..') continue;
		if ( ! @unlink ( $dir . '/' . $obj ) ) rmdir_r ( $dir . '/' . $obj, true );
	}
	
	closedir ( $dh );
	if ( $DeleteMe )
	{
		@rmdir ( $dir );
	}
}

function backupv104()
{

	$db = &JFactory::getDBO();
	$companies = array();
/*
				$catquery = getDefaultCatSQL();
				$salquery = getDefaultSalaryRangeSQL();
				$contriesquery = getDefaultContriesSQL();
				
				$Pakstatesquery = getDefaultPakStatesSQL();
				$Pakcontiesquery = getDefaultPakCountiesSQL();
				$Pakcitiesquery = getDefaultPakCitiesSQL();
*/
				$configquery = getUpdateConfigSQL();
				$jobtypequery = getDefaultJobTypeSQL();
				$educationquery = getDefaultHeighestEducationSQL();
				$jobstatusquery = getDefaultJobStatusSQL();
				$shiftquery = getDefaultShiftSQL();
				$rolequery = getDefaultRoleSQL();
				$emailquery = getDefaultEmailTemplatesSQL();
				$fieldorderingquery = getDefaultFieldOrderingSQL();
				
				$catquery = 'INSERT INTO '.$db->nameQuote('#__js_job_categories').' SELECT * FROM '.$db->nameQuote('#__js_job_categories_bak').';';
				$salaryquery = 'INSERT INTO '.$db->nameQuote('#__js_job_salaryrange').' SELECT * FROM '.$db->nameQuote('#__js_job_salaryrange_bak').';';
				$countriesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_countries').' SELECT * FROM '.$db->nameQuote('#__js_job_countries_bak').';';
				$statesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_states').' SELECT * FROM '.$db->nameQuote('#__js_job_states_bak').';';
				$countiesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_counties').' SELECT * FROM '.$db->nameQuote('#__js_job_counties_bak').';';
				$citiesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_cities').' SELECT * FROM '.$db->nameQuote('#__js_job_cities_bak').';';
				$zipsquery = 'INSERT INTO '.$db->nameQuote('#__js_job_zips').' SELECT * FROM '.$db->nameQuote('#__js_job_zips_bak').';';

				$db->setQuery($catquery);
				if ( $result = $db->queryBatch()) echo JText::_('Categories Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Categories data.</font><br>');
				
				$db->setQuery($salaryquery);
				if ( $result = $db->queryBatch()) echo JText::_('Salary Range Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Salary Range data.</font><br>');

				$db->setQuery($countriesquery);
				if ( $result = $db->queryBatch()) echo JText::_('Countries Retrieved backed up data successfully!<br>');
				else echo JText::_('Error occurred while attempting to transfer backed up Countries data.<br>');
			
				$db->setQuery($statesquery);
				if ( $result = $db->queryBatch())	echo JText::_('States Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up States data.</font><br>');

				$db->setQuery($countiesquery);
				if ( $result = $db->queryBatch())	echo JText::_('Counties Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Counties data.</font><br>');

				$db->setQuery($citiesquery);
				if ( $result = $db->queryBatch()) echo JText::_('Cities Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Cities data.</font><br>');

				$db->setQuery($zipsquery);
				if ( $result = $db->queryBatch())	echo JText::_('Zips Retrieved backed up data successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Zips data.</font><br>');

				$db->setQuery($configquery);
				if ( $result = $db->queryBatch())echo JText::_('Deault Configurations insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Configurations.</font><br>');
				
				$db->setQuery($jobtypequery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Job Types data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Job Type data.</font><br>');

				$db->setQuery($educationquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Heighest Education data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Heighest Education data.</font><br>');

				$db->setQuery($jobstatusquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Job Status data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Job Status data.</font><br>');

				$db->setQuery($shiftquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Shift data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Shift data.</font><br>');

				$db->setQuery($rolequery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Roles data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Roles data.</font><br>');

				$db->setQuery($emailquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Email Templates data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Email Templates data.</font><br>');

				$db->setQuery($fieldorderingquery);
				if ( $result = $db->queryBatch())	echo JText::_('Deault Field Ordering data insert successfully!<br>');
				else echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Field Ordering data.</font><br>');

	echo JText::_('Retreving Jobs backup ... <br>');
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
		$query = 'INSERT INTO '.$db->nameQuote('#__js_job_companies').' 
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
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs').' 
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
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_companies').' 
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
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs').' 
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
					$query = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs').' 
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
	}//else echo '<br> not found';	
	
		echo JText::_('Retreving Resume backup ... <br>');
		$query = 'INSERT INTO '.$db->nameQuote('#__js_job_resume').' 
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
		//echo '<br> SQL '.$query;
		$db->setQuery($query);
		if ( $result = $db->queryBatch())
			echo JText::_('Resume Retrieved backed up data successfully!<br>');
		else
			echo "<font color='red'>".JText::_('Error occurred while attempting to transfer backed up Resume data.</font><br>');

		echo JText::_('Retreving Resume directory backup ... <br>');
		$query = 'SELECT * FROM '.$db->nameQuote('#__js_job_resume');
		//echo '<br> SQL '.$query;
		$str=JPATH_BASE;
		$base = substr($str, 0,strlen($str)-14); //remove administrator
		$db->setQuery($query);
		$resumes = $db->loadObjectList();
		if( isset($resumes)){
			foreach ($resumes as $resume) {
				if ($resume->filename){
					$iddir = 'resume_'.$resume->id;
					//$path =JPATH_BASE.'/components/com_jsjobs/data';
					$path =$base.'/components/com_jsjobs/data';
					if (!file_exists($path)){ // creating resume directory
						mkdir($path, 0755);
					}
					$path =JPATH_BASE.'/components/com_jsjobs/data/jobseeker';
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

		// remove directory
		$path =$base.'/components/jsjobs_resume_bak';
		if (file_exists($path)){
			rmdir_r($path,1);
		}
		

}

function checkBackup7()
{
	$db = &JFactory::getDBO();
	$return_value;	
	$query = 'SELECT COUNT(*) FROM '.$db->nameQuote('#__at_jp_jobs_bak');
		$db->setQuery($query);
		$backup = $db->loadResult();
		if ( $backup == 0 )
		{
			return 1;
		}
		else
		{
			echo JText::_('Jobs n Apps Backed up data found... Beginning transfer ...<br>');

			$configquery = getDefaultConfigSQL();
			$db->setQuery($configquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Deault Configurations insert successfully!<br>');
			else{
				echo JText::_('Error occurred while attempting to inserting default Configurations.<br>');
			}
			$catquery = 'INSERT INTO '.$db->nameQuote('#__js_job_categories').' SELECT * FROM '.$db->nameQuote('#__at_jp_categories_bak').';';
			$salaryquery = 'INSERT INTO '.$db->nameQuote('#__js_job_salaryrange').' SELECT * FROM '.$db->nameQuote('#__at_jp_salaryrange_bak').';';
			$jobquery = 'INSERT INTO '.$db->nameQuote('#__js_job_jobs').' SELECT * FROM '.$db->nameQuote('#__at_jp_jobs_bak').';';
			$jobapplyquery = 'INSERT INTO '.$db->nameQuote('#__js_job_jobapply').' SELECT * FROM '.$db->nameQuote('#__at_jp_jobapply_bak').';';
			$joballowquery = 'INSERT INTO '.$db->nameQuote('#__js_job_userallow').' SELECT * FROM '.$db->nameQuote('#__at_jp_userallow_bak').';';

			//$countriesquery = getCountriesSQLBakup();
			$statesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_states').' SELECT * FROM '.$db->nameQuote('#__at_jp_states_bak').';';
			$countiesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_counties').' SELECT * FROM '.$db->nameQuote('#__at_jp_counties_bak').';';
			$citiesquery = 'INSERT INTO '.$db->nameQuote('#__js_job_cities').' SELECT * FROM '.$db->nameQuote('#__at_jp_cities_bak').';';
			$zipsquery = 'INSERT INTO '.$db->nameQuote('#__js_job_zips').' SELECT * FROM '.$db->nameQuote('#__at_jp_zips_bak').';';

			$configquery = getDefaultConfigSQL();
			$db->setQuery($configquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Deault Configurations insert successfully!<br>');
			else{
				echo "<font color='red'>".JText::_('Error occurred while attempting to inserting default Configurations.</font><br>');
			}


			$db->setQuery($catquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Categories Retrieved backed up data successfully!<br>');
			else{
				echo JText::_('Error occurred while attempting to transfer backed up Categories data.<br>');
				//return false;
			}
			
			$db->setQuery($salaryquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Salary Range Retrieved backed up data successfully!<br>');
			else{
				echo JText::_('Error occurred while attempting to transfer backed up Salary Range data.<br>');
				//return false;
			}
			
			$db->setQuery($jobquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Job Retrieved backed up data successfully!<br>');
			else{
				echo JText::_('Error occurred while attempting to transfer backed up Job data.<br>');
				//return false;
			}
			
			$db->setQuery($jobapplyquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Job Apply Retrieved backed up data successfully!<br>');
			else{
				echo JText::_('Error occurred while attempting to transfer backed up Job Apply data.<br>');
				//return false;
			}
			
			$db->setQuery($joballowquery);
			if ( $result = $db->queryBatch())
				echo JText::_('User Allow Retrieved backed up data successfully!<br>');
			else{
				echo JText::_('Error occurred while attempting to transfer backed up User Allow data.<br>');
				//return false;
			}

			
				echo JText::_('Error occurred while attempting to transfer backed up Resume data.<br>');
				//return false;

			
			/*
			$db->setQuery($countriesquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Countries Retrieved backed up data successfully!<br>');
			else
				echo JText::_('Error occurred while attempting to transfer backed up Countries data.<br>');
		*/
			$db->setQuery($statesquery);
			if ( $result = $db->queryBatch())
				echo JText::_('States Retrieved backed up data successfully!<br>');
			else
				echo JText::_('Error occurred while attempting to transfer backed up States data.<br>');

			$db->setQuery($countiesquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Counties Retrieved backed up data successfully!<br>');
			else
				echo JText::_('Error occurred while attempting to transfer backed up Counties data.<br>');

			$db->setQuery($citiesquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Cities Retrieved backed up data successfully!<br>');
			else
				echo JText::_('Error occurred while attempting to transfer backed up Cities data.<br>');

			$db->setQuery($zipsquery);
			if ( $result = $db->queryBatch())
				echo JText::_('Zips Retrieved backed up data successfully!<br>');
			else
				echo JText::_('Error occurred while attempting to transfer backed up Zips data.<br>');


				$sql = 'DROP TABLE '.$db->nameQuote('#__at_jp_jobs_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_categories_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_salaryrange_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_jobapply_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_userallow_bak').';';
				//$sql .= ' DROP TABLE '.$db->nameQuote('#__js_job_resume_bak').';';
				//$sql .= ' DROP TABLE '.$db->nameQuote('#__js_job_countries_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_states_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_counties_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_cities_bak').';';
				$sql .= ' DROP TABLE '.$db->nameQuote('#__at_jp_zips_bak').';';
			//echo '<br> clean '.$clquery;
			$db->setQuery($sql);
			$db->queryBatch();
			return 3;
		}	
	return true;
}


function setupSQLTransferCleanup()
{
	$db = &JFactory::getDBO();
	$sql = 'DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobs_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_categories_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_salaryrange_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobapply_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userallow_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_resume_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_countries_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_states_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_counties_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_cities_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_zips_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_config_bak').';';

	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_companies_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobsearches_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobstatus_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_jobtypes_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_shifts_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_coverletters_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_emailtemplates_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_fieldsordering_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_filters_bak').';';

	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_heighesteducation_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_resumesearches_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_roles_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userfields_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userfieldvalues_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userfield_data_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userroles_bak').';';
	$sql .= ' DROP TABLE IF EXISTS '.$db->nameQuote('#__js_job_userallow_bak').';';
	return $sql;
}




function getDefaultCatSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Accounting/Finance');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Administrative');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Advertising');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Airlines/Avionics/Aerospace');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Architectural');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Automotive');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Banking/Finance');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Biotechnology');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Civil/Construction');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Engineering');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Cleared Jobs');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Communications');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Computer/IT');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Construction');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Consultant/Contractual');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Customer Service');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Defense');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Design');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Education');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Electrical Engineering');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Electronics Engineering');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Energy');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Engineering');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Environmental/Safety');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Fundraising');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Health/Medicine');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Homeland Security');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Human Resources');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Insurance');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Intelligence Jobs');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Internships/Trainees');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Legal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Logistics/Transportation');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Maintenance');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Management');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Manufacturing/Warehouse');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Marketing');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Materials Management');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Mechanical Engineering');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Mortgage/Real Estate');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('National Security');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Part-time/Freelance');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Printing');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Product Design');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Public Relations');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Public Safety');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Research');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Retail');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Sales');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Scientific');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Shipping/Distribution');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Technicians');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Trades');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Transportation');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Transportation Engineering');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_categories')." (cat_title) VALUES ('Web Site Development');";
	return $sql;
}

function getDefaultConfigSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('companyautoapprove','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_city','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_county','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_editor','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_state','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_zipcode','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('currency','$');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('cur_location','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('defaultcountry','PK');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('defaultempallow','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('defaultjoballow','0');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('empautoapprove','0');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('employerdefaultrole','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('jobautoapprove','0');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('jobseekerdefaultrole','2');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('job_editor','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('mailfromaddress','sender@joomshark.com');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('mailfromname','JS Jobs');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('newdays','7');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeaddress','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeeducation','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_gradeschool','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_highschool','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_otherchool','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_university','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeemployer','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_1','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_2','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_3','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_recent','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference1','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference2','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference3','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeskill','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_durration','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_experience','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_heighesteducation','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_salaryrange','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_shift','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_showsave','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_startpublishing','1');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('actk', '0');";	
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_stoppublishing','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_available','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_experience','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_gender','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_heighesteducation','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_name','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_nationality','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_salaryrange','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_showsave','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_title','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('showemployerlink','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('theme','jsjobs01.css');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('title','JS JOBS');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('version','1.0.5.8');";
 	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('versioncode', '1508');";
 	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_address', '1');";
 	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_address_fields_width', '130');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('refercode', '0');";	
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_category', '1');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_jobtype', '1');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_heighesteducation', '1');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_salaryrange', '1');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter', '1');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('fr_cr_txa', '<img src=\"components/com_jsjobs/images/jsjobs_logo_small.png\" width=\"65\">&nbsp;&nbsp;&nbsp;Powered by <a href=\"http://www.joomshark.com\" target=\"_blank\">Joom Shark</a>');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('fr_cr_txsh', '0');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('fr_cr_txb', '<br>&copy;Copyright 2008 - 2010, <a href=\"http://www.al-barr.com\" target=\"_blank\">Al-Barr Technologies </a> ');";
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('backuponuninstall', '1');";	
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('company_logofilezize', '50');";	
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resume_photofilesize', '50');";	
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('offline', '1');";	
  	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_config')." VALUES ('offline_text', 'JS Jobs is down for maintenance.<br /> Please check back again soon.');";	
	return $sql;
}
function getUpdateConfigSQL() {
	$db = &JFactory::getDBO();
		$configupdate = "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('companyautoapprove','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_city','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_county','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_editor','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_state','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('comp_zipcode','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('currency','$');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('cur_location','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('defaultcountry','PK');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('defaultempallow','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('defaultjoballow','0');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('empautoapprove','0');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('employerdefaultrole','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('jobautoapprove','0');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('jobseekerdefaultrole','2');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('job_editor','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('mailfromaddress','sender@joomshark.com');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('mailfromname','JS Jobs');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('newdays','7');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeaddress','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeeducation','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_gradeschool','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_highschool','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_otherchool','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeedu_university','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeemployer','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_1','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_2','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_3','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeem_recent','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference1','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference2','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumereference3','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resumeskill','1');";
		$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('actk', '0');";	
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_durration','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_experience','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_heighesteducation','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_salaryrange','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_shift','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_showsave','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_startpublishing','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_job_stoppublishing','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_available','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_experience','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_gender','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_heighesteducation','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_name','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_nationality','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_salaryrange','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_showsave','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('search_resume_title','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('showemployerlink','1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('theme','jsjobs01.css');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('title','JS JOBS');";
		$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('version','1.0.5.8');";
		$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('versioncode', '1508');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_address', '1');";
	  	$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('refercode', '0');";	
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_address_fields_width', '130');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_category', '1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_jobtype', '1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_heighesteducation', '1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter_salaryrange', '1');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('filter', '1');";
		$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('fr_cr_txa', '<img src=\"components/com_jsjobs/images/jsjobs_logo_small.png\" width=\"65\">&nbsp;&nbsp;&nbsp;Powered by <a href=\"http://www.joomshark.com\" target=\"_blank\">Joom Shark</a>');";
		$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('fr_cr_txsh', '0');";
		$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('fr_cr_txb', '<br>&copy;Copyright 2008 - 2010, <a href=\"http://www.al-barr.com\" target=\"_blank\">Al-Barr Technologies </a> ');";
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('backuponuninstall', '1');";	
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('company_logofilezize', '50');";	
		$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('resume_photofilesize', '50');";	
	  	$configupdate .= "REPLACE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('offline', '1');";	
	  	$configupdate .= "INSERT IGNORE INTO ".$db->nameQuote('#__js_job_config')." VALUES ('offline_text', 'JS Jobs is down for maintenance.<br /> Please check back again soon.');";	
	return $configupdate;

}

function getDefaultSalaryRangeSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('1000', '1500');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('1500', '2000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('2000', '2500');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('2500', '3000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('3000', '3500');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('3500', '4000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('4000', '4500');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('4500', '5000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('5000', '5500');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('5500', '6000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('6000', '7000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('7000', '8000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('8000', '9000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('9000', '10000');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_salaryrange')." (rangestart, rangeend) VALUES ('10000', '10000+');";
	return $sql;
}

function getDefaultHeighestEducationSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_heighesteducation')." VALUES (1,'University','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_heighesteducation')." VALUES (2,'College','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_heighesteducation')." VALUES (3,'High School','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_heighesteducation')." VALUES (4,'No School','1');";
	return $sql;
}

function getDefaultJobStatusSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_jobstatus')." VALUES (1,'Sourcing','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_jobstatus')." VALUES (2,'Interviewing','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_jobstatus')." VALUES (3,'Closed to New Applicants','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_jobstatus')." VALUES (4,'Finalists Identified','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_jobstatus')." VALUES (5,'Pending Approval','1');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_jobstatus')." VALUES (6,'Hold','1');";
	return $sql;
}

function getDefaultJobTypeSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_jobtypes')." VALUES (1,'Full-Time','1','0');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_jobtypes')." VALUES (2,'Part-Time','1','0');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_jobtypes')." VALUES (3,'Internship','1','0');";
	return $sql;
}

function getDefaultShiftSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_shifts')." VALUES (1,'Morning','1','0');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_shifts')." VALUES (2,'Evening','1','0');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_shifts')." VALUES (3,'8 PM to 4 AM','1','0');";
	return $sql;
}

function getDefaultRoleSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_roles')." VALUES (1,'Employer',1,-1,-1,-1,-1,-1,-1,-1,-1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_roles')." VALUES (2,'Job Seeker',2,-1,-1,-1,-1,-1,-1,-1,-1,1);";
	return $sql;
}

function getDefaultEmailTemplatesSQL() {
	$db = &JFactory::getDBO();
	$siteAddress='';
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_emailtemplates')." VALUES (1,0,'company-approval',NULL,'Company {COMPANY_NAME} has been approved','<p>Dear  {EMPLOYER_NAME} , <br /><br />Your company <strong>{COMPANY_NAME}</strong> has been approved.</p><p> Login and view detail at www.joomshark.com  <br /><br />Please do not respond to this message. It is automatically generated and is for information purposes only. </p>',1,'2009-08-17 18:08:41');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_emailtemplates')." VALUES (2,0,'company-rejecting',NULL,'Your Company {COMPANY_NAME} has been rejected','<p>Dear  {EMPLOYER_NAME} , </p><p>Your company<strong> {COMPANY_NAME}</strong> has been rejected. </p><p>Login and view detail at www.joomshark.com  </p><p>Please do not respond to this message. It is automatically generated and is for information purposes only. </p>',NULL,'2009-08-17 17:54:48');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_emailtemplates')." VALUES (4,0,'job-approval',NULL,'Your job {JOB_TITLE} has been approved.','<p>Dear  {EMPLOYER_NAME} , </p><p>Your job <strong>{JOB_TITLE}</strong> has been approved.</p><p> Login and view detail at $siteAddress  </p><p>Please do not respond to this message. It is automatically generated and is for information purposes only.</p>',NULL,'2009-08-17 22:10:27');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_emailtemplates')." VALUES (5,0,'job-rejecting',NULL,'Your job {JOB_TITLE} has been rejected.','<p>Dear  {EMPLOYER_NAME} , </p><p>Your job <strong>{JOB_TITLE}</strong> has been rejected.</p><p> Login and view detail at $siteAddress  </p><p>Please do not respond to this message. It is automatically generated and is for information purposes only.</p>',NULL,'2009-08-17 22:12:43');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_emailtemplates')." VALUES (6,0,'resume-approval',NULL,'Your resume {RESUME_TITLE} has been approval.','<p>Dear  {JOBSEEKER_NAME}  , </p><p> Your resume <strong>{RESUME_TITLE}</strong>  has been approval.</p><p> Login and view detail at $siteAddress  </p><p>Please do not respond to this message. It is automatically generated and is for information purposes only.</p>',NULL,'2009-08-17 22:15:12');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_emailtemplates')." VALUES (7,0,'resume-rejecting',NULL,'Your company {RESUME_TITLE} has been rejected. ','<p>Dear  {JOBSEEKER_NAME}  , </p><p> Your resume <strong>{RESUME_TITLE}</strong>  has been rejected.</p><p> Login and view detail at $siteAddress  </p><p>Please do not respond to this message. It is automatically generated and is for information purposes only.</p>',NULL,'2009-08-17 22:14:52');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_emailtemplates')." VALUES (8,0,'jobapply-jobapply',NULL,'JS Jobs :  {JOBSEEKER_NAME} apply for {JOB_TITLE}','<p>Hello  {EMPLOYER_NAME} , </p><p> Mr/Mrs  {JOBSEEKER_NAME} apply for {JOB_TITLE}.</p><p> Login and view detail at $siteAddress  \\n\\nPlease do not respond to this message. It is automatically generated and is for information purposes only.</p>',NULL,'2009-08-18 16:46:16');";
	return $sql;
}

function getDefaultContriesSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (3,'AF','BF','Burkina Faso','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (5,'AF','CM','Cameroon','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (6,'AF','CV','Cape Verde','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (7,'AF','CF','Central African Republic','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (8,'AF','TD','Chad','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (9,'AF','KM','Comoros','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (10,'AF','CG','Congo','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (12,'AF','BJ','Benin','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (14,'AF','BI','Burundi','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (21,'AF','CI','Cote D\Ivorie','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (22,'AF','DJ','Djibouti','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (23,'AF','GQ','Equatorial Guinea','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (24,'AF','ER','Eritrea','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (25,'AF','ET','Ethiopia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (26,'AF','EG','Egypt','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (27,'AF','GA','Gabon','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (28,'AF','GH','Ghana','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (29,'AF','GN','Guinea','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (30,'AF','GM','Gambia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (31,'AF','GW','Guinea-Bissau','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (32,'AF','KE','Kenya','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (33,'AF','LS','Lesotho','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (34,'AF','LR','Liberia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (35,'AF','MG','Madagascar','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (36,'AF','ML','Mali','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (37,'AF','MR','Mauritania','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (38,'AF','YT','Mayotte','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (39,'AF','MA','Morocco','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (40,'AF','MZ','Mozambique','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (41,'AF','MW','Malawi','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (42,'AF','NA','Namibia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (43,'AF','NE','Niger','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (44,'AF','NG','Nigeria','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (45,'AF','RE','Reunion','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (46,'AF','SH','St. Helena','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (47,'AF','ST','Sao Tome and Principe','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (48,'AF','SN','Senegal','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (49,'AF','SL','Sierra Leone','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (50,'AF','SO','Somalia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (51,'AF','ZA','South Africa','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (52,'AF','SD','Sudan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (53,'AF','SZ','Swaziland','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (54,'AF','TZ','Tanzania','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (55,'AF','TG','Togo','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (56,'AF','UG','Uganda','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (57,'AF','EH','Western Sahara','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (58,'AF','ZR','Zaire','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (59,'AF','ZM','Zambia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (60,'AF','ZW','Zimbabwe','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (62,'AS','AF','Afghanistan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (63,'AS','BD','Bangladesh','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (64,'AS','BT','Bhutan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (65,'AS','BN','Brunei','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (66,'AS','KH','Cambodia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (67,'AS','CN','China','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (68,'AS','HK','Hong Kong','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (69,'AS','IN','India','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (70,'AS','ID','Indonesia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (71,'AS','JP','Japan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (72,'AS','KZ','Kazakhstan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (73,'AS','KG','Kyrgyzstan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (74,'AS','LA','Laos','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (75,'AS','MO','Macau','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (76,'AS','MY','Malaysia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (77,'AS','MV','Maldives','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (78,'AS','MN','Mongolia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (79,'AS','NP','Nepal','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (80,'AS','PK','Pakistan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (81,'AS','PH','Philippines','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (82,'AS','KR','Republic of Korea','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (83,'AS','RU','Russia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (84,'AS','SC','Seychelles','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (85,'AS','SG','Singapore','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (86,'AS','LK','Sri Lanka','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (87,'AS','TW','Taiwan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (88,'AS','TJ','Tajikistan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (89,'AS','TH','Thailand','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (90,'AS','TM','Turkmenistan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (91,'AS','UZ','Uzbekistan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (92,'AS','VN','Vietnam','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (94,'AU','AU','Australia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (95,'AU','FM','Federated States of Micronesia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (96,'AU','FJ','Fiji','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (97,'AU','PF','French Polynesia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (98,'AU','GU','Guam','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (99,'AU','KI','Kiribati','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (100,'AU','MH','Marshall Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (101,'AU','NR','Nauru','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (102,'AU','NC','New Caledonia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (103,'AU','NZ','New Zealand','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (104,'AU','MP','Northern Mariana Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (105,'AU','PW','Palau','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (106,'AU','PG','Papua New Guinea','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (107,'AU','PN','Pitcairn','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (108,'AU','SB','Solomon Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (109,'AU','TO','Tonga','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (110,'AU','TV','Tuvalu','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (111,'AU','VU','Vanuatu','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (112,'CA','AI','Anguilla','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (114,'CA','AW','Aruba','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (115,'CA','BS','Bahamas','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (116,'CA','BB','Barbados','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (117,'CA','BM','Bermuda','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (118,'CA','VI','British Virgin Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (119,'CA','KY','Cayman Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (120,'CA','DM','Dominica','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (121,'CA','DO','Dominican Republic','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (122,'CA','GD','Grenada','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (123,'CA','GP','Guadeloupe','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (124,'CA','HT','Haiti','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (125,'CA','JM','Jamaica','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (126,'CA','MQ','Martinique','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (127,'CA','AN','Neterlands Antilles','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (128,'CA','PR','Puerto Rico','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (129,'CA','KN','St. Kitts and Nevis','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (130,'CA','LC','St. Lucia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (131,'CA','VC','St. Vincent and the Grenadines','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (132,'CA','TT','Trinidad and Tobago','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (133,'CA','TC','Turks and Caicos Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (134,'CE','BZ','Belize','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (135,'CE','CR','Costa Rica','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (136,'CE','SV','El Salvador','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (137,'CE','GT','Guatemala','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (138,'CE','HN','Honduras','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (139,'CE','NI','Nicaragua','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (140,'CE','PA','Panama','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (143,'CE','AM','Armenia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (144,'CE','AT','Austria','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (145,'CE','AZ','Azerbaijan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (146,'CE','BY','Belarus','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (147,'CE','BE','Belgium','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (148,'CE','BG','Bulgaria','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (149,'CE','HR','Croatia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (150,'CE','CY','Cyprus','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (151,'CE','CZ','Czech Republic','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (152,'CE','DK','Denmark','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (153,'CE','EE','Estonia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (154,'CE','FO','Faroe Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (155,'CE','FI','Finland','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (156,'CE','FR','France','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (157,'CE','GE','Georgia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (158,'CE','DE','Germany','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (159,'CE','GI','Gibraltar','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (160,'CE','GR','Greece','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (161,'CE','GL','Greenland','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (162,'CE','HU','Hungary','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (163,'CE','IS','Iceland','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (164,'CE','IE','Ireland','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (165,'CE','IT','Italy','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (166,'CE','LV','Latvia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (167,'CE','LI','Liechtenstein','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (168,'CE','LT','Lithuania','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (169,'CE','LU','Luxembourg','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (170,'CE','MT','Malta','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (171,'CE','FX','Metropolitan France','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (172,'CE','MD','Moldova','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (173,'CE','NL','Netherlands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (174,'CE','NO','Norway','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (175,'CE','PL','Poland','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (176,'CE','PT','Portugal','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (177,'CE','RO','Romania','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (178,'CE','SK','Slovakia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (179,'CE','SI','Slovenia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (180,'CE','ES','Spain','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (181,'CE','SJ','Svalbard and Jan Mayen Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (182,'CE','SE','Sweden','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (183,'CE','CH','Switzerland','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (184,'CE','MK','Republic of Macedonia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (185,'CE','TR','Turkey','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (186,'CE','UA','Ukraine','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (187,'CE','GB','United Kingdom','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (188,'CE','VA','Vatican City','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (189,'CE','YU','Yugoslavia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (190,'ME','IL','Israel','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (191,'ME','JO','Jordan','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (192,'ME','KW','Kuwait','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (193,'ME','LB','Lebanon','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (194,'ME','OM','Oman','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (195,'ME','QA','Qatar','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (196,'ME','SA','Saudi Arabia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (197,'ME','SY','Syria','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (198,'ME','AE','United Arab Emirates','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (199,'ME','YE','Yemen','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (200,'NA','CA','Canada','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (201,'NA','MX','Mexico','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (202,'NA','US','United States','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (204,'SA','BO','Bolivia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (205,'SA','BR','Brazil','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (206,'SA','CL','Chile','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (207,'SA','CO','Colombia','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (208,'SA','EC','Equador','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (209,'SA','FK','Falkland Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (210,'SA','GF','French Guiana','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (211,'SA','GY','Guyana','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (212,'SA','PY','Paraguay','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (213,'SA','PE','Peru','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (214,'SA','SR','Suriname','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (215,'SA','UY','Uruguay','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (216,'SA','VE','Venezuela','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (217,'OT','BH','Bahrain','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (218,'OT','BV','Bouvet Islands','Y');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_countries')." VALUES (219,'OT','IO','British Indian Ocean Territory','Y');";
	
	return $sql;
}

function getDefaultPakStatesSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_states')." (`code`,`name`,`enabled`,`countrycode`) VALUES ('Capital','Capital','Y','PK');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_states')." (`code`,`name`,`enabled`,`countrycode`) VALUES ('Punjab','Punjab','Y','PK');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_states')." (`code`,`name`,`enabled`,`countrycode`) VALUES ('Sind','Sind','Y','PK');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_states')." (`code`,`name`,`enabled`,`countrycode`) VALUES ('NWFP','NWFP','Y','PK');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_states')." (`code`,`name`,`enabled`,`countrycode`) VALUES ('Balochstan','Balochistan','Y','PK');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_states')." (`code`,`name`,`enabled`,`countrycode`) VALUES ('AJK','Azad Jammu Kashmir','Y','PK');";
	
	return $sql;
}

function getDefaultPakCountiesSQL() {
	$db = &JFactory::getDBO();
	
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Chitral','Chitral','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('UpperDir','Upper Dir','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('LowerDir','Lower Dir','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Swat','Swat','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Shangla','Shangla','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Buner','Buner','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Malakand','Malakand P.A.','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kohistan','Kohistan','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Mansehra','Mansehra','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Batagram','Batagram','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Abbottabad','Abbottabad','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Haripur','Haripur','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Mardan','Mardan','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Swabi','Swabi','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Charsadda','Charsadda','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Peshawar','Peshawar','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Nowshera','Nowshera','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kohat','Kohat','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Hangu','Hangu','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Karak','Karak','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Bannu','Bannu','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('DIKhan','Dera Ismail Khan','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Tank','Tank','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('LakkiMarw','Lakki Marwat','Y','PK','NWFP');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Rawalpindi','Rawalpindi','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Jhelum','Jhelum','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Chakwal','Chakwal','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Sargodha','Sargodha','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Bhakkar','Bhakkar','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Khushab','Khushab','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Mianwali','Mianwali','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Faisalabad','Faisalabad','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Jhang','Jhang','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('TTSingh','Toba Tek Singh','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Gujranwala','Gujranwala','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Hafizabad','Hafizabad','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Gujrat','Gujrat','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('MandiBaha','Mandi Bahauddin','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Sialkot','Sialkot','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Narowal','Narowal','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Lahore','Lahore','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kasur','Kasur','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Okara','Okara','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Sheikhupur','Sheikhupura','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Vehari','Vehari','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Sahiwal','Sahiwal','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Pakpattan','Pakpattan','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Multan','Multan','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Lodhran','Lodhran','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Khanewal','Khanewal','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('DGKhan','Dera Ghazi Khan','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Rajanpur','Rajanpur','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Layyah','Layyah','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Muzaffarga','Muzaffargarh','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Bahawalpur','Bahawalpur','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Bahawalnag','Bahawalnagar','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('RYKhan','Rahim Yar Khan','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Attock','Attock','Y','PK','Punjab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Islamabad','Islamabad','Y','PK','Capital');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Shikarpur','Shikarpur','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Larkana','Larkana','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Sukkur','Sukkur','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Ghotki','Ghotki','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Khairpur','Khairpur','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Naushahro','Naushahro Feroze','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('NawabShah','Nawab Shah','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Dadu','Dadu','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Hyderabad','Hyderabad','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Badin','Badin','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Thatta','Thatta','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Sanghar','Sanghar','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('MirpurKha','Mirpur Khas','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Umerkot','Umerkot','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Tharparkar','Tharparkar','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Karachi','Karachi','Y','PK','Sind');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Quetta','Quetta','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Pishin','Pishin','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('KillaAbdu','Killa Abdullah','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Chagai','Chagai','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Loralai','Loralai','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Musakhel','Musakhel','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('KillaSaif','Killa Saifullah','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Zhob','Zhob','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Sibi','Sibi','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Ziarat','Ziarat','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kohlu','Kohlu','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('DeraBugti','Dera Bugti','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Jaffarabad','Jaffarabad','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Nasirabad','Nasirabad','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Bolan','Bolan','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kachhi','Kachhi','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kalat','Kalat','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Mastung','Mastung','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Khuzdar','Khuzdar','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Awaran','Awaran','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kharan','Kharan','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Lasbela','Lasbela','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kech','Kech','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Gwadar','Gwadar','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Panjgur','Panjgur','Y','PK','Balochstan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Bajaur','Bajaur','Y','PK','FATA');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Mohmand','Mohmand','Y','PK','FATA');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Khyber','Khyber','Y','PK','FATA');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Kurram','Kurram','Y','PK','FATA');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('Orakzai','Orakzai','Y','PK','FATA');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('SouthWazi','South Waziristan','Y','PK','FATA');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_counties')." (`code`,`name`,`enabled`,`countrycode`,`statecode`) VALUES ('NorthWazi','North Waziristan','Y','PK','FATA');";
	
	return $sql;
}

function getDefaultPakCitiesSQL() {
	$db = &JFactory::getDBO();

	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chitral','Chitral','Y','PK','NWFP','Chitral');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Drosh','Drosh','Y','PK','NWFP','Chitral');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lutkoh','Lutkoh','Y','PK','NWFP','Chitral');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mastuj','Mastuj','Y','PK','NWFP','Chitral');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Turkoh','Turkoh','Y','PK','NWFP','Chitral');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mulkoh','Mulkoh','Y','PK','NWFP','Chitral');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dir','Dir','Y','PK','NWFP','UpperDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kohistan','Kohistan','Y','PK','NWFP','UpperDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Wari','Wari','Y','PK','NWFP','UpperDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khall','Khall','Y','PK','NWFP','UpperDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Temergara','Temergara','Y','PK','NWFP','LowerDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Balambat','Balambat','Y','PK','NWFP','LowerDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lalqila','Lalqila','Y','PK','NWFP','LowerDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Adenzai','Adenzai','Y','PK','NWFP','LowerDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Munda','Munda','Y','PK','NWFP','LowerDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Swat','Swat','Y','PK','NWFP','Swat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Matta','Matta','Y','PK','NWFP','Swat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Samarbagh','Samarbagh(Barwa)','Y','PK','NWFP','LowerDir');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Alpuri','Alpuri','Y','PK','NWFP','Shangla');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Besham','Besham','Y','PK','NWFP','Shangla');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chakesar','Chakesar','Y','PK','NWFP','Shangla');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Martung','Martung','Y','PK','NWFP','Shangla');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('DaggarBun','Buner','Y','PK','NWFP','Buner');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('SwatRaniz','Swat Ranizai','Y','PK','NWFP','Malakand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('SamRaniza','Sam Ranizai','Y','PK','NWFP','Malakand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dassu','Dassu','Y','PK','NWFP','Kohistan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pattan','Pattan','Y','PK','NWFP','Kohistan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Palas','Palas','Y','PK','NWFP','Kohistan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mansehra','Mansehra','Y','PK','NWFP','Mansehra');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Balakot','Balakot','Y','PK','NWFP','Mansehra');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Oghi','Oghi','Y','PK','NWFP','Mansehra');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Batagram','Batagram','Y','PK','NWFP','Batagram');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Allai','Allai','Y','PK','NWFP','Batagram');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Abbottabad','Abbottabad','Y','PK','NWFP','Abbottabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Haripur','Haripur','Y','PK','NWFP','Haripur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ghazi','Ghazi','Y','PK','NWFP','Haripur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mardan','Mardan','Y','PK','NWFP','Mardan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Swabi','Swabi','Y','PK','NWFP','Swabi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lahore','Lahore','Y','PK','NWFP','Swabi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Charsadda','Charsadda','Y','PK','NWFP','Charsadda');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Tangi','Tangi','Y','PK','NWFP','Charsadda');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Peshawar','Peshawar','Y','PK','NWFP','Peshawar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nowshera','Nowshera','Y','PK','NWFP','Nowshera');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kohat','Kohat','Y','PK','NWFP','Kohat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lachi','Lachi','Y','PK','NWFP','Kohat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hangu','Hangu','Y','PK','NWFP','Hangu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Karak','Karak','Y','PK','NWFP','Karak');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Banda Daud','Banda Daud Shah','Y','PK','NWFP','Karak');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('TakhtENa','Takht-E-Nasrati','Y','PK','NWFP','Karak');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bannu','Bannu','Y','PK','NWFP','Bannu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('DIK','Dera Ismail Khan','Y','PK','NWFP','DIKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Daraban','Daraban','Y','PK','NWFP','DIKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Paharpur','Paharpur','Y','PK','NWFP','DIKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kulachi','Kulachi','Y','PK','NWFP','DIKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Tank','Tank','Y','PK','NWFP','Tank');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Puran','Puran','Y','PK','NWFP','Shangla');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mansehra D','T.A.Adj.Mansehra Distt.','Y','PK','NWFP','Mansehra');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('TakhtBhai','Takht Bhai','Y','PK','NWFP','Mardan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('LakkiMarw','Lakki Marwat','Y','PK','NWFP','LakkiMarw');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hassanabda','Hassanabdal','Y','PK','Punjab','Attock');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('FatehJang','Fateh Jang','Y','PK','Punjab','Attock');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('PindiGheb','Pindi Gheb','Y','PK','Punjab','Attock');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jand','Jand','Y','PK','Punjab','Attock');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Rawalpindi','Rawalpindi','Y','PK','Punjab','Rawalpindi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Taxila','Taxila','Y','PK','Punjab','Rawalpindi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kahuta','Kahuta','Y','PK','Punjab','Rawalpindi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Murree','Murree','Y','PK','Punjab','Rawalpindi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KotliSatt','Kotli Sattian','Y','PK','Punjab','Rawalpindi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('GujarKhan','Gujar Khan','Y','PK','Punjab','Rawalpindi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jhelum','Jhelum','Y','PK','Punjab','Jhelum');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sohawa','Sohawa','Y','PK','Punjab','Jhelum');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('PindDadan','Pind Dadan Khan','Y','PK','Punjab','Jhelum');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dina','Dina','Y','PK','Punjab','Jhelum');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chakwal','Chakwal','Y','PK','Punjab','Chakwal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Talagang','Talagang','Y','PK','Punjab','Chakwal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('ChoaSaida','Choa Saidan Shah','Y','PK','Punjab','Chakwal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sargodha','Sargodha','Y','PK','Punjab','Sargodha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sillanwali','Sillanwali','Y','PK','Punjab','Sargodha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bhalwal','Bhalwal','Y','PK','Punjab','Sargodha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shahpur','Shahpur','Y','PK','Punjab','Sargodha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sahiwal','Sahiwal','Y','PK','Punjab','Sargodha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KotMomin','Kot Momin','Y','PK','Punjab','Sargodha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mankera','Mankera','Y','PK','Punjab','Bhakkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KalurKot','Kalur Kot','Y','PK','Punjab','Bhakkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bhakkar','Bhakkar','Y','PK','Punjab','Bhakkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('DaryaKhan','Darya Khan','Y','PK','Punjab','Bhakkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khushab','Khushab','Y','PK','Punjab','Khushab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nurpur','Nurpur','Y','PK','Punjab','Khushab');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mianwali','Mianwali','Y','PK','Punjab','Mianwali');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('IsaKhel','Isa Khel','Y','PK','Punjab','Mianwali');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Piplan','Piplan','Y','PK','Punjab','Mianwali');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Faisalabad','Faisalabad City','Y','PK','Punjab','Faisalabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Faisalabad','Faisalabad Saddar','Y','PK','Punjab','Faisalabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('ChakJhumr','Chak Jhumra','Y','PK','Punjab','Faisalabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sammundri','Sammundri','Y','PK','Punjab','Faisalabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jaranwala','Jaranwala','Y','PK','Punjab','Faisalabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Tandlianwa','Tandlianwala','Y','PK','Punjab','Faisalabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chiniot','Chiniot','Y','PK','Punjab','Jhang');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jhang','Jhang','Y','PK','Punjab','Jhang');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shorkot','Shorkot','Y','PK','Punjab','Jhang');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('TobaTekS','Toba Tek Singh','Y','PK','Punjab','TTSingh');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kamalia','Kamalia','Y','PK','Punjab','TTSingh');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gojra','Gojra','Y','PK','Punjab','TTSingh');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Wazirabad','Wazirabad','Y','PK','Punjab','Gujranwala');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gujranwalacity','Gujranwala City','Y','PK','Punjab','Gujranwala');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gujranwalasaddar','Gujranwala Saddar','Y','PK','Punjab','Gujranwala');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('NowsheraVirkan','Nowshera Virkan','Y','PK','Punjab','Gujranwala');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kamoki','Kamoki','Y','PK','Punjab','Gujranwala');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hafizabad','Hafizabad','Y','PK','Punjab','Hafizabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('PindiBhat','Pindi Bhattian','Y','PK','Punjab','Hafizabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gujrat','Gujrat','Y','PK','Punjab','Gujrat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kharian','Kharian','Y','PK','Punjab','Gujrat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('SaraiAlam','Sarai Alamgir','Y','PK','Punjab','Gujrat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MandiBaha','Mandi Bahauddin','Y','PK','Punjab','MandiBaha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Malikwal','Malikwal','Y','PK','Punjab','MandiBaha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Phalia','Phalia','Y','PK','Punjab','MandiBaha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sialkot','Sialkot','Y','PK','Punjab','Sialkot');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Daska','Daska','Y','PK','Punjab','Sialkot');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pasrur','Pasrur','Y','PK','Punjab','Sialkot');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Narowal','Narowal','Y','PK','Punjab','Narowal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shakargarh','Shakargarh','Y','PK','Punjab','Narowal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('LahoreCity','Lahore City','Y','PK','Punjab','Lahore');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lahore Cantt','Lahore Cantt.','Y','PK','Punjab','Lahore');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kasur','Kasur','Y','PK','Punjab','Kasur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chunian','Chunian','Y','PK','Punjab','Kasur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pattoki','Pattoki','Y','PK','Punjab','Kasur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Okara','Okara','Y','PK','Punjab','Okara');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Depalpur','Depalpur','Y','PK','Punjab','Okara');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('RenalaKhu','Renala Khurd','Y','PK','Punjab','Okara');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ferozewala','Ferozewala','Y','PK','Punjab','Sheikhupur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('NankanaSa','Nankana Sahib','Y','PK','Punjab','Sheikhupur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sheikhupur','Sheikhupura','Y','PK','Punjab','Sheikhupur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Safdarabad','Safdarabad','Y','PK','Punjab','Sheikhupur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Vehari','Vehari','Y','PK','Punjab','Vehari');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Burewala','Burewala','Y','PK','Punjab','Vehari');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mailsi','Mailsi','Y','PK','Punjab','Vehari');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sahiwal','Sahiwal','Y','PK','Punjab','Sahiwal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chichawatn','Chichawatni','Y','PK','Punjab','Sahiwal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pakpattan','Pakpattan','Y','PK','Punjab','Pakpattan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MultanCit','Multan City','Y','PK','Punjab','Multan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MultanSad','Multan Saddar','Y','PK','Punjab','Multan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shujabad','Shujabad','Y','PK','Punjab','Multan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('JalalpurP','Jalalpur Pirwala','Y','PK','Punjab','Multan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lodhran','Lodhran','Y','PK','Punjab','Lodhran');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KahrorPac','Kahror Pacca','Y','PK','Punjab','Lodhran');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dunyapur','Dunyapur','Y','PK','Punjab','Lodhran');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khanewal','Khanewal','Y','PK','Punjab','Khanewal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jehanian','Jehanian','Y','PK','Punjab','Khanewal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MianChann','Mian Channu','Y','PK','Punjab','Khanewal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kabirwala','Kabirwala','Y','PK','Punjab','Khanewal');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('DGKhan','Dera Ghazi Khan','Y','PK','Punjab','DGKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Taunsa','Taunsa','Y','PK','Punjab','DGKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Rajanpur','Rajanpur','Y','PK','Punjab','Rajanpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Rojhan','Rojhan','Y','PK','Punjab','Rajanpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jampur','Jampur','Y','PK','Punjab','Rajanpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Leiah','Leiah','Y','PK','Punjab','Layyah');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chaubara','Chaubara','Y','PK','Punjab','Layyah');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KarorLal','Karor Lal Esan','Y','PK','Punjab','Layyah');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Muzaffarga','Muzaffargarh','Y','PK','Punjab','Muzaffarga');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Alipur','Alipur','Y','PK','Punjab','Muzaffarga');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jatoi','Jatoi','Y','PK','Punjab','Muzaffarga');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KotAddu','Kot Addu','Y','PK','Punjab','Muzaffarga');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hasilpur','Hasilpur','Y','PK','Punjab','Hasilpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bahawalpur','Bahawalpur','Y','PK','Punjab','Hasilpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Yazman','Yazman','Y','PK','Punjab','Hasilpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('AhmadpurE','Ahmadpur East','Y','PK','Punjab','Hasilpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KhairpurT','Khairpur Tamewali','Y','PK','Punjab','Hasilpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Minchinaba','Minchinabad','Y','PK','Punjab','Bahawalnag');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bahawalnag','Bahawalnagar','Y','PK','Punjab','Bahawalnag');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('FortAbbas','Fort Abbas','Y','PK','Punjab','Bahawalnag');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Haroonabad','Haroonabad','Y','PK','Punjab','Bahawalnag');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chishtian','Chishtian','Y','PK','Punjab','Bahawalnag');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Liaquatpur','Liaquatpur','Y','PK','Punjab','RYKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khanpur','Khanpur','Y','PK','Punjab','RYKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('RahimYar','Rahim Yar Khan','Y','PK','Punjab','RYKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sadiqabad','Sadiqabad','Y','PK','Punjab','RYKhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Attock','Attock','Y','PK','Punjab','Attock');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Islamabad','Islamabad City','Y','PK','Capital','Islamabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jacobabad','Jacobabad','Y','PK','Sind','Jacobabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('GarhiKhai','Garhi Khairo','Y','PK','Sind','Jacobabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Thul','Thul','Y','PK','Sind','Jacobabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kandhkot','Kandhkot','Y','PK','Sind','Jacobabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kashmor','Kashmor','Y','PK','Sind','Jacobabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shikarpur','Shikarpur','Y','PK','Sind','Shikarpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khanrpur','Khanrpur','Y','PK','Sind','Shikarpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('GarhiYasi','Garhi Yasin','Y','PK','Sind','Shikarpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lakhi','Lakhi','Y','PK','Sind','Shikarpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shahdadkot','Shahdadkot','Y','PK','Sind','Larkana');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MiroKhan','Miro Khan','Y','PK','Sind','Larkana');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('RatoDero','Rato Dero','Y','PK','Sind','Larkana');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Larkana','Larkana','Y','PK','Sind','Larkana');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dokri','Dokri','Y','PK','Sind','Larkana');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kambar','Kambar','Y','PK','Sind','Larkana');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Warah','Warah','Y','PK','Sind','Larkana');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sukkur','Sukkur','Y','PK','Sind','Sukkur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Rohri','Rohri','Y','PK','Sind','Sukkur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('PanoAqil','Pano Aqil','Y','PK','Sind','Sukkur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Salehpat','Salehpat','Y','PK','Sind','Sukkur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ghotki','Ghotki','Y','PK','Sind','Ghotki');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khangarh','Khangarh','Y','PK','Sind','Ghotki');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MirpurMat','Mirpur Mathelo','Y','PK','Sind','Ghotki');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ubauro','Ubauro','Y','PK','Sind','Ghotki');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Daharki','Daharki','Y','PK','Sind','Ghotki');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khairpur','Khairpur','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kingri','Kingri','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sobhodero','Sobhodero','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gambat','Gambat','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KotDiji','Kot Diji','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mirwah','Mirwah','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('FaizGanj','Faiz Ganj','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nara','Nara','Y','PK','Sind','Khairpur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kandioro','Kandioro','Y','PK','Sind','Naushahro');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Naushahro','Naushahro Feroze','Y','PK','Sind','Naushahro');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bhiria','Bhiria','Y','PK','Sind','Naushahro');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Moro','Moro','Y','PK','Sind','Naushahro');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sakrand','Sakrand','Y','PK','Sind','NawabShah');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('NawabShah','Nawab Shah','Y','PK','Sind','NawabShah');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Daulatpur','Daulatpur','Y','PK','Sind','NawabShah');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mehar','Mehar','Y','PK','Sind','Dadu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khairpur N','Khairpur Nathan Shah','Y','PK','Sind','Dadu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sehwan','Sehwan','Y','PK','Sind','Dadu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dadu','Dadu','Y','PK','Sind','Dadu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Johi','Johi','Y','PK','Sind','Dadu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kotri','Kotri','Y','PK','Sind','Dadu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('ThanoBula','Thano Bula Khan','Y','PK','Sind','Dadu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hala','Hala','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Matiari','Matiari','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('TandoAlla','Tando Allahyar','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hyderabad','Hyderabad City','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Latifabad','Latifabad','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hyderabad','Hyderabad','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Qasimabad','Qasimabad','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('TandoMoha','Tando Mohammad Khan','Y','PK','Sind','Hyderabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Golarchi','Golarchi','Y','PK','Sind','Badin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Badin','Badin','Y','PK','Sind','Badin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Matli','Matli','Y','PK','Sind','Badin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('TandoBagh','Tando Bagho','Y','PK','Sind','Badin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Talhar','Talhar','Y','PK','Sind','Badin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Thatta','Thatta','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MirpurSak','Mirpur Sakro','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KetiBunde','Keti Bunder','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ghorabari','Ghorabari','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sujawal','Sujawal','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MirpurBat','Mirpur Bathoro','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jati','Jati','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('ShahBunde','Shah Bunder','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KharoChan','Kharo Chan','Y','PK','Sind','Thatta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sanghar','Sanghar','Y','PK','Sind','Sanghar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sinjhoro','Sinjhoro','Y','PK','Sind','Sanghar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khipro','Khipro','Y','PK','Sind','Sanghar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shahdadpur','Shahdadpur','Y','PK','Sind','Sanghar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('JamNawaz','Jam Nawaz Ali','Y','PK','Sind','Sanghar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('TandoAdam','Tando Adam','Y','PK','Sind','Sanghar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MirpurKha','Mirpur Khas','Y','PK','Sind','MirpurKha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Digri','Digri','Y','PK','Sind','MirpurKha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KotGhulam','Kot Ghulam Mohammad','Y','PK','Sind','MirpurKha');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Umerkot','Umerkot','Y','PK','Sind','Umerkot');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Samaro','Samaro','Y','PK','Sind','Umerkot');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kunri','Kunri','Y','PK','Sind','Umerkot');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pithoro','Pithoro','Y','PK','Sind','Umerkot');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chachro','Chachro','Y','PK','Sind','Tharparkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('NagarPark','Nagar Parkar','Y','PK','Sind','Tharparkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Diplo','Diplo','Y','PK','Sind','Tharparkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mithi','Mithi','Y','PK','Sind','Tharparkar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('EntireEa','Entire  East','Y','PK','Sind','Karachi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KarachiWest','Karachi West','Y','PK','Sind','Karachi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KarachiSouth','Karachi South','Y','PK','Sind','Karachi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('EntireUrban','Entire Urban','Y','PK','Sind','Karachi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KarachiCental','Karachi Central','Y','PK','Sind','Karachi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mali','Mali','Y','PK','Sind','Karachi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Quetta','Quetta','Y','PK','Balochstan','Quetta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Panjpai','Panjpai','Y','PK','Balochstan','Quetta');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pishin','Pishin','Y','PK','Balochstan','Pishin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hurramzai','Hurramzai','Y','PK','Balochstan','Pishin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Barshore','Barshore','Y','PK','Balochstan','Pishin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Karezat','Karezat','Y','PK','Balochstan','Pishin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bostan','Bostan','Y','PK','Balochstan','Pishin');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KillaAbdu','Killa Abdullah','Y','PK','Balochstan','KillaAbdu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gulistan','Gulistan','Y','PK','Balochstan','KillaAbdu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chaman','Chaman','Y','PK','Balochstan','KillaAbdu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dobandi','Dobandi','Y','PK','Balochstan','KillaAbdu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nushki','Nushki','Y','PK','Balochstan','Chagai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dalbandin','Dalbandin','Y','PK','Balochstan','Chagai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chagai','Chagai','Y','PK','Balochstan','Chagai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nokundi','Nokundi','Y','PK','Balochstan','Chagai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dak','Dak','Y','PK','Balochstan','Chagai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Taftan','Taftan','Y','PK','Balochstan','Chagai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('LoralaiBo','Loralai/Bori','Y','PK','Balochstan','Loralai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mekhtar','Mekhtar','Y','PK','Balochstan','Loralai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Duki','Duki','Y','PK','Balochstan','Loralai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Barkhan','Barkhan','Y','PK','Balochstan','Barkhan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Musakhel','Musakhel','Y','PK','Balochstan','Musakhel');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kingri','Kingri','Y','PK','Balochstan','Musakhel');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KillaSaif','Killa Saifullah','Y','PK','Balochstan','KillaSaif');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MuslimBag','Muslim Bagh','Y','PK','Balochstan','KillaSaif');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Loiband','Loiband','Y','PK','Balochstan','KillaSaif');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Baddini','Baddini','Y','PK','Balochstan','KillaSaif');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Zhob','Zhob','Y','PK','Balochstan','Zhob');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sherani','Sherani','Y','PK','Balochstan','Zhob');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('QamarDin','Qamar Din Karez','Y','PK','Balochstan','Zhob');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ashwat','Ashwat','Y','PK','Balochstan','Zhob');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sibi','Sibi','Y','PK','Balochstan','Sibi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kutmandai','Kutmandai','Y','PK','Balochstan','Sibi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sangan','Sangan','Y','PK','Balochstan','Sibi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lehri','Lehri','Y','PK','Balochstan','Sibi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ziarat','Ziarat','Y','PK','Balochstan','Ziarat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Harnai','Harnai','Y','PK','Balochstan','Ziarat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sinjawi','Sinjawi','Y','PK','Balochstan','Ziarat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kohlu','Kohlu','Y','PK','Balochstan','Kohlu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kahan','Kahan','Y','PK','Balochstan','Kohlu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mawand','Mawand','Y','PK','Balochstan','Kohlu');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('DeraBugti','Dera Bugti','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sangsillah','Sangsillah','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sui','Sui','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Loti','Loti','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Phelawagh','Phelawagh','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Malam','Malam','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Baiker','Baiker','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('PirKoh','Pir Koh','Y','PK','Balochstan','DeraBugti');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('JhatPat','Jhat Pat','Y','PK','Balochstan','Jaffarabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Panhwar','Panhwar','Y','PK','Balochstan','Jaffarabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('UstaMoham','Usta Mohammad','Y','PK','Balochstan','Jaffarabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gandaka','Gandaka','Y','PK','Balochstan','Jaffarabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Chattar','Chattar','Y','PK','Balochstan','Nasirabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Tamboo','Tamboo','Y','PK','Balochstan','Nasirabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('DMJamali','D.M.Jamali','Y','PK','Balochstan','Nasirabad');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dhadar','Dhadar','Y','PK','Balochstan','Bolan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bhag','Bhag','Y','PK','Balochstan','Bolan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Balanari','Balanari','Y','PK','Balochstan','Bolan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sani','Sani','Y','PK','Balochstan','Bolan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khattan','Khattan','Y','PK','Balochstan','Bolan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mach','Mach','Y','PK','Balochstan','Bolan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gandawa','Gandawa','Y','PK','Balochstan','Kachhi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mirpur','Mirpur','Y','PK','Balochstan','Kachhi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('JhalMagsi','Jhal Magsi','Y','PK','Balochstan','Kachhi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kalat','Kalat','Y','PK','Balochstan','Kalat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mangochar','Mangochar','Y','PK','Balochstan','Kalat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Johan','Johan','Y','PK','Balochstan','Kalat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Surab','Surab','Y','PK','Balochstan','Kalat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gazg','Gazg','Y','PK','Balochstan','Kalat');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mastung','Mastung','Y','PK','Balochstan','Mastung');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dasht','Dasht','Y','PK','Balochstan','Mastung');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KhadKooch','Khad Koocha','Y','PK','Balochstan','Mastung');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Khuzdar','Khuzdar','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Zehri','Zehri','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Moola','Moola','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Karakh','Karakh','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nal','Nal','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Wadh','Wadh','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ornach','Ornach','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Saroona','Saroona','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Aranji','Aranji','Y','PK','Balochstan','Khuzdar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mashkai','Mashkai','Y','PK','Balochstan','Awaran');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Awaran','Awaran','Y','PK','Balochstan','Awaran');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('JhalJao','Jhal Jao','Y','PK','Balochstan','Awaran');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kharan','Kharan','Y','PK','Balochstan','Kharan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Besima','Besima','Y','PK','Balochstan','Kharan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nag','Nag','Y','PK','Balochstan','Kharan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Wasuk','Wasuk','Y','PK','Balochstan','Kharan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mashkhel','Mashkhel','Y','PK','Balochstan','Kharan');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bela','Bela','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Uthal','Uthal','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lakhra','Lakhra','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Liari','Liari','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hub','Hub','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gadani','Gadani','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('SonmianiW','Sonmiani/Winder','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dureji','Dureji','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kanraj','Kanraj','Y','PK','Balochstan','Lasbela');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Kech','Kech','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Buleda','Buleda','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Zamuran','Zamuran','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Hoshab','Hoshab','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Balnigor','Balnigor','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dasht','Dasht','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Tump','Tump','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mand','Mand','Y','PK','Balochstan','Kech');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gwadar','Gwadar','Y','PK','Balochstan','Gwadar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jiwani','Jiwani','Y','PK','Balochstan','Gwadar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Suntsar','Suntsar','Y','PK','Balochstan','Gwadar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pasni','Pasni','Y','PK','Balochstan','Gwadar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ormara','Ormara','Y','PK','Balochstan','Gwadar');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Panjgur','Panjgur','Y','PK','Balochstan','Panjgur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Parome','Parome','Y','PK','Balochstan','Panjgur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gichk','Gichk','Y','PK','Balochstan','Panjgur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Gowargo','Gowargo','Y','PK','Balochstan','Panjgur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Barang','Barang','Y','PK','FATA','Bajaur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Charmang','Charmang','Y','PK','FATA','Bajaur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KharBajau','Khar Bajaur','Y','PK','FATA','Bajaur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Mamund','Mamund','Y','PK','FATA','Bajaur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Salarzai','Salarzai','Y','PK','FATA','Bajaur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Utmankhel(','Utmankhel(Qzafi)','Y','PK','FATA','Bajaur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Nawagai','Nawagai','Y','PK','FATA','Bajaur');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Halimzai','Halimzai','Y','PK','FATA','Mohmand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pindiali','Pindiali','Y','PK','FATA','Mohmand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Safi','Safi','Y','PK','FATA','Mohmand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('UpperMohm','Upper Mohmand','Y','PK','FATA','Mohmand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('UtmanKhel','Utman Khel(Ambar)','Y','PK','FATA','Mohmand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('YakeGhund','Yake Ghund','Y','PK','FATA','Mohmand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Pringhar','Pringhar','Y','PK','FATA','Mohmand');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Bara','Bara','Y','PK','FATA','Khyber');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Jamrud','Jamrud','Y','PK','FATA','Khyber');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('LandiKota','Landi Kotal','Y','PK','FATA','Khyber');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MulaGhori','Mula Ghori','Y','PK','FATA','Khyber');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('LowerKurr','Lower Kurram','Y','PK','FATA','Kurram');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('UpperKurr','Upper Kurram','Y','PK','FATA','Kurram');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('KurramFR','Kurram F.R.','Y','PK','FATA','Kurram');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Central','Central','Y','PK','FATA','Orakzai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Lower','Lower','Y','PK','FATA','Orakzai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Upper','Upper','Y','PK','FATA','Orakzai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ismailzai','Ismailzai','Y','PK','FATA','Orakzai');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Ladha','Ladha','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MakinChar','Makin(Charlai)','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sararogha','Sararogha','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Sarwekai','Sarwekai','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Tiarza','Tiarza','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Wana','Wana','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('ToiKhulla','Toi Khullah','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Birmal','Birmal','Y','PK','FATA','SouthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('DattaKhel','Datta Khel','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Dossali','Dossali','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Garyum','Garyum','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('GhulamKha','Ghulam Khan','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MirAli','Mir Ali','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('MiranShah','Miran Shah','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Razmak','Razmak','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Spinwam','Spinwam','Y','PK','FATA','NorthWazi');";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_cities')." (`code`,`name`,`enabled`,`countrycode`,`statecode`,`countycode`) VALUES ('Shewa','Shewa','Y','PK','FATA','NorthWazi');";
	
	return $sql;
}

function getDefaultFieldOrderingSQL() {
	$db = &JFactory::getDBO();
	$sql = "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (1,'jobtitle','Job Title',1,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (2,'company','Company',2,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (3,'jobcategory','Job Category',3,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (4,'jobtype','Job Type',4,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (5,'jobstatus','Job Status',5,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (6,'country','Country',17,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (7,'state','State',18,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (8,'jobshift','Job Shift',6,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (9,'jobsalaryrange','Job Salary Range',6,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (10,'county','County',19,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (11,'heighesteducation','Heighest Education',8,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (12,'noofjobs','No of Jobs',9,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (13,'experience','Experience',10,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (14,'duration','Duration',11,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (15,'startpublishing','Start Publishing',12,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (16,'stoppublishing','Stop Publishing',13,NULL,2,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (17,'description','Description',14,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (18,'qualifications','Qualifications',15,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (19,'prefferdskills','Prefered Skills',16,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (20,'sendemail','Send Email',21,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (21,'jobcategory','Job Category',2,NULL,1,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (22,'name','Name',1,NULL,1,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (23,'url','URL',3,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (24,'contactname','Contact Name',4,NULL,1,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (25,'contactphone','Contact Phone',5,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (26,'contactemail','Contact Email',6,NULL,1,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (27,'since','Since',8,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (28,'companysize','Company Size',9,NULL,1,0,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (29,'income','Income',10,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (30,'description','Description',11,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (31,'address1','Address1',17,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (32,'logo','Logo',19,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (35,'4',NULL,22,NULL,1,0,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (36,'111','sqls',115,NULL,7,NULL,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (37,'7','Test2',116,'',7,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (38,'8','Combo',118,'',1,0,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (39,'9','Combo2',119,'',1,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (40,'contactfax','Contact Fax',7,NULL,1,0,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (41,'country','Country',12,NULL,1,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (42,'state','State',13,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (43,'county','County',14,'',1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (44,'city','City',15,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (45,'zipcode','Zipcode',16,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (46,'address2','Address2',18,NULL,1,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (47,'10','Text Field',120,'',1,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (48,'11','Check Box',121,'',1,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (49,'12','Date',122,'',1,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (50,'13','Drop Down',123,'',1,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (51,'14','Email',124,'',1,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (52,'15','Editor',125,'',1,1,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (53,'16','Text Area',126,'',1,0,0,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (54,'section_personal','Personal Information',0,'10',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (55,'applicationtitle','Application Title',1,'10',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (56,'firstname','First Name',2,'10',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (57,'middlename','Middle Name',3,'10',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (58,'lastname','Last Name',4,'10',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (59,'emailaddress','Email Address',5,'10',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (60,'homephone','Home Phone',6,'10',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (61,'workphone','Work Phone',7,'10',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (62,'cell','Cell',8,'10',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (63,'nationality','Nationality',9,'10',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (64,'gender','Gender',10,'10',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (65,'photo','Photo',12,'10',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (66,'section_basic','Basic Information',13,'20',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (67,'category','Category',14,'20',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (68,'salary','Salary ',15,'20',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (69,'jobtype','Job Type',16,'20',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (70,'heighesteducation','Heighest Education',17,'20',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (71,'totalexperience','Total Experience',18,'20',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (72,'startdate','Date you can start',19,'20',3,1,1,1);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (75,'section_addresses','Addresses',20,'30',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (76,'section_sub_address','Current Address',21,'31',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (77,'address_country','Country',22,'31',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (78,'address_state','State',23,'31',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (79,'address_county','County',24,'31',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (80,'address_city','City',25,'31',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (81,'address_zipcode','Zip Code',26,'31',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (82,'address_address','Address',27,'31',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (83,'section_sub_address1','Address1',30,'32',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (84,'address1_country','Country',31,'32',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (85,'address1_state','State',32,'32',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (86,'address1_county','County',33,'32',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (87,'address1_city','City',34,'32',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (88,'address1_zipcode','Zip Code',35,'32',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (89,'address1_address','Address',36,'32',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (90,'section_sub_address2','Address1',40,'33',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (91,'address2_country','Country',41,'33',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (92,'address2_state','State',42,'33',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (93,'address2_county','County',43,'33',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (94,'address2_city','City',44,'33',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (95,'address2_zipcode','Zip Code',45,'33',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (96,'address2_address','Address',46,'33',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (97,'section_education','Education',50,'40',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (98,'section_sub_institute','High School',51,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (99,'institute_institute','Institute',52,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (100,'institute_country','Country',53,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (101,'institute_state','State',54,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (102,'institute_county','County',55,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (103,'institute_city','City',56,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (104,'institute_address','Address',57,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (105,'institute_certificate','Certificate Name',58,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (106,'institute_study_area','Study Area',59,'41',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (107,'section_sub_institute1','University',61,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (108,'institute1_institute','Institute',62,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (109,'institute1_country','Country',63,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (110,'institute1_state','State',64,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (111,'institute1_county','County',65,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (112,'institute1_city','City',66,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (113,'institute1_address','Address',67,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (114,'institute1_certificate','Certificate Name',68,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (115,'institute1_study_area','Study Area',69,'42',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (116,'section_sub_institute2','Grade School',71,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (117,'institute2_institute','Institute',72,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (118,'institute2_country','Country',73,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (119,'institute2_state','State',74,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (120,'institute2_county','County',75,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (121,'institute2_city','City',76,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (122,'institute2_address','Address',77,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (123,'institute2_certificate','Certificate Name',78,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (124,'institute2_study_area','Study Area',79,'43',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (125,'section_sub_institute3','Other School',81,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (126,'institute3_institute','Institute',82,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (127,'institute3_country','Country',83,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (128,'institute3_state','State',84,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (129,'institute3_county','County',85,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (130,'institute3_city','City',86,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (131,'institute3_address','Address',87,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (132,'institute3_certificate','Certificate Name',88,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (133,'institute3_study_area','Study Area',89,'44',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (134,'section_employer','Employer',91,'50',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (135,'section_sub_employer','Recent Employer',92,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (136,'employer_employer','Employer',93,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (137,'employer_position','Position',93,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (138,'employer_resp','Responsibilities',94,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (139,'employer_pay_upon_leaving','Pay Upon Leaving',95,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (140,'employer_supervisor','Supervisor',96,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (141,'employer_from_date','From Date',97,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (142,'employer_to_date','To Date',98,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (143,'employer_leave_reason','Leave Reason',99,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (144,'employer_country','Country',100,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (145,'employer_state','State',101,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (146,'employer_county','County',102,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (147,'employer_city','City',103,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (148,'employer_zip','Zip Code',104,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (149,'employer_phone','Phone',105,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (150,'employer_address','Address',106,'51',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (151,'section_sub_employer1','Prior Employer 1',107,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (152,'employer1_employer','Employer',108,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (153,'employer1_position','Position',109,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (154,'employer1_resp','Responsibilities',110,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (155,'employer1_pay_upon_leaving','Pay Upon Leaving',111,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (156,'employer1_supervisor','Supervisor',112,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (157,'employer1_from_date','From Date',113,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (158,'employer1_to_date','To Date',114,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (159,'employer1_leave_reason','Leave Reason',115,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (160,'employer1_country','Country',116,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (161,'employer1_state','State',117,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (162,'employer1_county','County',118,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (163,'employer1_city','City',119,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (164,'employer1_zip','Zip Code',120,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (165,'employer1_phone','Phone',121,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (166,'employer1_address','Address',122,'52',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (167,'section_sub_employer2','Prior Employer 2',125,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (168,'employer2_employer','Employer',126,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (169,'employer2_position','Position',127,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (170,'employer2_resp','Responsibilities',128,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (171,'employer2_pay_upon_leaving','Pay Upon Leaving',129,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (172,'employer2_supervisor','Supervisor',130,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (173,'employer2_from_date','From Date',131,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (174,'employer2_to_date','To Date',132,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (175,'employer2_leave_reason','Leave Reason',133,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (176,'employer2_country','Country',134,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (177,'employer2_state','State',135,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (178,'employer2_county','County',136,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (179,'employer2_city','City',137,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (180,'employer2_zip','Zip Code',138,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (181,'employer2_phone','Phone',139,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (182,'employer2_address','Address',140,'53',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (183,'section_sub_employer3','Prior Employer 3',145,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (184,'employer3_employer','Employer',146,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (185,'employer3_position','Position',147,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (186,'employer3_resp','Responsibilities',148,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (187,'employer3_pay_upon_leaving','Pay Upon Leaving',149,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (188,'employer3_supervisor','Supervisor',150,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (189,'employer3_from_date','From Date',151,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (190,'employer3_to_date','To Date',152,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (191,'employer3_leave_reason','Leave Reason',153,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (192,'employer3_country','Country',154,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (193,'employer3_state','State',155,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (194,'employer3_county','County',156,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (195,'employer3_city','City',157,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (196,'employer3_zip','Zip Code',158,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (197,'employer3_phone','Phone',159,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (198,'employer3_address','Address',160,'54',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (199,'section_skills','Skills',165,'60',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (200,'driving_license','Driving License',166,'60',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (201,'license_no','License No',167,'60',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (202,'license_country','License Country',168,'60',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (203,'skills','Skills',169,'60',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (204,'section_resumeeditor','Resume Editor',175,'70',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (205,'editor','Editor',176,'70',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (206,'fileupload','File Upload',177,'70',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (207,'section_references','References',185,'80',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (208,'section_sub_reference','Reference 1',186,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (209,'reference_reference','Reference',187,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (210,'reference_name','Name',188,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (211,'reference_country','Country',189,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (212,'reference_state','State',190,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (213,'reference_county','County',191,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (214,'reference_city','City',192,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (215,'reference_zipcode','Zip Code',193,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (216,'reference_phone','Phone',194,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (217,'reference_relation','Relation',195,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (218,'reference_years','Years',196,'81',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (219,'section_sub_reference1','Reference 2',200,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (220,'reference1_reference','Reference',201,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (221,'reference1_name','Name',202,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (222,'reference1_country','Country',203,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (223,'reference1_state','State',204,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (224,'reference1_county','County',205,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (225,'reference1_city','City',206,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (226,'reference1_zipcode','Zip Code',207,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (227,'reference1_phone','Phone',208,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (228,'reference1_relation','Relation',209,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (229,'reference1_years','Years',210,'82',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (230,'section_sub_reference2','Reference 3',211,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (231,'reference2_reference','Reference',212,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (232,'reference2_name','Name',213,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (233,'reference2_country','Country',214,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (234,'reference2_state','State',215,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (235,'reference2_county','County',216,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (236,'reference2_city','City',217,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (237,'reference2_zipcode','Zip Code',218,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (238,'reference2_phone','Phone',219,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (239,'reference2_relation','Relation',220,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (240,'reference2_years','Years',221,'83',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (241,'section_sub_reference3','Reference 4',222,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (242,'reference3_reference','Reference',223,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (243,'reference3_name','Name',224,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (244,'reference3_country','Country',225,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (245,'reference3_state','State',226,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (246,'reference3_county','County',227,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (247,'reference3_city','City',228,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (248,'reference3_zipcode','Zip Code',229,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (249,'reference3_phone','Phone',230,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (250,'reference3_relation','Relation',231,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (251,'reference3_years','Years',232,'84',3,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (252,'city','City',20,NULL,2,1,1,0);";
	$sql .= "INSERT INTO ".$db->nameQuote('#__js_job_fieldsordering')." VALUES (253,'Iamavailable','I am Available',11,'10',3,1,1,0);";

	return $sql;
}


?>
