<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 2, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/hotjsjobs.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

$noofjobs = $params->get('noofjobs', 7);
$category = $params->get('category', 1);
$company = $params->get('company', 1);
$jobtype = $params->get('jobtype', 1);
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

$curdate = date('Y-m-d H:i:s');
	
$query = "SELECT COUNT(apply.jobid) as totalapply, job.id, job.title, job.jobcategory, job.created, cat.cat_title
	, company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle 
	FROM ". $db->nameQuote('#__js_job_jobs')." AS job 
	JOIN ". $db->nameQuote('#__js_job_jobapply')." AS apply ON job.id = apply.jobid 
	JOIN ". $db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id 
	JOIN ". $db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
	LEFT JOIN ". $db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
	WHERE job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) ."
	GROUP BY apply.jobid ORDER BY totalapply DESC LIMIT {$noofjobs}";
//echo $query;
$db->setQuery($query);

if ($jobs = $db->loadObjectList()) { 
    echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
	$isodd = 1;
	foreach ($jobs as $job) {
	    $isodd = 1 - $isodd;
		echo '<tr class="'.$trclass[$isodd].'"><td>'
			. '<a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&fr=lj&vj=2&jobcat=' .$job->jobcategory . '&oi=' . $job->id . '&Itemid='.$itemid.'"><u><strong>'
	        . $job->title . '</strong></u></a><br />';
		if ($company == 1) echo '<small>Company: <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=2&md=' .$job->companyid . '&jobcat=' .$job->jobcategory . '&Itemid='.$itemid.'">'.$job->companyname.'</a></small><br />';	
		if ($category == 1) echo '<small>Category: '.$job->cat_title.'</small><br />';	
		if ($jobtype == 1) echo  '<small>Type: '.$job->jobtypetitle.'</small><br />';	
		if ($posteddate == 1) echo  "<small>Posted: ".strftime('%B %d, %Y',strtotime($job->created))."</small><br /><br />";
		if ($separator == 1) echo '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
		echo '</td></tr>';
    }
	echo '</table>';
}
?>

