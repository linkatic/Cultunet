<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Oct 29th 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/newestjsresume.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

$noofresumes = $params->get('noofresumes', 7);
$title = $params->get('title', 1);
$nationality = $params->get('nationality', 1);
$gender = $params->get('gender', 1);
$available = $params->get('available', 1);

$category = $params->get('category', 1);
$jobtype = $params->get('jobtype', 1);
$salaryrange = $params->get('salaryrange', 1);
$highesteducation = $params->get('highesteducation', 1);
$experience = $params->get('experience', 1);
$posteddate = $params->get('posteddate', 1);
$theme = $params->get('theme', 1);
$separator = $params->get('separator', 1);

$itemid =  JRequest::getVar('Itemid');
$db =& JFactory::getDBO();

//$theme = 1; //js jobs  theme
if ($theme == 1){ // js jobs theme
	$trclass = array("odd", "even");
	$query = "SELECT * FROM ". $db->nameQuote('#__js_job_config')." WHERE configname = 'theme' ";
	$db->setQuery($query);
	//echo $query;
	if ($config = $db->loadObject()) $css = $config->configvalue; else $css = 'jsjobs01.css'; 
	if ($css == 'templatetheme.css') $trclass = array("sectiontableentry1", "sectiontableentry2");
?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $css; ?>" />
<?php
}elseif ($theme == 2){ // template theme
	$trclass = array("sectiontableentry1", "sectiontableentry2");
}else 	$trclass = array("", ""); //no theme


$query = "SELECT resume.id, resume.application_title, resume.first_name,resume.last_name 
		,resume.nationality, resume.gender, resume.iamavailable, resume.job_category 
		,resume.jobsalaryrange, resume.jobtype, resume.heighestfinisheducation, resume.total_experience 
		,resume.create_date, cat.cat_title, jobtype.title AS jobtypetitle, education.title as educationtitle
		,country.name AS countryname, salary.rangestart, salary.rangeend
	FROM ". $db->nameQuote('#__js_job_resume')." AS resume 
	JOIN ". $db->nameQuote('#__js_job_categories')." AS cat ON resume.job_category = cat.id 
	JOIN ". $db->nameQuote('#__js_job_jobtypes')." AS jobtype ON resume.jobtype = jobtype.id 

	LEFT JOIN ". $db->nameQuote('#__js_job_heighesteducation')." AS education ON resume.heighestfinisheducation = education.id 
	LEFT JOIN ". $db->nameQuote('#__js_job_salaryrange')." AS salary ON resume.jobsalaryrange = salary.id 
	LEFT JOIN ". $db->nameQuote('#__js_job_countries')." AS country ON resume.nationality = country.code 

	WHERE resume.status = 1 ORDER BY create_date DESC LIMIT {$noofresumes}";
//echo $query;
$db->setQuery($query);

if ($resumes = $db->loadObjectList()) { 
    echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
	$isodd = 1;
	if ($salaryrange == 1){
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_config')." WHERE configname = 'currency' ";
		$db->setQuery( $query );
		$cur = $db->loadObject();
		if ($cur) $currency = $cur->configvalue;
		else $currency = 'Rs';
	}	
	foreach ($resumes as $resume) {
	    $isodd = 1 - $isodd;
		echo '<tr class="'.$trclass[$isodd].'"><td>'
			. '<a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=4&rd='. $resume->id .'&Itemid='.$itemid.'"><u><strong>'
	        . $resume->first_name.' '.$resume->last_name . '</strong></u></a><br />';
		if ($title == 1) echo '<small>Title: '.$resume->application_title.'</small><br />';	
		if ($category == 1) echo '<small>Category: '.$resume->cat_title.'</small><br />';	
		if ($jobtype == 1) echo  '<small>Type: '.$resume->jobtypetitle.'</small><br />';	

		if ($highesteducation == 1) echo  '<small>Highest Education: '.$resume->educationtitle.'</small><br />';	
		if ($salaryrange == 1)	echo  '<small>Salary Range: '.$currency.$resume->rangestart.' - '.$currency.$resume->rangeend .'</small><br />';	
		if ($experience == 1) echo  '<small>Experience: '.$resume->total_experience.'</small><br />';	
		if ($available == 1) { if ($resume->iamavailable == 1) $availabletext = 'Yes'; else $availabletext = 'No'; echo  '<small>Available: '.$availabletext.'</small><br />';}	
		if ($gender == 1) { if ($resume->gender == 1) $gendertext='Male'; else $gendertext='Female'; echo  '<small>Gender: '.$gendertext.'</small><br />';}	
		if ($nationality == 1) echo  '<small>Nationality: '.$resume->countryname.'</small><br />';	

		if ($posteddate == 1) echo  "<small>Posted: ".strftime('%B %d, %Y',strtotime($resume->create_date))."</small><br /><br />";
		if ($separator == 1) echo '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
		echo '</td></tr>';
    }
	echo '</table>';
}

?>

